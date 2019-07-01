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

        return $meta;
    }

    public function getMetaForPublic() {
        $meta = [];
        $key = "meta:shoutoutShare:likes:" . $this->id;

        $meta['likeCount'] = Redis::sCard($key);

        $meta['commentCount'] = $this->comments()->count();

        $shoutout = \App\Shoutout::where('id',$this->shoutout_id)->whereNull('deleted_at')->first();
        $meta['original_post_meta'] = $shoutout->getMetaFor(request()->user()->profile->id);

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

}
