<?php

namespace App\Ideabook;

use App\Profile as BaseProfile;

class Profile extends BaseProfile
{
    protected $fillable = [];
    
    //if you add a relation here, make sure you remove it from
    //App\Recommend to prevent any unwanted results like nested looping.
    protected $with = [];
    
    protected $visible = [
        'id',
        'tagline',
        'about',
        'imageUrl',
        'heroImageUrl',
        'followers',
        'following',
        'followingProfiles',
        'followerProfiles',
        'name',
    ];
    
    protected $appends = ['imageUrl','heroImageUrl','followingProfiles','followerProfiles','name'];
}
