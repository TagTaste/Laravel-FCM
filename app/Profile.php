<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['name','tagline','about','image',
        'hero_image','phone','address','dob','interests','marital_status',
    'website_url','blog_url','facebook_url','linkedin_url','instagram_link',
    'youtube_channel','followers','following'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
