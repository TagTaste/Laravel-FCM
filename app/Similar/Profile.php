<?php

namespace App\Similar;

use App\Profile as BaseProfile;

class Profile extends BaseProfile
{
    protected $with = [];
    
    protected $visible = ['id','name','imageUrl','tagline','followingProfiles','followerProfiles'];
    
    public function similar()
    {
        return self::join('channels','channels.profile_id','=','profiles.id')
            ->join('subscribers','subscribers.channel_name','=','channels.name')
            ->where('profiles.id','!=',$this->id)
            ->where('subscribers.profile_id','!=',$this->id)
            ->paginate(5);
    }
    
}
