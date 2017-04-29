<?php

namespace App;

use App\Channel\Payload;
use App\Traits\PushesToChannel;
use App\Events\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use PushesToChannel;
    
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

    protected $appends = ['imageUrl','heroImageUrl','followingProfiles','followerProfiles','isTagged','name'];
    
    public static function boot()
    {
        parent::boot();
        
        self::created(function(Profile $profile){
            
            //create profile's feed channel
            //$feed = Channel::create(['name'=>"feed." . $profile->id,'profile_id'=>$profile->id]);
            
            //subscribe to own feed channel
            $profile->subscribe("feed." . $profile->id,$profile->id);
            
            //create profile's public channel
            //$public  = Channel::create(['name'=>"public." . $profile->id,'profile_id'=>$profile->id]);
            //subscribe to own public channel
            $profile->subscribe("public." . $profile->id,$profile->id);
    
            // anything below this condition would not be executed
            // for the admin user.
            if($profile->id === 1){
                return;
            }
            //create the document for searching
            \App\Documents\Profile::create($profile);
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
    
    /**
     * Get people I am following.
     *
     * @return array
     */
    public function getFollowingProfilesAttribute()
    {
        //if you use \App\Profile here, it would end up nesting a lot of things.
        $profiles = \DB::table('subscribers')
            ->select('profiles.id','users.name','tagline','subscribers.channel_name')
            ->join('channels','subscribers.channel_name','=','channels.name')
            ->join('profiles','profiles.id','=','channels.profile_id')
            ->join('users','users.id','=','profiles.user_id')
            ->where('subscribers.profile_id','=',$this->id)
            ->where('subscribers.channel_name','not like','feed.' . $this->id)
            ->where('subscribers.channel_name','not like','network.' . $this->id)
            ->where('subscribers.channel_name','not like','public.' . $this->id)
            ->whereNull('subscribers.deleted_at')
            ->whereNull('profiles.deleted_at')
            ->whereNull('users.deleted_at')
            ->get();
//        $profiles = \DB::table('profiles')
//            ->select('profiles.id','users.name','tagline','subscribers.channel_name')
//            ->join('users','users.id','=','profiles.user_id')
//            ->join('subscribers','subscribers.profile_id','=','profiles.id')
//            ->where('subscribers.profile_id','=',$this->id)
//            ->where('subscribers.channel_name','not like','feed.' . $this->id)
//            ->where('subscribers.channel_name','not like','network.' . $this->id)
//            ->where('subscribers.channel_name','not like','public.' . $this->id)
//            ->get();
        
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
    
    /**
     * Get people following me.
     *
     * @return array
     */
    public function getFollowerProfilesAttribute()
    {
        //if you use \App\Profile here, it would end up nesting a lot of things.
        $profiles = \DB::table('profiles')
            ->select('profiles.id','users.name','tagline')
            ->join('subscribers','subscribers.profile_id','=','profiles.id')
            ->join('users','users.id','=','profiles.user_id')
            ->where('subscribers.channel_name','like','network.' . $this->id)
            ->where('subscribers.profile_id','!=',$this->id)
            ->whereNull('profiles.deleted_at')
            ->whereNull('subscribers.deleted_at')
            ->whereNull('users.deleted_at')
            ->get();
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
        $this->subscribe("network." . $owner->id,$owner->id);
        return $this->subscribe("public." . $owner->id,$owner->id);
    }
    
    public function subscribe($channelName, $ownerId)
    {
        $channel = Channel::where('profile_id',$ownerId)->where('name','like',$channelName)->first();
        
        if($channel === null){
            $channel = Channel::create(['name'=>$channelName,'profile_id'=>$ownerId]);
        }
        
        return $channel->subscribe($this->id);
    }
    
    public function unsubscribeNetworkOf(Profile $owner)
    {
        $this->unsubscribe("network." . $owner->id,$owner->id);
        return $this->unsubscribe("public." . $owner->id,$owner->id);
    }
    
    public function unsubscribe($channelName, $ownerId)
    {
        $channel = Channel::where('profile_id',$ownerId)->where('name','like',$channelName)->first();
        
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
    
    
    /**
     * Feed for the logged in user's profile
     *
     * @return mixed
     */
    public function feed()
    {
        $profileId = $this->id;
        return Payload::select('payload')->whereHas('channel',function($query) use ($profileId) {
            $query->where('channels.profile_id',$profileId)
            ->where('channels.name','not like','network.' . $profileId)
            ->where('channels.name','not like','public.' . $profileId);
        })->orderBy('created_at','desc')->get();
    }
    
    /**
     * Feed which a user would see if he visits his /profile page.
     */
    public function profileFeed()
    {
        $profileId = $this->id;
        return Payload::select('payload')->whereHas('channel',function($query) use ($profileId) {
            $query->where('channel_name','profile.' . $profileId);
        })->orderBy('created_at','desc')->get();
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
        })->orderBy('created_at','desc')->get();
    }
    
    public function shoutouts()
    {
        return $this->hasMany(Shoutout::class);
    }

}
