<?php

namespace App;

use App\Channel\Payload;
use App\Http\Controllers\Api\Survey\SurveyController;
use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use App\Traits\IdentifiesContentIsReported;
use App\Traits\HashtagFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;
use App\Payment\PaymentDetails;
use App\Payment\PaymentLinks;
use App\surveyApplicants;

class Surveys extends Model implements Feedable
{

    use IdentifiesOwner, CachedPayload, SoftDeletes, IdentifiesContentIsReported, HashtagFactory;

    protected $table = "surveys";
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public $incrementing = false;


    protected $fillable = ["id", "profile_id", "company_id", "privacy_id", "title", "description", "image_meta", "video_meta", "videos_meta", "form_json", "multi_submission", "profile_updated_by", "invited_profile_ids", "expired_at", "is_active", "state", "deleted_at", "published_at", "is_private", "is_section"];

    protected $with = ['profile', 'company'];

    protected $appends = ['owner', 'meta', "closing_reason", 'mandatory_fields', 'totalApplicants'];

    protected $visible = [
        "id", "profile_id", "company_id", "privacy_id", "title", "description", "image_meta", "form_json",
        "video_meta", "videos_meta", "state", "multi_submission", "expired_at", "published_at", "profile", "company", "created_at", "updated_at", "is_private", "totalApplicants", "is_section"
    ];

    protected $cast = [
        "form_json" => 'array',
    ];


    public function addToCache()
    {
        $data = [
            'id' => $this->id,
            'profile_id' => $this->profile_id,
            'company_id' => $this->company_id,
            'privacy_id' => $this->privacy_id,
            'title' => $this->title,
            'description' => $this->description,
            'image_meta' => json_decode($this->image_meta),
            'video_meta' => json_decode($this->video_meta),
            'videos_meta' => json_decode($this->videos_meta),
            'state' => $this->state,
            'expired_at' => $this->expired_at,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'is_private' => $this->is_private,
            'multi_submission' => $this->multi_submission,
        ];

        Redis::set("surveys:" . $this->id, json_encode($data));
    }

