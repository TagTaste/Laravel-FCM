<?php

namespace App\PublicView;

use App\Job;
use App\Profile as BaseProfile;
use App\Shoutout;
use App\Subscriber;

class Profile extends BaseProfile
{
    protected $fillable = [];

    protected $with = [];

    protected $visible = ['id','name', 'designation','imageUrl','tagline','about','handle','city','expertise',
        'keywords','image','isFollowing'];

    protected $appends = ['name','imageUrl'];

    public function recipes()
    {
        return $this->hasMany(\App\Recipe::class);
    }

    public function photos()
    {
        return $this->belongsToMany('App\PublicView\Photos','profile_photos','profile_id','photo_id');
    }

    public function collaborate()
    {
        return $this->hasMany(\App\Collaborate::class);
    }

    public function subscribers()
    {
        return $this->hasMany(Subscriber::class);
    }

    public function shoutouts()
    {
        return $this->hasMany(Shoutout::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
