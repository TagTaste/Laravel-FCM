<?php

namespace App\Similar;

use App\Profile as BaseProfile;

class Profile extends BaseProfile
{
    protected $with = [];
    
    protected $visible = ['id','name','imageUrl','tagline','followingProfiles','followerProfiles'];
    
    public function similar()
    {
        return self::join('subscribers','subscribers.profile_id','=','profiles.id')
            ->where('subscribers.profile_id','!=',$this->id)
            ->where('subscribers.channel_name','like','public.%')
            ->paginate(5);
    }
    
}
