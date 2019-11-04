<?php

namespace App\Shareable;

use App\PeopleLike;
use Illuminate\Support\Facades\Redis;

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
    
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);

        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'recipeShare' ,request()->user()->profile->id);

        $meta['commentCount'] = $this->comments()->count();

        return $meta;
    }

    public function getMetaForPublic(){
        $meta = [];
        $key = "meta:recipeShare:likes:" . $this->id;

        $meta['likeCount'] = Redis::sCard($key);

        $meta['commentCount'] = $this->comments()->count();

        return $meta;
    }
    
    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->recipe->id,
            'share_id' => $this->id,
            'content' => null != $this->content ? $this->content : $this->recipe->title,
            'image' => null,
            'shared' => true
        ];
    }
}
