<?php


namespace App\Scopes;


trait Profile
{
    public function scopeProfile($query,$profileId)
    {
        return $query->where('profile_id',$profileId);
    }
}