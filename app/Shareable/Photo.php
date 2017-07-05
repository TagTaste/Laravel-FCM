<?php

namespace App\Shareable;

use App\Shareable\Share;



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
        $meta['hasLiked'] = $this->like()->where('profile_id',$profileId)->first() !== null;
        $meta['likeCount'] = $this->like->count();

        $idLiked = $this->like()->select('profile_id')->take(3)->get();
        $meta['peopleLiked'] = \App\User::whereIn('id',$idLiked)->select('name')->get();

        $meta['commentCount'] = $this->comments()->count();

        return $meta;
    }
    
}
