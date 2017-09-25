<?php

namespace App\Shareable;

class Collaborate extends Share
{
    protected $fillable = ['profile_id','collaborate_id','payload_id','privacy_id'];
    protected $visible = ['id','profile_id','created_at'];

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
    
        $meta['hasLiked'] = \Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = \Redis::sCard($key);

        $idLiked = $this->like()->select('profile_id')->take(3)->get();
        $meta['peopleLiked'] = \App\User::whereIn('id',$idLiked)->select('name')->get();

        $meta['commentCount'] = $this->comments()->count();

        return $meta;
    }
    
    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'content' => $this->title,
            'image' => null,
            'shared' => true
        ];
    }

}
