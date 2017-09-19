<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Model
{
    use SoftDeletes;
    protected $fillable = ['channel_name', 'profile_id','timestamp'];
    
    public static function boot()
    {
        parent::boot();
        
        self::created(function($model){
            \Redis::sAdd("subscribers:" . $model->channel_name, $model->profile_id);
        });
        
        self::restored(function($model){
            \Redis::sAdd("subscribers:" . $model->channel_name, $model->profile_id);
        });
        
        self::deleting(function($model){
            \Redis::sRem("subscribers:" . $model->channel_name, $model->profile_id);
        });
    }
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class,'profile_id');
    }
    
    public function channel()
    {
        return $this->belongsTo(Channel::class,'channel_name','name');
    }
    
    public static function getFollowers($channelName)
    {
        $profileIds = \Redis::sMembers("subscribers:".$channelName);
        return \App\Recipe\Profile::whereIn('id',$profileIds)->get();
    }
    
    public static function count($channelName)
    {
        return \Redis::sCard("subscribers:" . $channelName);
    }
}
