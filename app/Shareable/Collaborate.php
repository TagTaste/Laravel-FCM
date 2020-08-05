<?php

namespace App\Shareable;

use App\PeopleLike;
use Illuminate\Support\Facades\Redis;

class Collaborate extends Share
{
    protected $fillable = ['profile_id','collaborate_id','payload_id','privacy_id','content'];
    protected $visible = ['id','profile_id','created_at','content'];

    protected $with = ['collaborate'];

    public static function boot()
    {
        static::deleted(function($model){
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
                    "content" => null,
                )
            ),
        ];
        return $seo_tags;
    }

}
