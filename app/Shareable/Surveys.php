<?php

namespace App\Shareable;

use App\Http\Controllers\Api\Survey\SurveyController;
use App\Payment\PaymentDetails;
use App\Payment\PaymentLinks;
use App\PeopleLike;
use App\Surveys as AppSurveys;
use Illuminate\Support\Facades\Redis;
use App\Traits\HashtagFactory;

class Surveys extends Share
{
    use HashtagFactory;
    protected $fillable = ['profile_id', 'surveys_id', 'payload_id', 'privacy_id', 'content'];
    protected $visible = ['id', 'profile_id', 'created_at', 'content'];

    protected $with = ['surveys'];

    public function surveys()
    {
        return $this->belongsTo(\App\Surveys::class, 'survey_id');
    }

    // public function like()
    // {
    //     return $this->hasMany(\App\Shareable\Sharelikable\surveys::class,'poll_share_id');
    // }

    public function isSurveyReported()
    {
        return $this->isReported(request()->user()->profile->id, "surveys", $this->survey_id, true, $this->id);
    }

    public function getMetaFor($profileId)
    {
        $meta = [];
        $key = "meta:surveysShare:likes:" . $this->id;

        $meta['hasLiked'] = Redis::sIsMember($key, $profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);

        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'surveysShare', request()->user()->profile->id);

        $meta['commentCount'] = $this->comments()->count();

        $survey = \App\Surveys::where('id', $this->surveys_id)->whereNull('deleted_at')->first();
        if ($survey) {
            $meta['originalPostMeta'] = $survey->getMetaFor($profileId); //Because off android this response is changes 
            //from original_post_meta to originalPostMeta 
        }
        $payment = PaymentDetails::where("model_type", "Survey")->where("model_id", $this->surveys_id)->where("is_active", 1)->first();
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
            $new  = new SurveyController(new AppSurveys);
            $getCount = $new->getDispatchedPaymentUserTypes($payment);
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
        $meta['isReported'] =  $this->isSurveyReported();
        $meta['isReviewed'] = ((!empty($reviewed) && $reviewed->application_status == 2) ? true : false);
        $meta['isInterested'] = ((!empty($reviewed)) ? true : false);
        $k = Redis::get("surveys:application_status:$this->id:profile:$profileId");
        $meta['applicationStatus'] = $k !== null ? (int)$k : null;
        return $meta;
    }

    public function getMetaForV2($profileId)
    {
        $meta = [];
        $key = "meta:surveysShare:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key, $profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $survey = \App\Surveys::where('id', $this->surveys_id)->whereNull('deleted_at')->first();
        if ($survey) {
            $meta['originalPostMeta'] = $survey->getMetaFor($profileId); //Because off android this response is changes 
            //from original_post_meta to originalPostMeta 
        }
        $payment = PaymentDetails::where("model_type", "Survey")->where("model_id", $this->surveys_id)->where("is_active", 1)->first();

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
            $new  = new SurveyController(new AppSurveys);
            $getCount = $new->getDispatchedPaymentUserTypes($payment);
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
        $meta['isReported'] =  $this->isSurveyReported();
        $meta['isReviewed'] = ((!empty($reviewed) && $reviewed->application_status == 2) ? true : false);
        $meta['isInterested'] = ((!empty($reviewed)) ? true : false);
        $k = Redis::get("surveys:application_status:$this->id:profile:$profileId");
        $meta['applicationStatus'] = $k !== null ? (int)$k : null;
        return $meta;
    }

    public function getMetaForV2Shared($profileId)
    {
        return $this->getMetaForV2($profileId);
    }

    public function getMetaForPublic()
    {
        $meta = [];
        $key = "meta:surveysShare:likes:" . $this->id;

        $meta['likeCount'] = Redis::sCard($key);

        $meta['commentCount'] = $this->comments()->count();

        return $meta;
    }

    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->surveys_id,
            // 'id' => 1,
            'share_id' => $this->id,
            'content' => null != $this->content ? $this->content : null,
            'image' => null,
            'shared' => true
        ];
    }

    /**
     * @param int $profileId
     * @return array
     */
    public function getSeoTags(): array
    {
        $title = "TagTaste | Share Survey";

        $description = "";
        if (!is_null($this->content)) {
            if (is_array($this->content) && array_key_exists('text', $this->content)) {
                $description = $this->content['text'];
            } else {
                $description = $this->content;
            }
        }

        if (!is_null($description) && strlen($description)) {
            $description = substr($this->getContent($description), 0, 160) . "...";
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
                    "content" => "post, shoutout, feed, user feed, text update, tagtaste post, survey, photo, video",
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
                    "content" => "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/og_logo.png",
                )
            ),
        ];
        return $seo_tags;
    }
}
