<?php

namespace App\Shareable;

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
        $meta['hasLiked'] = $this->like()->where('profile_id',$profileId)->first() !== null;
//        $meta['likeCount'] = \Redis::hget("shoutout_shares:" . $this->id . ":meta","like") ?: 0;

        $idLiked = $this->like()->select('profile_id')->take(3)->get();
        $meta['peopleLiked'] = \App\User::whereIn('id',$idLiked)->select('name')->get();

        $meta['commentCount'] = $this->comments()->count();

        return $meta;
    }

}
