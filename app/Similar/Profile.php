<?php

namespace App\Similar;

use App\Profile as BaseProfile;

class Profile extends BaseProfile
{
    //protected $with = [];
    
    //protected $visible = ['id','name','imageUrl','tagline','followingProfiles','followerProfiles','handle'];
    
    public function similar($skip,$take)
    {
        /*
            select distinct profiles.id from profiles
            where profiles.id not in (select distinct channels.profile_id from channels
            join subscribers on subscribers.channel_name = channels.name
            where subscribers.channel_name like 'network.6' or subscribers.profile_id = 6)
        */
        $distinctProfiles = \DB::table('profiles')->selectRaw(\DB::raw('distinct profiles.id'))
            ->whereRaw(
                \DB::raw(
                    'profiles.id not in (select distinct channels.profile_id from channels join subscribers on subscribers.channel_name = channels.name where subscribers.channel_name like \'network.' . $this->id .'\' or subscribers.profile_id = ' . $this->id . ')'
                )
            )
            ->get();
        
        if($distinctProfiles->count()){
            $dist = $distinctProfiles->pluck('id')->toArray();
            return self::whereIn('id',$dist)->where('id','!=',$this->id)->whereNull('deleted_at')->skip($skip)
                ->take($take)
                ->get();
        }
        return false;
    }
    
}
