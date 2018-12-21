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
        'keywords','image','isFollowing','ageRange','gender',"image_meta","hero_image_meta"];

    protected $appends = ['name','designation','imageUrl','ageRange'];
    
    public function getDesignationAttribute()
    {
       return $this->professional !== null ? $this->professional->designation : null;
    }

    public function getAgeRangeAttribute()
    {
        $age = $this->getDobAttribute($this->dob);
        if(isset($age) && !is_null($age)) {
            $ageGroup = ['< 18', '18 - 35', '35 - 55', '55 - 70', '> 70'];
            $to = (int)$diff = (date('Y') - date('Y', strtotime($age)));
            switch ($to) {
                case $to <= 18:
                    return $ageGroup[0];
                    break;
                case $to > 18 && $to <= 35:
                    return $ageGroup[1];
                    break;
                case $to > 35 && $to <= 55:
                    return $ageGroup[2];
                    break;
                case $to > 55 && $to <= 70:
                    return $ageGroup[3];
                    break;
                case $to > 70:
                    return $ageGroup[4];
                    break;
                default:
                    return null;
            }
        }
        return null;
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
