<?php

namespace App\Shareable;

class Recipe extends Share
{
    protected $with = ['recipe'];

    public static function boot()
    {
        static::deleted(function($model){
            $model->payload->delete();
        });
    }

    public function recipe()
    {
        return $this->belongsTo(\App\Recipe::class,'recipe_id');
    }

    public function like()
    {
        return $this->hasMany(\App\Shareable\Sharelikable\Recipe::class,'recipe_share_id');
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
