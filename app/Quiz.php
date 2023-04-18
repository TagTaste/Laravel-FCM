<?php

namespace App;

use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Channel\Payload;
use App\Traits\IdentifiesOwner;
use App\Traits\IdentifiesContentIsReported;
use App\Traits\HashtagFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;
use App\Payment\PaymentDetails;
use App\QuizApplicants;


class Quiz extends Model implements Feedable
{

    use IdentifiesOwner, CachedPayload, SoftDeletes, IdentifiesContentIsReported, HashtagFactory;

    protected $table = "quizes";
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public $incrementing = false;


    protected $fillable = ["id", "profile_id", "company_id", "title", "description", "image_meta", "form_json", "payload_id", "updated_by", "expired_at", "state", "deleted_at", "replay", "privacy_id"];

    protected $with = ['profile', 'company'];

    protected $appends = ['owner', 'meta', 'totalApplicants', 'application_status', 'score_text'];
    protected $visible = [
        "id", "profile_id", "company_id", "privacy_id", "title", "description", "replay", "image_meta", "form_json",
        "video_meta", "state", "expired_at", "published_at", "profile", "company", "created_at", "updated_at", "is_private", "totalApplicants", 'application_status', 'score_text'
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
            'payload_id' => $this->payload_id,
            'title' => $this->title,
            'description' => $this->description,
            'image_meta' => json_decode($this->image_meta),
            'state' => $this->state,
            'expired_at' => $this->expired_at,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'replay' => $this->replay,
        ];

