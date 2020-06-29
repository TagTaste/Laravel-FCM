<?php

namespace App\Shareable;

use App\PeopleLike;
use Illuminate\Support\Facades\Redis;

class Shoutout extends Share
{
    protected $with = ['shoutout'];
    
    public static function boot()
    {
        static::deleted(function($model){
            $model->payload->delete();
        });
    }
    
    public function shoutout()
    {
        return $this->belongsTo(\App\Shoutout::class,'shoutout_id');
    }

    public function like()
    {
        return $this->hasMany(\App\Shareable\Sharelikable\Shoutout::class,'shoutout_share_id');
    }

    public function isShoutoutReported()
    {
        return $this->isReported(request()->user()->profile->id, "shoutout", $this->shoutout_id, true, $this->id);
    }

    public function getMetaFor($profileId){
        $meta = [];
        $key = "meta:shoutoutShare:likes:" . $this->id;
    
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);

        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'shoutoutShare' ,request()->user()->profile->id);

        $meta['commentCount'] = $this->comments()->count();
        $shoutout = \App\Shoutout::where('id',$this->shoutout_id)->whereNull('deleted_at')->first();
        $meta['original_post_meta'] = $shoutout->getMetaFor($profileId);
        $meta['isReported'] =  $this->isShoutoutReported();
        return $meta;
    }

    public function getMetaForV2($profileId)
    {
        $meta = [];
        $key = "meta:shoutoutShare:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['isReported'] =  $this->isShoutoutReported();
        return $meta;
    }

    public function getMetaForV2Shared($profileId)
    {
        $meta = [];
        $key = "meta:shoutoutShare:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $shoutout = \App\Shoutout::where('id',$this->shoutout_id)->whereNull('deleted_at')->first();
        $meta['originalPostMeta'] = $shoutout->getMetaForV2($profileId);
        $meta['isReported'] =  $this->isShoutoutReported();
        return $meta;
    }

    public function getMetaForPublic() {
        $meta = [];
        $key = "meta:shoutoutShare:likes:" . $this->id;

        $meta['likeCount'] = Redis::sCard($key);

        $meta['commentCount'] = $this->comments()->count();

        $shoutout = \App\Shoutout::where('id',$this->shoutout_id)->whereNull('deleted_at')->first();
        //$meta['original_post_meta'] = $shoutout->getMetaFor(request()->user()->profile->id);

        return $meta;
    }
    
    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->shoutout->id,
            'share_id' => $this->id,
            'content' => null != $this->content ? $this->content : $this->shoutout->content,
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
        $title = "TagTaste | Share Post";
        $description = "";

        $seo_tags = [
            "title" => $title,
            "meta" => array(
                array(
                    "name" => "description",
                    "content" => $description,
                ),
                array(
                    "name" => "keywords",
                    "content" => "",
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
