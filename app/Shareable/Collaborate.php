<?php

namespace App\Shareable;

use App\Payment\PaymentDetails;
use App\Payment\PaymentLinks;
use App\PaymentHelper;
use App\PeopleLike;
use Illuminate\Support\Facades\Redis;
use App\Traits\HashtagFactory;

class Collaborate extends Share
{
    use HashtagFactory;
    protected $fillable = ['profile_id','collaborate_id','payload_id','privacy_id','content'];
    protected $visible = ['id','profile_id','created_at','content'];

    protected $with = ['collaborate'];

    public static function boot()
    {
        static::created(function($model){
            $matches = $model->hasHashtags($model);
            if(count($matches)) {
                $model->createHashtag($matches,'App\Shareable\Collaborate',$model->id);
            }
        });
        static::updated(function($model){
            $model->deleteExistingHashtag('App\Shareable\Collaborate',$model->id);
            $matches = $model->hasHashtags($model);
            if(count($matches)) {
                $model->createHashtag($matches,'App\Shareable\Collaborate',$model->id);
            }
        });
        static::deleted(function($model){
            
                $model->deleteExistingHashtag('App\Shareable\Collaborate',$model->id);
            $model->payload->delete();
        });
    }

    public function collaborate()
    {
        return $this->belongsTo(\App\Collaborate::class,'collaborate_id');
    }

    public function like()
    {
        return $this->hasMany(\App\Shareable\Sharelikable\Collaborate::class,'collaborate_share_id');
    }

    public function isCollaborateReported()
    {
        return $this->isReported(request()->user()->profile->id, "collaborate", $this->collaborate_id, true, $this->id);
    }

    public function getMetaFor($profileId){
        $meta = [];
        $key = "meta:collaborateShare:likes:" . $this->id;
    
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);

        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'collaborateShare' ,request()->user()->profile->id);

        $meta['commentCount'] = $this->comments()->count();
        $collaborate = \App\Collaborate::where('id',$this->collaborate_id)->first();
        $meta['original_post_meta'] = $collaborate->getMetaFor($profileId);
        $meta['isReported'] =  $this->isCollaborateReported();
        $payment = PaymentDetails::where("model_type","Private Review")->where("model_id",$this->collaborate_id)->where("is_active",1)->first();
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

        return $meta;
    }

    public function getMetaForV2($profileId)
    {
        $meta = [];
        $key = "meta:collaborateShare:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['isReported'] =  $this->isCollaborateReported();
        $payment = PaymentDetails::where("model_type","Private Review")->where("model_id",$this->collaborate_id)->where("is_active",1)->first();
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
        return $meta;
    }

    public function getMetaForV2Shared($profileId)
    {
        $meta = [];
        $key = "meta:collaborateShare:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $collaborate = \App\Collaborate::where('id',$this->collaborate_id)->first();
        $meta['originalPostMeta'] = $collaborate->getMetaForV2($profileId);
        $meta['isReported'] =  $this->isCollaborateReported();
        $payment = PaymentDetails::where("model_type","Private Review")->where("model_id",$this->collaborate_id)->where("is_active",1)->first();
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
        return $meta;
    }

    public function getMetaForPublic(){
        $meta = [];
        $key = "meta:collaborateShare:likes:" . $this->id;

        $meta['likeCount'] = Redis::sCard($key);

        $meta['commentCount'] = $this->comments()->count();

        return $meta;
    }

    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->collaborate->id,
            'share_id' => $this->id,
            'content' => null != $this->content ? $this->content : $this->collaborate->looking_for,
            'image' => null,
            'shared' => true
        ];
    }

    /**
     * @param int $profileId
     * @return array
     */
    public function getSeoTags() : array
    {
        $title = "TagTaste | Post";

        $description = "";
        if (!is_null($this->content)) {
            if (is_array($this->content) && array_key_exists('text', $this->content)) {
                $description = $this->content['text'];
            } else {
                $description = $this->content;
            }
        }

        if (!is_null($description) && strlen($description)) {
            $description = substr($this->getContent($description),0,160)."...";
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
                    "content" => "post, shoutout, feed, user feed, text update, tagtaste post, poll, photo, video",
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