        Redis::set("quiz:" . $this->id, json_encode($data));
    }

    public function removeFromCache()
    {
        Redis::del("quiz:" . $this->id);
    }

    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }

    public function payload()
    {
        return $this->belongsTo(Payload::class, 'payload_id');
    }

    public function privacy()
    {
        return $this->belongsTo(Privacy::class);
    }

    public function company()
    {
        return $this->belongsTo(\App\Recipe\Company::class);
    }

    public function getOwnerAttribute()
    {
        return $this->owner();
    }

    public function comments()
    {
        return $this->belongsToMany('App\Comment', 'comment_quiz', 'quiz_id', 'comment_id');
    }

    public function getMetaFor(int $profileId): array
    {
        $meta = [];
        // $meta['seen_count'] = "0";
        $meta['expired_at'] = $this->expired_at;
        $key = "meta:quiz:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key, $profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id', $this->company_id)->where('user_id', request()->user()->id)->exists() : false;

        $meta['answerCount'] = \DB::table('quiz_applicants')->where('quiz_id', $this->id)->where('application_status', 2)->get()->count();
        $reviewed = \DB::table('quiz_applicants')->where('quiz_id', $this->id)->where('profile_id', $profileId)->where("application_status", 2)->first();
        $meta['isReviewed'] = (!empty($reviewed) ? true : false);


        $payment = PaymentDetails::where("model_type", "quiz")->where("model_id", $this->id)->where("is_active", 1)->first();

        $meta['isPaid'] = PaymentHelper::getisPaidMetaFlag($payment);

        $k = Redis::get("quiz:application_status:$this->id:profile:$profileId");
        $meta['applicationStatus'] = $k !== null ? (int)$k : null;
        $meta['score_text']=$this->getScoreTextAttribute();

        return $meta;
    }

    public function getMetaForV2(int $profileId): array
    {
        $meta = [];
        $meta['expired_at'] = $this->expired_at;
        $key = "meta:quiz:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key, $profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id', $this->company_id)->where('user_id', request()->user()->id)->exists() : false;
        $meta['answerCount'] = \DB::table('quiz_applicants')->where('quiz_id', $this->id)->where('application_status', 2)->get()->count();
        $reviewed = QuizApplicants::where('quiz_id', $this->id)->where('profile_id', $profileId)->where("application_status", 2)->first();

        $payment = PaymentDetails::where("model_type", "quiz")->where("model_id", $this->id)->where("is_active", 1)->first();

        $meta['isPaid'] = PaymentHelper::getisPaidMetaFlag($payment);
        $k = Redis::get("quiz:application_status:$this->id:profile:$profileId");

        $meta['applicationStatus'] = $k !== null ? (int)$k : null;
        $meta['score_text']=$this->getScoreTextAttribute();

        return $meta;
    }
    public function getMetaAttribute()
    {
        $meta = [];
        $meta['expired_at'] = $this->expired_at;
        $key = "meta:quiz:likes:" . $this->id;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['answerCount'] = \DB::table('quiz_applicants')->where('quiz_id', $this->id)->where('application_status', 2)->get()->count();
        $payment = PaymentDetails::where("model_type", "quiz")->where("model_id", $this->id)->where("is_active", 1)->first();
        $meta['isPaid'] = PaymentHelper::getisPaidMetaFlag($payment);
        //NOTE NIKHIL : Add answer count in here like poll count 
        // $meta['vote_count'] = \DB::table('poll_votes')->where('poll_id',$this->id)->count();
        $k = Redis::get("quiz:application_status:$this->id:profile:" . request()->user()->profile->id);
        $meta['applicationStatus'] = $k !== null ? (int)$k : null;
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id', $this->company_id)->where('user_id', request()->user()->id)->exists() : false;

        return $meta;
    }

    public function getClosingReason()
    {
        $reason = [
            'reason' => null,
            'other_reason' => null
        ];
        $reason_value = \DB::table('quiz_close_reasons')
            ->where('quiz_id', (int)$this->id)
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
        if ($c || request()->user()->profile->id == $this->profile_id) {
            return \DB::table('quiz_applicants')->where('quiz_id', $this->id)->where("application_status", config("constant.QUIZ_APPLICANT_ANSWER_STATUS.COMPLETED"))->whereNull('deleted_at')->get()->count();
        }

        return 0;
    }

    public function getApplicationStatusAttribute()
    {
        $k = Redis::get("quiz:application_status:$this->id:profile:" . request()->user()->profile->id);
        $k = $k !== null ? (int)$k : null;
        return $k;
    }

    public function getScoreTextAttribute()
    {
        $reviewed = QuizApplicants::where('quiz_id', $this->id)->where('profile_id', request()->user()->profile->id)->where("application_status", 2)->first();
        $applicantScore = isset($reviewed->applicant_score) ? json_decode($reviewed->applicant_score ): null;
        $score_text =0;
        if (!empty($applicantScore)) {
            foreach ($applicantScore as $applicant) {
                $score_text = $applicant->score_text . "% Scored";
            }
        }
        return $score_text;
    }

    public function getSeoTags(): array
    {
        $title = "TagTaste | " . $this->title . " | Quiz";
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
                    "content" => "quiz, quizzes, online quiz, food quiz, TagTaste quiz",
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

    public function getRelatedKey(): array
    {
        if (empty($this->relatedKey) && $this->company_id === null) {
            return ['profile' => 'profile:small:' . $this->profile_id];
        }
        return ['company' => 'company:small:' . $this->company_id];
    }

    public function getPreviewContent()
    {
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_feed/' . $this->id;
        $data['title'] = substr($this->title, 0, 65);
        $data['description'] = "by " . $this->owner->name;
        $data['ogTitle'] = "Quiz: " . substr($this->title, 0, 65);
        $data['ogDescription'] = "by " . $this->owner->name;
        $images = $this->image_meta != null ? $this->image_meta : null;
        $data['cardType'] = isset($images) ? 'summary_large_image' : 'summary';
        $data['ogImage'] = 'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/icon_survey.png';
        $data['ogUrl'] = env('APP_URL') . '/quiz/' . $this->id;
        $data['redirectUrl'] = env('APP_URL') . '/quiz/' . $this->id;

        return $data;
    }

    public function addToGraph()
    {
        $data = [
            'id' => $this->id,
            'quiz_id' => $this->id,
            'title' => substr($this->title, 0, 150),
            'state' => $this->state,
            'profile_id' => $this->profile_id,
            'company_id' => $this->company_id,
            'payload_id' => $this->payload_id,
            'created_at' => $this->created_at
        ];

        $quiz = \App\Neo4j\Quiz::where('quiz_id', $data['id'])->first();
        if (!$quiz) {
            \App\Neo4j\Quiz::create($data);
        } else {
            unset($data['id']);
            \App\Neo4j\Quiz::where('quiz_id', $data['quiz_id'])->update($data);
        }
    }

    public function addParticipationEdge($profileId)
    {
        $userProfile = \App\Neo4j\User::where('profile_id', $profileId)->first();
        $quiz = \App\Neo4j\Quiz::where('quiz_id', $this->id)->first();
        if ($userProfile && $quiz) {
            $isUserParticipated = $userProfile->quiz_participated->where('quiz_id', $this->id)->first();
            if (!$isUserParticipated) {
                $relation = $userProfile->quiz_participated()->attach($quiz);
                $relation->save();
            } else {
                $relation = $userProfile->quiz_participated()->edge($quiz);
                $relation->save();
            }
        }
    }

    public function removeFromGraph()
    {
        $quizCount = \App\Neo4j\Quiz::where('quiz_id', $this->id)->count();
        if ($quizCount > 0) {
            $client = config('database.neo4j_uri_client');
            $query = "MATCH (p:Quizes{quiz_id:'$this->id'})
                        DETACH DELETE p;";
            $result = $client->run($query);
        }
    }
}
