<?php


namespace App\Scopes;


trait Profile
{
    public function scopeForProfile($query,$profileId)
    {
        return $query->whereHas('profile',function($query) use ($profileId){
            $query->where('profile_id',$profileId);
        });
    }
}