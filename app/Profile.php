<?php

namespace App;

use App\Channel\Payload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    protected $fillable = [
                            'tagline',
                            'about',
                            'image',
                            'hero_image',
                            'phone', 'country_code',
                            'address',
                            'dob',
                            'interests',
                            'website_url',
                            'blog_url',
                            'facebook_url',
                            'linkedin_url',
                            'instagram_link',
                            'pinterest_url',
                            'other_links',
                            'ingredients',
                            'favourite_moments',
                            'verified',
                            'youtube_channel',
                            'followers',
                            'following',
                            'user_id',
                            'created_at',
                            'pincode'
                          ];

    //if you add a relation here, make sure you remove it from
    //App\Recommend to prevent any unwanted results like nested looping.
    protected $with = [
                        'experience',
                        'awards',
                        'certifications',
                        'tvshows',
                        'books',
                        //'albums',
                        'projects',
                        'professional'
                      ];

    protected $visible = [
                          'id',
                          'tagline',
                          'about',
                          'phone', 'country_code',
                          'address',
                          'dob',
                          'interests',
                          'imageUrl',
                          'heroImageUrl',
                          'website_url',
                          'blog_url',
                          'facebook_url',
                          'linkedin_url',
                          'instagram_link',
                            'pinterest_url',
                            'other_links',
                          'ingredients',
                          'favourite_moments',
                          'verified',
                          'youtube_channel',
                          'interested_in_opportunities',
                          'followers',
                          'following',
                          'experience',
                          'awards',
                          'certifications',
                          'tvshows',
                          'books',
                          'followingProfiles',
                          'followerProfiles',
                          'name',
                          'photos',
                          'projects',
                          'professional',
                          'created_at',
                          'pincode',
                            'isTagged'
                        ];

    protected $appends = ['imageUrl','heroImageUrl','followingProfiles','followerProfiles','isTagged'];
    
    public static function boot()
    {
        parent::boot();
        
        self::created(function(Profile $profile){
            Channel::create(['name'=>"feed." . $profile->id,'profile_id'=>$profile->id]);
        });
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    public function getNameAttribute()
    {
        return $this->user->name;
    }

    public function setDobAttribute($value)
    {
        if(!empty($value)){
            $this->attributes['dob'] = date("Y-m-d",strtotime($value));
        }
    }

    public function getDobAttribute($value)
    {
        if(!empty($value)){
            return date("d-m-Y",strtotime($value));
        }
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
    
    public function recipes()
    {
        return $this->hasMany(\App\Recipe::class);
    }

    //specific to API
    public function getImageUrlAttribute()
    {
        if($this->image){
            return "/profile/images/" . $this->id . '.jpg';
        }

    }

    //specific to API
    public function getHeroImageUrlAttribute()
    {
        if($this->hero_image){
            return "/profile/hero/" . $this->id . '.jpg';
        }
    }

    //$followsId is following $this profile
    public function follow($followsId)
    {
        $this->iAmFollowing()->attach($followsId);
        $this->save();
        \DB::table('profiles')->whereId($this->id)->increment('following');
        \DB::table('profiles')->whereId($followsId)->increment('followers');
    }

    public function unfollow($followsId)
    {
        $this->iAmFollowing()->detach($followsId);
        $this->save();
        \DB::table('profiles')->whereId($this->id)->decrement('following');
        \DB::table('profiles')->whereId($followsId)->decrement('followers');
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

            $count = $profiles->count();
            if($count > 1000000)
            {
                 $count = round($count/1000000, 1) . "m";
            }
            elseif($count > 1000)
            {
                $count = round($count/1000, 1) . "k";
            }

        return ['count'=> $count, 'profiles' => $profiles];

    }

    public function getFollowerProfilesAttribute()
    {
        //if you use \App\Profile here, it would end up nesting a lot of things.

        $profiles = \DB::table('profiles')->select('profiles.id','users.name','tagline')
            ->join('followers','followers.follower_id','=','profiles.id')
            ->join('users','users.id','=','followers.follower_id')
            ->where('followers.follows_id','=',$this->id)->get();

             $count = $profiles->count();
            if($count > 1000000)
            {
                 $count = round($count/1000000, 1);
                $count = $count."M";
            }
            elseif($count > 1000)
            {
               
                $count = round($count/1000, 1);
                $count = $count."K";
            }

        return ['count'=> $count, 'profiles' => $profiles];

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

    //there should be a better way to write the paths.
    public static function getImagePath($id, $filename = null)
    {
        $relativePath = "profile/{$id}/images";
        Storage::makeDirectory($relativePath);
        return $filename === null ? $relativePath : storage_path("app/" . $relativePath) . "/" . $filename;
    }

    //there should be a better way to write the paths.
    public static function getHeroImagePath($id, $filename = null)
    {
        $relativePath = "profile/{$id}/hero_images";
        Storage::makeDirectory($relativePath);
        return $filename === null ? $relativePath : storage_path("app/" . $relativePath ) . "/" . $filename;
    }

    public function professional()
    {
        return $this->hasOne('\App\Professional');
    }
    
    /**
     * Just the pivot relationship. Ideabook of a user is defined in \App\User
     */
    
    public function ideabooks()
    {
        return $this->belongsToMany('\App\Ideabook','ideabook_profiles','profile_id','ideabook_id');
    }
    
    public function getIsTaggedAttribute()
    {
        return $this->ideabooks->count() === 1;
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
    
    /**
     * Subscribe the owner's network
     * @param Profile $owner
     * @return mixed
     */
    public function subscribeNetworkOf(Profile $owner)
    {
        return $this->subscribe("network." . $owner->id,$owner->id);
    }
    
    public function subscribe($channelName, $ownerId)
    {
        $channel = $this->channels->where('name','like',$channelName)->first();
        
        if(!$channel){
            $channel = Channel::create(['name'=>$channelName,'profile_id'=>$ownerId]);
        }
        
        return $channel->subscribe($this->id);
    }
    
    public function unsubscribeNetworkOf(Profile $owner)
    {
        return $this->unsubscribe("network." . $owner->id,$owner->id);
    }
    
    public function unsubscribe($channelName)
    {
        $channel = $this->channels->where('name','like',$channelName)->first();
        
        if(!$channel){
            throw new ModelNotFoundException();
        }
        
        return $channel->unsubscribe($this->id);
    }
    
    public function addSubscriber(Profile $profile)
    {
        $channelName = 'network.' . $this->id;
        $channel = $this->channels()->where('name','like',$channelName)->first();
        
        if(!$channel){
            //create channel
            $channel = $this->channels()->create(['name'=>$channelName]);
        }
        return $channel->subscribe($profile->id);
    }
    
    public function pushToMyFeed(&$data)
    {
        //push to my feed
        $this->pushToChannel("feed." . $this->id,$data);
        
        //push to my channel
        return $this->pushToNetwork($data);
    }
    public function pushToNetwork(&$data)
    {
        return $this->pushToChannel("network." . $this->id,$data);
    }
    
    public function pushToChannel($channelName,&$data)
    {
        $channel = $this->channels()->where('name',$channelName)->first();
        
        if(!$channel){
            //since a user can post even if he has no network (i.e. no followers)
            //throwing an exception here might cause some problem.
            //Throw an error if you feel like. Make sure it doesn't break anything.
            return false;
        }
        
        return $channel->addPayload($data);
        
    }
    
    /**
     * Feed for the logged in user's profile
     *
     * @return mixed
     */
    public function feed()
    {
        $profileId = $this->id;
        return Payload::select('payload')->whereHas('channel',function($query) use ($profileId) {
            $query->where('channels.profile_id',$profileId);
        })->get();
    }
    
    /**
     * Feed which a user would see if he visits his /profile page.
     */
    public function profileFeed()
    {
        $profileId = $this->id;
        return Payload::select('payload')->whereHas('channel',function($query) use ($profileId) {
            $query->where('channel_name','profile.' . $profileId);
        })->get();
    }
    
    /**
     * Feed of the subscribed network of the user.
     *
     * @return mixed
     */
    public function subscribedNetworksFeed()
    {
        $profileId = $this->id;
        $channels = Channel::select('name')->whereHas("subscribers", function ($query) use ($profileId) {
            $query->where('profile_id', $profileId)->where('name', 'like', 'network.%')
                ->where('name', 'not like', 'network.' . $profileId);
        })->get()->toArray();
        
        return Payload::select('payload')->whereHas('channel', function ($query) use ($channels) {
            $query->whereIn('channel_name', $channels);
        })->get();
    }

}
