<?php

namespace App;

use App\Channel\Payload;
use App\Traits\PushesToChannel;
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
                            'pincode',
                            'handle',
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
                            'isTagged',
                            'handle',
                        ];

    protected $appends = ['imageUrl','heroImageUrl','followingProfiles','followerProfiles','isTagged','name'];
    
    public static function boot()
    {
        parent::boot();
        
        self::created(function(Profile $profile){
            
            //create profile's feed channel
            //$feed = Channel::create(['name'=>"feed." . $profile->id,'profile_id'=>$profile->id]);
            
            //subscribe to own feed channel
            $profile->subscribe("feed", $profile);
            
            //create profile's public channel
            //$public  = Channel::create(['name'=>"public." . $profile->id,'profile_id'=>$profile->id]);
            //subscribe to own public channel
            $profile->subscribe("public", $profile);
    
            // anything below this condition would not be executed
            // for the admin user.
            if($profile->id === 1){
                return;
            }
            //create the document for searching
            \App\Documents\Profile::create($profile);
            
            //bad call inside, would be fixed soon
            $profile->addToCache();
    
        });
        
        self::updated(function(Profile $profile){
           //bad call inside, would be fixed soon
           $profile->addToCache();
        });
    }
    
    public function addToCache()
    {
        $smallProfile = \App\Recipe\Profile::find($this->id);
        \Redis::set("profile:small:" . $this->id , $smallProfile->toJson());
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
        return $this->image !== null ? "/images/p/" . $this->id . "/" . $this->image : null;
    }

    //specific to API
    public function getHeroImageUrlAttribute()
    {
        return $this->hero_image !== null ? "/images/p/" . $this->id . "/hi/" . $this->hero_image : null;
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
    
    public static function getFollowing($id)
    {
        $channelOwnerProfileIds = \DB::table("subscribers")
            ->select('channels.profile_id')
            ->join('channels','subscribers.channel_name','=','channels.name')
            ->where('subscribers.profile_id','=',$id)
            ->where('subscribers.channel_name','like','network.%')
            ->where('subscribers.channel_name','not like','feed.' . $id)
            ->where('subscribers.channel_name','not like','network.' . $id)
            ->where('subscribers.channel_name','not like','public.' . $id)
            ->whereNull('subscribers.deleted_at')
            ->get();
        return \App\Recipe\Profile::whereIn("id",$channelOwnerProfileIds->pluck('profile_id')->toArray())->get();
    }
    public function getFollowingProfilesAttribute()
    {
        //if you use \App\Profile here, it would end up nesting a lot of things.
        $profiles = self::getFollowing($this->id);
        
            $count = $profiles->count();
            
            if($count > 1000000)
            {
                 $count = round($count/1000000, 1) . "m";
            }
            elseif($count > 1000)
            {
                $count = round($count/1000, 1) . "k";
            }

        return ['count'=> $count, 'profiles' => $profiles->toArray()];

    }
    
    public static function getFollowers($id)
    {
        //just get the profile ids first
        //then fire another query to build the required things
        
        $profileIds = \DB::table('profiles')->select('profiles.id')
            ->join('subscribers','subscribers.profile_id','=','profiles.id')
            ->where('subscribers.channel_name','like','network.' . $id)
            ->where('subscribers.profile_id','!=',$id)
            ->whereNull('profiles.deleted_at')
            ->whereNull('subscribers.deleted_at')
            ->get();
        
        return \App\Recipe\Profile::whereIn('id',$profileIds->pluck('id')->toArray())->get();
    }
    
    /**
     * Get people following me.
     *
     * @return array
     */
    public function getFollowerProfilesAttribute()
    {
        //if you use \App\Profile here, it would end up nesting a lot of things.
        $profiles = Profile::getFollowers($this->id);
        
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
        //$relativePath = "profile/{$id}/images";
        $relativePath = "images/p/{$id}";
        
        Storage::makeDirectory($relativePath);
        return $filename === null ? $relativePath : storage_path("app/" . $relativePath) . "/" . $filename;
    }

    //there should be a better way to write the paths.
    public static function getHeroImagePath($id, $filename = null)
    {
        $relativePath = "images/p/{$id}/hi";
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
    
    private function getChannel(&$channelName, &$owner, $createIfNotExist = true)
    {
        $prefix = $owner instanceof Company ? "company." : null;
        $whereClause = $owner instanceof Company ? "company_id" : "profile_id";
        $channelName = $prefix . $channelName . "." . $owner->id;
        
        $channel = Channel::where($whereClause,$owner->id)->where('name','like',$channelName)->first();
    
        if($channel === null){
            if(!$createIfNotExist) {
                throw new ModelNotFoundException("Channel not found.");
            }
    
            $channel = Channel::create(['name'=>$channelName,$whereClause=>$owner->id]);
        }
        
        return $channel;
    }
    /**
     * Subscribe the owner's network
     * @param Profile|Company $owner
     * @return mixed
     */
    public function subscribeNetworkOf(&$owner)
    {
        //only individual can publish things in network.
        if($owner instanceof Company === false){
            $this->subscribe("network",$owner);
        }
        
        return $this->subscribe("public",$owner);
    }
    
    public function subscribe($channelName, &$owner)
    {
        return $this->getChannel($channelName,$owner,true)->subscribe($this->id);
    }
    
    public function unsubscribeNetworkOf(&$owner)
    {
        $this->unsubscribe("network",$owner);
        return $this->unsubscribe("public",$owner);
    }
    
    public function unsubscribe($channelName, &$owner)
    {
        return $this->getChannel($channelName,$owner,false)->unsubscribe($this->id);
    }
    
    //todo: remove this method if not used.
    private function addSubscriber(Profile $owner)
    {
        $channelName = 'network.' . $this->id;
        $channel = $this->channels()->where('name','like',$channelName)->first();
        
        if(!$channel){
            //create channel
            $channel = $this->channels()->create(['name'=>$channelName]);
        }
        return $channel->subscribe($owner->id);
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
    
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
    
    public static function isFollowing($profileId, $followerProfileId)
    {
        return Subscriber::where('profile_id',$followerProfileId)->where("channel_name",'like','network.' . $profileId)->count() === 1;
    }

}
