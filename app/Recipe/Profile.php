<?php

namespace App\Recipe;

use App\Channel;
use App\Job;
use App\Profile as BaseProfile;
use App\Shoutout;
use App\Subscriber;

class Profile extends BaseProfile
{
    protected $fillable = [];

    protected $with = [];

    protected $visible = ['id','name', 'designation','imageUrl','tagline','about','handle','city','expertise','user_id',
        'keywords','image','isFollowing'];

    protected $appends = ['name','designation','imageUrl'];
    
    public function getDesignationAttribute()
    {
       return $this->professional !== null ? $this->professional->designation : null;
    }
    
    public function experience()
    {
        return $this->hasMany('App\Profile\Experience');
    }
    
    public function awards()
    {
        return $this->belongsToMany('App\Profile\Award','profile_awards','profile_id','award_id');
    }
    
    public function certifications()
    {
        return $this->hasMany('App\Profile\Certification');
    }
    
    public function tvshows()
    {
        return $this->hasMany('App\Profile\Show');
    }
    
    public function books()
    {
        return $this->hasMany('App\Profile\Book');
    }
    
    public function patents()
    {
        return $this->hasMany('App\Profile\Patent');
    }
    
    public function recipes()
    {
        return $this->hasMany(\App\Recipe::class);
    }
    
    public function photos()
    {
        return $this->belongsToMany('App\Photo','profile_photos','profile_id','photo_id');
    }
    
    public function projects()
    {
        return $this->hasMany('App\Profile\Project');
    }
    
    public function education()
    {
        return $this->hasMany('App\Education');
    }
    
    public function companies()
    {
        return $this->hasManyThrough('\App\Company','App\User');
    }
    
    public function professional()
    {
        return $this->hasOne('\App\Professional');
    }
    
    public function ideabooks()
    {
        return $this->belongsToMany('\App\Ideabook','ideabook_profiles','profile_id','ideabook_id');
    }
    
    public function collaborate()
    {
        return $this->hasMany(\App\Collaborate::class);
    }
    
    public function channels()
    {
        return $this->hasMany(Channel::class);
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
