<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['tagline','about','image',
        'hero_image','phone','address','dob','interests',
    'website_url','blog_url','facebook_url','linkedin_url','instagram_link',
    'youtube_channel','followers','following','user_id'];

    //if you add a relation here, make sure you remove it from
    //App\Recommend to prevent any unwanted results like nested looping.
    protected $with = ['experience','awards','certifications','tvshows','books','albums','projects'];

    protected $visible = ['id','tagline','about','phone','address','dob','interests', 'imageUrl','heroImageUrl',
        'website_url','blog_url','facebook_url','linkedin_url','instagram_link',
        'youtube_channel',
        'followers','following',
        'experience','awards','certifications','tvshows','books','followingProfiles', 'followerProfiles',
        'name','albums','projects'];

    protected $appends = ['imageUrl','heroImageUrl','followingProfiles','followerProfiles'];

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

    public function tvshows()
    {
        return $this->hasMany('App\Profile\Show');
    }

    public function books()
    {
        return $this->hasMany('App\Profile\Book');
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

    //$followsId is following $this profile
    public function follow($followsId)
    {
        $this->iAmFollowing()->attach($followsId);
        $this->save();
    }

    public function unfollow($followsId)
    {
        $this->iAmFollowing()->detach($followsId);
        $this->save();
    }

    //profiles which are following this profile
    public function myFollowers()
    {
        return $this->belongsToMany('App\Profile', 'followers', 'follows_id', 'follower_id');
    }

    //profiles which this profile is following
    public function iAmFollowing()
    {
        return $this->belongsToMany('App\Profile','followers','follower_id','follows_id');

    }

    public function getFollowingProfilesAttribute()
    {
        //if you use \App\Profile here, it would end up nesting a lot of things.

        $profiles = \DB::table('profiles')->select('profiles.id','users.name','tagline')
            ->join('followers','followers.follows_id','=','profiles.id')
            ->join('users','users.id','=','followers.follows_id')
            ->where('followers.follower_id','=',$this->id)->get();

        return ['count'=> $profiles->count(), 'profiles' => $profiles];

    }

    public function getFollowerProfilesAttribute()
    {
        //if you use \App\Profile here, it would end up nesting a lot of things.

        $profiles = \DB::table('profiles')->select('profiles.id','users.name','tagline')
            ->join('followers','followers.follower_id','=','profiles.id')
            ->join('users','users.id','=','followers.follower_id')
            ->where('followers.follows_id','=',$this->id)->get();

        return ['count'=> $profiles->count(), 'profiles' => $profiles];

    }

    public function albums()
    {
        return $this->hasMany('App\Album');
    }

    public function photos()
    {
        return $this->hasManyThrough('App\Photo','App\Album');
    }

    public function projects()
    {
        return $this->hasMany('App\Projects');
    }


}
