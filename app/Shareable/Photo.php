<?php

namespace App\Shareable;

use App\PeopleLike;
use App\Shareable\Share;
use Illuminate\Support\Facades\Redis;

class Photo extends Share
{

    protected $with = ['photo'];

    public static function boot()
    {
        static::deleted(function($model){
            $model->payload->delete();
        });
    }

    public function photo()
    {
        return $this->belongsTo(\App\Photo::class,'photo_id');
    }

    public function like()
    {
        return $this->hasMany(\App\Shareable\Sharelikable\Photo::class,'photo_share_id');
    }

    public function isPhotoReported()
    {
        return $this->isReported(request()->user()->profile->id, "photo", (string)$this->photo_id, true, $this->id);
    }

    public function getMetaFor($profileId){
        $meta = [];
        $key = "meta:photoShare:likes:" . $this->id;
    
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);

        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'photoShare' ,request()->user()->profile->id);

        $meta['commentCount'] = $this->comments()->count();
        $photo = \App\Photo::where('id',$this->photo_id)->whereNull('deleted_at')->first();
        $meta['original_post_meta'] = $photo->getMetaFor($profileId);
        $meta['isReported'] =  $this->isPhotoReported();
        return $meta;
    }

    public function getMetaForV2($profileId)
    {
        $meta = [];
        $key = "meta:photoShare:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['isReported'] =  $this->isPhotoReported();
        return $meta;
    }

    public function getMetaForV2Shared($profileId)
    {
        $meta = [];
        $key = "meta:photoShare:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $photo = \App\Photo::where('id',$this->photo_id)->whereNull('deleted_at')->first();
        $meta['originalPostMeta'] = $photo->getMetaForV2($profileId);
        $meta['isReported'] =  $this->isPhotoReported();
        return $meta;
    }

    public function getMetaForPublic(){
        $meta = [];
        $key = "meta:photoShare:likes:" . $this->id;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        return $meta;
    }
    
    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->photo->id,
            'share_id' => $this->id,
            'content' => null != $this->content ? $this->content : $this->photo->caption,
            'image' => $this->photo->photoUrl,
            'shared' => true
        ];
    }

    /**
     * @param int $profileId
     * @return array
     */
    public function getSeoTags() : array
    {
        $title = "TagTaste | Share Photo";

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
                    "content" => $description
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
