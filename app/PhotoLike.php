<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class PhotoLike extends Model
{
    protected $fillable = ['photo_id', 'profile_id'];
    
    public function photo()
    {
        return $this->belongsToMany('App\Photo', 'photo_id');
    }
    
    public static function boot()
    {
    
    }
    
    public function getLikeCountAttribute()
    {
        return Redis::sCard( "meta:photo:likes:" . $this->photo_id);
    }
    
}
