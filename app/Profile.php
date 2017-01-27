<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['tagline','about','image',
        'hero_image','phone','address','dob','interests',
    'website_url','blog_url','facebook_url','linkedin_url','instagram_link',
    'youtube_channel','followers','following','user_id'];

    protected $with = ['experience','awards','certifications'];

    protected $visible = ['tagline','about','phone','address','dob','interests', 'imageUrl','heroImageUrl',
        'website_url','blog_url','facebook_url','linkedin_url','instagram_link',
        'youtube_channel','followers','following','experience','awards','certifications','name'];

    protected $appends = ['imageUrl','heroImageUrl'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = date("Y-m-d",strtotime($value));
    }

    public function getDobAttribute($value)
    {
        if(!$value){
            return;
        }
        return date("d-m-Y",strtotime($value));
    }

    public function experience()
    {
        return $this->hasMany('App\Profile\Experience');
    }

    public function awards()
    {
        return $this->hasMany('App\Profile\Award');
    }

    public function certifications()
    {
        return $this->hasMany('App\Profile\Certification');
    }

    //specific to API
    public function getImageUrlAttribute()
    {
        return "/profile/images/" . $this->id . '.jpg';
    }

    //specific to API
    public function getHeroImageUrlAttribute()
    {
        return "/profile/hero/" . $this->id . '.jpg';

    }


}
