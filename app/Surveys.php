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

class Surveys extends Model implements Feedable
{

    use IdentifiesOwner, CachedPayload, SoftDeletes, IdentifiesContentIsReported, HashtagFactory;

    protected $table = "surveys";
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public $incrementing = false;


    protected $fillable = ["id","profile_id","company_id","privacy_id","title","description","image_meta","video_meta","form_json","profile_updated_by","invited_profile_ids","expired_at","is_active","state","deleted_at","published_at","is_private"];
    
    protected $with = ['profile','company'];
    
    protected $appends = ['owner','meta',"closing_reason",'mandatory_fields','totalApplicants'];

    protected $visible = ["id","profile_id","company_id","privacy_id","title","description","image_meta","form_json",
    "video_meta","state","expired_at","published_at","profile","company","created_at","updated_at","is_private","totalApplicants"];

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
            'state' => $this->state,
            'expired_at' => $this->expired_at,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'is_private' => $this->is_private,
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
        $meta['expired_at'] = $this->expired_at;
        $key = "meta:surveys:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key, $profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id', $this->company_id)->where('user_id', request()->user()->id)->exists() : false;

        $meta['answerCount'] = \DB::table('survey_applicants')->where('survey_id', $this->id)->where('application_status', 2)->get()->count();
        $meta['isReported'] =  $this->isSurveyReported();

        $reviewed = \DB::table('survey_applicants')->where('survey_id', $this->id)->where('profile_id', $profileId)->where('application_status', 2)->first();
        // $meta['review_dump'] = $reviewed;
        // $meta['review_param'] = ["survey_id" => $this->id,"profile"=>$profileId];
        $payment = PaymentDetails::where("model_type", "Survey")->where("model_id", $this->id)->where("is_active", 1)->first();
        if (!empty($payment)) {


            $ispaid = true;
            $exp = (!empty($payment) && !empty($payment->excluded_profiles) ? $payment->excluded_profiles : null);
            if ($exp != null) {
                $separate = explode(",", $exp);
                if (in_array(request()->user()->profile->id, $separate)) {
                    //excluded profile error to be updated
                    $ispaid = false;
                }
            }
            if ($ispaid == true) {
                
                $getCount = PaymentHelper::getDispatchedPaymentUserTypes($payment);
                if (request()->user()->profile->is_expert) {
                    $ukey = "expert";
                } else {
                    $ukey = "consumer";
                }

                if ($payment->review_type == config("payment.PAYMENT_REVIEW_TYPE.USER_TYPE")) {
                    $getAmount = json_decode($payment->amount_json, true);
                    if (($getCount[$ukey] + 1) > $getAmount["current"][$ukey][0]["user_count"]) {
                        $ispaid = false;
                    }
                } else {
                    $links = PaymentLinks::where("payment_id", $payment->id)->where("status_id", "<>", config("constant.PAYMENT_CANCELLED_STATUS_ID"))->get();
                    if ((int)$links->count() >=  (int)$payment->user_count) {
                        $ispaid = false;
                    }
                }
            }
        } else {
            $ispaid = false;
        }
        $meta['isPaid'] = $ispaid;
        $meta['isReviewed'] = (!empty($reviewed) ? true : false);

        $meta['isInterested'] = ((!empty($reviewed)) ? true : false);
        $k = Redis::get("surveys:application_status:$this->id:profile:$profileId");
        $meta['applicationStatus'] = $k !== null ? (int)$k : null;


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
        $meta['answerCount'] = \DB::table('survey_applicants')->where('survey_id', $this->id)->where('application_status', 2)->get()->count();
        $meta['isReported'] =  $this->isSurveyReported();


        $reviewed = \DB::table('survey_applicants')->where('survey_id', $this->id)->where('profile_id', $profileId)->where('application_status', 2)->first();
        $meta['isReviewed'] = (!empty($reviewed) ? true : false);
        $payment = PaymentDetails::where("model_type", "Survey")->where("model_id", $this->id)->where("is_active", 1)->first();
        if (!empty($payment)) {


            $ispaid = true;
            $exp = (!empty($payment) && !empty($payment->excluded_profiles) ? $payment->excluded_profiles : null);
            if ($exp != null) {
                $separate = explode(",", $exp);
                if (in_array(request()->user()->profile->id, $separate)) {
                    //excluded profile error to be updated
                    $ispaid = false;
                }
            }
            
            $getCount = PaymentHelper::getDispatchedPaymentUserTypes($payment);
            if (request()->user()->profile->is_expert) {
                $ukey = "expert";
            } else {
                $ukey = "consumer";
            }

            if ($payment->review_type == config("payment.PAYMENT_REVIEW_TYPE.USER_TYPE")) {
                $getAmount = json_decode($payment->amount_json, true);
                if (($getCount[$ukey] + 1) > $getAmount["current"][$ukey][0]["user_count"]) {
                    $ispaid = false;
                }
            } else {
                $links = PaymentLinks::where("payment_id", $payment->id)->where("status_id", "<>", config("constant.PAYMENT_CANCELLED_STATUS_ID"))->get();
                if ((int)$links->count() >=  (int)$payment->user_count) {
                    $ispaid = false;
                }
            }
        } else {
            $ispaid = false;
        }
        $meta['isPaid'] = $ispaid;

        $meta['isInterested'] = ((!empty($reviewed)) ? true : false);
        $payment = PaymentDetails::where("model_type", "Survey")->where("model_id", $this->id)->where("is_active", 1)->first();

        $k = Redis::get("surveys:application_status:$this->id:profile:$profileId");
        $meta['applicationStatus'] = $k !== null ? (int)$k : null;


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
        $meta['isPaid'] = (!empty($payment) ? true : false);
        //NOTE NIKHIL : Add answer count in here like poll count 
        // $meta['vote_count'] = \DB::table('poll_votes')->where('poll_id',$this->id)->count();
        return $meta;
    }

    public function getSeoTags(): array
    {
        $title = "TagTaste | " . $this->title . " | Survey";
        $description = "";
        if (!is_null($this->description)) {
            $description = substr(htmlspecialchars_decode($this->description), 0, 160) . "...";
        } else {
            $description = "World's first online community for food professionals to discover, network and collaborate with each other.";
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
                    "content" => "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/icon_survey.png",
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

    public function getMandatoryFields()
    {
        return \DB::table('surveys_mandatory_fields')
            ->join('surveys_mandatory_fields_mapping', 'surveys_mandatory_fields.id', '=', 'surveys_mandatory_fields_mapping.mandatory_field_id')
            ->where('surveys_mandatory_fields_mapping.survey_id', $this->id)
            ->get()->toArray();
    }
  

    public function getTotalApplicantsAttribute()
    {
        if($this->is_private == 1 && request()->user()->profile->id==$this->profile_id){
            return \DB::table('survey_applicants')->where('survey_id', $this->id)->whereNull('deleted_at')->get()->count();
        }
        
        return 0;
    }

}
