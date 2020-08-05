<?php

namespace App\Shareable;

use App\PeopleLike;
use Illuminate\Support\Facades\Redis;

class Polling extends Share
{
    protected $fillable = ['profile_id','poll_id','payload_id','privacy_id','content'];
    protected $visible = ['id','profile_id','created_at','content'];

    protected $with = ['polling'];

    public static function boot()
    {
        static::deleted(function($model){
            $model->payload->delete();
        });
    }

    public function polling()
    {
        return $this->belongsTo(\App\Polling::class,'poll_id');
    }

    public function like()
    {
        return $this->hasMany(\App\Shareable\Sharelikable\Polling::class,'poll_share_id');
    }

    public function isPollingReported()
    {
        return $this->isReported(request()->user()->profile->id, "polling", $this->poll_id, true, $this->id);
    }

    public function getMetaFor($profileId){
        $meta = [];
        $key = "meta:pollingShare:likes:" . $this->id;

        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);

        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'pollingShare' ,request()->user()->profile->id);

        $meta['commentCount'] = $this->comments()->count();

        $poll = \App\Polling::where('id',$this->poll_id)->whereNull('deleted_at')->first();
        if ($poll) {
            $meta['originalPostMeta'] = $poll->getMetaFor($profileId);//Because off android this response is changes 
                                                                      //from original_post_meta to originalPostMeta 
        }
        $meta['isReported'] =  $this->isPollingReported();
        return $meta;
    }

    public function getMetaForV2($profileId)
    {
        $meta = [];
        $key = "meta:pollingShare:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $poll = \App\Polling::where('id',$this->poll_id)->whereNull('deleted_at')->first();
        if ($poll) {
            $meta['originalPostMeta'] = $poll->getMetaFor($profileId);
        }
        $meta['isReported'] =  $this->isPollingReported();
        return $meta;
    }

    public function getMetaForV2Shared($profileId)
    {   
        return $this->getMetaForV2($profileId);
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
            'id' => $this->polling->id,
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
    public function getSeoTags() : array
    {
        $title = "TagTaste | Share Poll";

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
                )
            ),
        ];
        return $seo_tags;
    }

}
