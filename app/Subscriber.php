<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Model
{
    use SoftDeletes;
    protected $fillable = ['channel_name', 'profile_id','timestamp'];
    
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class,'profile_id');
    }
    
    public function channel()
    {
        return $this->belongsTo(Channel::class,'channel_name','name');
    }
    
    public static function getFollowers($profileId)
    {
        $profileIds = \Redis::sMembers("followers:$profileId");
        $keys = [];
        foreach($profileIds as $id){
            $keys[] = "profile:small:" . $id;
        }
        return \Redis::mget($keys);
    }
    
    public static function countFollowers($profileId)
    {
        return \Redis::sCard("followers:" . $profileId);
    }
    
    public static function countFollowing($profileId)
    {
        return \Redis::sCard("following:" . $profileId);
    }
    
    public static function getFollowing($profileId)
    {
        $profileIds = \Redis::sMembers("following:$profileId");
        $keys = [];
        foreach($profileIds as $id){
            $keys[] = "profile:small:" . $id;
        }
        return \Redis::mget($keys);
    }
}
