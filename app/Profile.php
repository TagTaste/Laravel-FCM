<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['tagline','about','image',
        'hero_image','phone','address','dob','interests',
    'website_url','blog_url','facebook_url','linkedin_url','instagram_link',
    'youtube_channel','followers','following','user_id'];

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
}
