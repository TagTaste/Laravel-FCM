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

    public function getMetaFor($profileId){
        $meta = [];
        $key = "meta:collaborateShare:likes:" . $this->id;
    
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);

        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'collaborateShare' ,request()->user()->profile->id);

        $meta['commentCount'] = $this->comments()->count();
        $collaborate = \App\Collaborate::where('id',$this->collaborate_id)->whereNull('deleted_at')->first();
        $meta['original_post_meta'] = $collaborate->getMetaFor($profileId);

        return $meta;
    }

    public function getMetaForV2($profileId) {
        $meta = [];
        $key = "meta:collaborateShare:likes:" . $this->id;
        $meta['has_liked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['like_count'] = Redis::sCard($key);
        $meta['comment_count'] = $this->comments()->count();
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

}
