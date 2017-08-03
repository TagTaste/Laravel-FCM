<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhotoLike extends Model
{
    protected $fillable = ['photo_id', 'profile_id'];
    
    public function photo()
    {
        return $this->belongsToMany('App\Photo', 'photo_id');
    }
    
    public static function boot()
    {
        self::created(function($model){
            \Redis::hIncrBy("photo:" . $model->photo_id . ":meta", "like", 1);
        });
        
        self::deleting(function($model){
            \Redis::hIncrBy("photo:" . $model->photo_id . ":meta", "like", -1);
        });
    }
    
    public function getLikeCountAttribute()
    {
        return \Redis::hget("photo:" . $this->photo_id . ":meta", "like");
    }
    
}
