<?php

namespace App\Shareable;

use App\PeopleLike;

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

    public function getMetaFor($profileId){
        $meta = [];
        $key = "meta:shoutoutShare:likes:" . $this->id;
    
        $meta['hasLiked'] = \Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = \Redis::sCard($key);

        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'shoutoutShare' ,request()->user()->proflie->id);

        $meta['commentCount'] = $this->comments()->count();

        return $meta;
    }
    
    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'content' => $this->content,
            'image' => null,
            'shared' => true
        ];
    }

}
