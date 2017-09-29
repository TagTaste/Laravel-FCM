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
        $key = "meta:recipeShare:likes:" . $this->id;
    
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
            'content' => $this->name,
            'image' => null,
            'shared' => true
        ];
    }
}