    public function removeFromCache()
    {
        Redis::del("surveys:" . $this->id);
    }

    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }

    public function company()
    {
        return $this->belongsTo(\App\Recipe\Company::class);
    }

    public function isSurveyReported()
    {
        return $this->isReported(request()->user()->profile->id, "surveys", (string)$this->id);
    }

    public function getMetaFor(int $profileId): array
    {
        $meta = [];
        // $meta['seen_count'] = "0";
        $meta['expired_at'] = $this->expired_at;
        $key = "meta:surveys:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key, $profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id', $this->company_id)->where('user_id', request()->user()->id)->exists() : false;

        $meta['answerCount'] = SurveyAttemptMapping::where("survey_id",$this->id)->whereNotNull("completion_date")->whereNull("deleted_at")->get()->count();
        $meta['isReported'] =  $this->isSurveyReported();

        $reviewed = SurveyAttemptMapping::where("survey_id",$this->id)->where("profile_id",$profileId)->whereNotNull("completion_date")->orderBy("completion_date","desc")->first();
        // $meta['review_dump'] = $reviewed;
        // $meta['review_param'] = ["survey_id" => $this->id,"profile"=>$profileId];
        $payment = PaymentDetails::where("model_type", "Survey")->where("model_id", $this->id)->where("is_active", 1)->first();


        $meta['isPaid'] = PaymentHelper::getisPaidMetaFlag($payment);
        $meta['isReviewed'] = (!empty($reviewed) ? true : false);

        $meta['isInterested'] = ((!empty($reviewed)) ? true : false);

        $isReviewed = surveyApplicants::where("survey_id",$this->id)->where("profile_id",$profileId)->whereNull('deleted_at')->first();
        
        $meta['isInterested'] = false;
        if($isReviewed != null){
            $meta['isInterested'] = true;
            $meta['is_invited'] = $isReviewed->is_invited;
        }
        
        $k = Redis::get("surveys:application_status:$this->id:profile:$profileId");
        $meta['applicationStatus'] = $k !== null ? (int)$k : null;
        $meta['submissionCount'] = (!empty($reviewed) ? $reviewed->attempt : 0);
        return $meta;
    }

    public function getMetaForV2(int $profileId): array
    {
        $meta = [];
        $meta['expired_at'] = $this->expired_at;
        $key = "meta:surveys:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key, $profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id', $this->company_id)->where('user_id', request()->user()->id)->exists() : false;
        $meta['answerCount'] =  SurveyAttemptMapping::where("survey_id",$this->id)->whereNotNull("completion_date")->whereNull("deleted_at")->get()->count();
        $meta['isReported'] =  $this->isSurveyReported();


        $reviewed = SurveyAttemptMapping::where("survey_id",$this->id)->where("profile_id",$profileId)->whereNotNull("completion_date")->orderBy("completion_date","desc")->first();
        $meta['isReviewed'] = (!empty($reviewed) ? true : false);
        $payment = PaymentDetails::where("model_type", "Survey")->where("model_id", $this->id)->where("is_active", 1)->first();

        $meta['isPaid'] = PaymentHelper::getisPaidMetaFlag($payment);

        $meta['isInterested'] = ((!empty($reviewed)) ? true : false);

        $isReviewed = surveyApplicants::where("survey_id",$this->id)->where("profile_id",$profileId)->whereNull('deleted_at')->first();

        $meta['isInterested'] = false;
        if($isReviewed != null){
            $meta['isInterested'] = true;
            $meta['is_invited'] = $isReviewed->is_invited;
        }

        $payment = PaymentDetails::where("model_type", "Survey")->where("model_id", $this->id)->where("is_active", 1)->first();

        $k = Redis::get("surveys:application_status:$this->id:profile:$profileId");
        $meta['applicationStatus'] = $k !== null ? (int)$k : null;
        $meta['submissionCount'] = (!empty($reviewed) ? $reviewed->attempt : 0);

        return $meta;
    }

    public function privacy()
    {
        return $this->belongsTo(Privacy::class);
    }

    public function payload()
    {
        return $this->belongsTo(Payload::class, 'payload_id');
    }

    public function getCommentNotificationMessage(): string
    {
        return "New comment on " . $this->title . ".";
    }

    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'content' => $this->title,
            'image' => $this->image_meta,
        ];
    }

    public function getRelatedKey(): array
    {
        if (empty($this->relatedKey) && $this->company_id === null) {
            return ['profile' => 'profile:small:' . $this->profile_id];
        }
        return ['company' => 'company:small:' . $this->company_id];
    }

    public function comments()
    {
        return $this->belongsToMany('App\Comment', 'comment_surveys', 'surveys_id', 'comment_id');
    }

    public function getPreviewContent()
    {
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_feed/' . $this->id;
        $data['title'] = substr($this->title, 0, 65);
        $data['description'] = "by " . $this->owner->name;
        $data['ogTitle'] = "Survey: " . substr($this->title, 0, 65);
        $data['ogDescription'] = "by " . $this->owner->name;
        $images = $this->image_meta != null ? $this->image_meta : null;
        $data['cardType'] = isset($images) ? 'summary_large_image' : 'summary';
        $data['ogImage'] = 'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/icon_survey.png';
        $data['ogUrl'] = env('APP_URL') . '/surveys/' . $this->id;
        $data['redirectUrl'] = env('APP_URL') . '/surveys/' . $this->id;

        return $data;
    }

    public function getOwnerAttribute()
    {
        return $this->owner();
    }

    public function getMetaAttribute()
    {
        $meta = [];
        $meta['expired_at'] = $this->expired_at;
        $key = "meta:surveys:likes:" . $this->id;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['answerCount'] = \DB::table('survey_applicants')->where('survey_id', $this->id)->where('application_status', 2)->get()->count();
        $payment = PaymentDetails::where("model_type", "Survey")->where("model_id", $this->id)->where("is_active", 1)->first();
        $meta['isPaid'] = PaymentHelper::getisPaidMetaFlag($payment);
        //NOTE NIKHIL : Add answer count in here like poll count 
        // $meta['vote_count'] = \DB::table('poll_votes')->where('poll_id',$this->id)->count();
        return $meta;
    }

    public function getSeoTags(): array
    {
        $title = "TagTaste | " . $this->title . " | Survey";
        $description = "";
        if (!is_null($this->description)) {
            $description = mb_convert_encoding(substr(strip_tags($this->description), 0, 160), 'UTF-8', 'UTF-8') . "...";
        } else {
            $description = "World's first online community for food professionals to discover, network and collaborate with each other.";
        }

        $image = null;
        if (gettype($this->image_meta) != 'array') {
            $this->image_meta = json_decode($this->image_meta, true);
        }

        if (isset($this->image_meta) && $this->image_meta != null && $this->image_meta != '') {
            $image = $this->image_meta[0]['original_photo'] ?? null;
        }



        $seo_tags = [
            "title" => $title,
            "meta" => array(
                array(
                    "name" => "description",
                    "content" => $description,
                ),
                array(
                    "name" => "keywords",
                    "content" => "survey, surveys, online survey, food survey, TagTaste survey",
                )
            ),
            "og" => array(
                array(
                    "property" => "og:title",
                    "content" => $title,
                ),
                array(
                    "property" => "og:description",
                    "content" => $description,
                ),
                array(
                    "property" => "og:image",
                    "content" => $image,
                )
            ),
        ];
        return $seo_tags;
    }

    public function getClosingReason()
    {
        $reason = [
            'reason' => null,
            'other_reason' => null
        ];
        $reason_value = \DB::table('surveys_close_reasons')
            ->where('survey_id', (int)$this->id)
            ->orderBy('id', 'desc')
            ->first();

        if (!empty($reason_value)) {
            $reason['reason'] = $reason_value->reason;
            $reason['other_reason'] = $reason_value->other_reason;
        } else {
            return null;
        }
        return $reason;
    }

    public function getSubmissionList()
    {
        $profileId = request()->user()->profile->id;
        $surveyAttempt = SurveyAttemptMapping::select('id','profile_id','attempt','completion_date')->where("survey_id", "=", $this->id)->where("profile_id","=",$profileId)->where("deleted_at", "=", null)->whereNotNull("completion_date")->orderBy('completion_date', 'desc')->get()->toArray();

        sort($surveyAttempt);

        return $surveyAttempt;
    }

    public function getMandatoryFields()
    {
        return \DB::table('surveys_mandatory_fields')
            ->join('surveys_mandatory_fields_mapping', 'surveys_mandatory_fields.id', '=', 'surveys_mandatory_fields_mapping.mandatory_field_id')
            ->where('surveys_mandatory_fields_mapping.survey_id', $this->id)
            ->get()->toArray();
    }


    public function getTotalApplicantsAttribute()
    {
        $c = false;
        if (isset($this->company_id) && !empty($this->company_id)) {
            $companyId = $this->company_id;
            $userId = request()->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if ($userBelongsToCompany) {
                $c = true;
            }
        }
        if ($this->is_private == 1 && ($c || request()->user()->profile->id == $this->profile_id)) {
            return \DB::table('survey_applicants')->where('survey_id', $this->id)->whereNull('deleted_at')->get()->count();
        }

        return 0;
    }

    public function addToGraph()
    {
        $data = [
            'id' => $this->id,
            'survey_id' => $this->id,
            'title' => substr($this->title, 0, 150),
            'state' => $this->state,
            'profile_id' => $this->profile_id,
            'company_id' => $this->company_id,
            'payload_id' => $this->payload_id,
            'created_at' => $this->created_at
        ];

        $survey = \App\Neo4j\Surveys::where('survey_id', $data['id'])->first();
        if (!$survey) {
            \App\Neo4j\Surveys::create($data);
        } else {
            unset($data['id']);
            \App\Neo4j\Surveys::where('survey_id', $data['survey_id'])->update($data);
        }
    }

    public function addParticipationEdge($profileId)
    {
        $userProfile = \App\Neo4j\User::where('profile_id', $profileId)->first();
        $survey = \App\Neo4j\Surveys::where('survey_id', $this->id)->first();
        if ($userProfile && $survey) {
            $isUserParticipated = $userProfile->survey_participated->where('survey_id', $this->id)->first();
            if (!$isUserParticipated) {
                $relation = $userProfile->survey_participated()->attach($survey);
                $relation->save();
            } else {
                $relation = $userProfile->survey_participated()->edge($survey);
                $relation->save();
            }
        }
    }

    public function removeFromGraph()
    {
        $surveyCount = \App\Neo4j\Surveys::where('survey_id', $this->id)->count();
        if ($surveyCount > 0) {
            $client = config('database.neo4j_uri_client');
            $query = "MATCH (p:Surveys{survey_id:'$this->id'})
                        DETACH DELETE p;";
            $result = $client->run($query);
        }
    }
}
