<?php

namespace App\Shareable;

use App\PeopleLike;
use Illuminate\Support\Facades\Redis;
use App\Traits\HashtagFactory;

class Shoutout extends Share
{
    use HashtagFactory;
    protected $with = ['shoutout'];
    
    public static function boot()
    {
        static::created(function($model){
            $matches = $model->hasHashtags($model);
            if(count($matches)) {
                $model->createHashtag($matches,'App\Shareable\Shoutout',$model->id);
            }
        });
        static::updated(function($model){
            $model->deleteExistingHashtag('App\Shareable\Shoutout',$model->id);
            $matches = $model->hasHashtags($model);
            if(count($matches)) {
                $model->createHashtag($matches,'App\Shareable\Shoutout',$model->id);
            }
        });
        static::deleted(function($model){
            $matches = $model->hasHashtags($model);
            if(count($matches)) {
                $model->deleteExistingHashtag('App\Shareable\Shoutout',$model->id);
            }
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
