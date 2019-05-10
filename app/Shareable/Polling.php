<?php

namespace App\Shareable;

use App\PeopleLike;

class Polling extends Share
{
    protected $fillable = ['profile_id','poll_id','payload_id','privacy_id'];
    protected $visible = ['id','profile_id','created_at'];

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

    public function getMetaFor($profileId){
        $meta = [];
        $key = "meta:pollingShare:likes:" . $this->id;

        $meta['hasLiked'] = \Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = \Redis::sCard($key);

        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'pollingShare' ,request()->user()->profile->id);

        $meta['commentCount'] = $this->comments()->count();

        $poll = \App\Polling::where('id',$this->poll_id)->whereNull('deleted_at')->first();
        $meta['original_post_meta'] = $poll->getMetaFor($profileId);

        return $meta;
    }

//    public function getMetaForPublic(){
//        $meta = [];
//        $key = "meta:collaborateShare:likes:" . $this->id;
//
//        $meta['likeCount'] = \Redis::sCard($key);
//
//        $meta['commentCount'] = $this->comments()->count();
//
//        return $meta;
//    }

//    public function getNotificationContent()
//    {
//        return [
//            'name' => strtolower(class_basename(self::class)),
//            'id' => $this->collaborate->id,
//            'share_id' => $this->id,
//            'content' => null != $this->content ? $this->content : $this->collaborate->looking_for,
//            'image' => null,
//            'shared' => true
//        ];
//    }

}
