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

        return $meta;
    }

    public function getMetaForV2($profileId)
    {
        $meta = [];
        $key = "meta:shoutoutShare:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        return $meta;
    }

    public function getMetaForPublic(){
        $meta = [];
        $key = "meta:photoShare:likes:" . $this->id;

        $meta['likeCount'] = \Redis::sCard($key);

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
    
}
