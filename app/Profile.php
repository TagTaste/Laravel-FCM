<?php

namespace App;

use App\Channel\Payload;
use App\Traits\PushesToChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;

class Profile extends Model
{
    use PushesToChannel,Notifiable, SoftDeletes;

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
        'twitter_url',
        'google_url',
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
        'expertise', //a.k.a spokenLanguages
        'keywords',
        'city',
        'country',
        'resume',
        'email_private',
        'address_private',
        'phone_private',
        'dob_private',
        'affiliations',
        'style_image',
        'style_hero_image',
        'otp',
        'verified_phone',
        'onboarding_step',
    ];

    //if you add a relation here, make sure you remove it from
    //App\Recommend to prevent any unwanted results like nested looping.
    protected $with = [
//        'experience',
        'awards',
        'certifications',
        'tvshows',
        'books',
        //'albums',
        'patents',
        'projects',
//        'education',
        'professional',
        'training'
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
        'google_url',
        'instagram_link',
        'pinterest_url',
        'other_links',
        'twitter_url',
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
        'patents',
        'followingProfiles',
        'followerProfiles',
        'mutualFollowers',
        'name',
        'photos',
        'education',
        'projects',
        'professional',
        'created_at',
        'pincode',
        'isTagged',
        'handle',
        'expertise',
        'keywords',
        'city',
        'country',
        'resumeUrl',
        'email_private',
        'address_private',
        'phone_private',
        'dob_private',
        'training',
        'affiliations',
        'style_image',
        'style_hero_image',
        'verified_phone',
        'notificationCount',
        'messageCount',
        'addPassword',
        'unreadNotificationCount',
        'onboarding_step',
        'remainingMessages'
    ];

    protected $appends = ['imageUrl', 'heroImageUrl', 'followingProfiles', 'followerProfiles', 'isTagged', 'name' ,
        'resumeUrl','experience','education','mutualFollowers','notificationCount','messageCount','addPassword','unreadNotificationCount',
            'remainingMessages'];

    public static function boot()
    {
        parent::boot();

        self::created(function (Profile $profile) {

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
            if ($profile->id === 1) {
                return;
            }
            //create the document for searching
            \App\Documents\Profile::create($profile);
            //bad call inside, would be fixed soon
            $profile->addToCache();

        });

        self::updated(function (Profile $profile) {
            //bad call inside, would be fixed soon
            $profile->addToCache();
    
            //this would delete the old document.
            \App\Documents\Profile::create($profile);
        });
        
        self::deleting(function($profile){
            \App\Filter\Profile::removeModel($profile->id);
            \App\Documents\Profile::delete($profile);
            $profile->removeFromCache();
        });
    }

    public function addToCache()
    {
        $smallProfile = \App\Recipe\Profile::find($this->id);
        \Redis::set("profile:small:" . $this->id, $smallProfile->toJson());
    }

    public static function getFromCache($id)
    {
        return \Redis::get('profile:small:' . $id);
    }
    
    public function removeFromCache()
    {
        return \Redis::del('profile:small:' . $this->id);
    }

    public static function getMultipleFromCache($ids = [])
    {
        $keyPreifx = "profile:small:";
        foreach ($ids as &$id) {
            $id = $keyPreifx . $id;
        }
        $profiles = \Redis::mget($ids);
        if (count(array_filter($profiles)) == 0) {
            return false;
        }
        foreach ($profiles as $index => &$profile) {
            $profile = json_decode($profile);
        }

        return $profiles;
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getNameAttribute()
    {
        try {
            return $this->user->name;
        } catch (\Exception $e){
            $message = "Accessing deleted profile " . $this->id;
            \Log::warning($message);
            $client =  new \GuzzleHttp\Client();
            $hook = env('SLACK_HOOK');
            if($hook){
                $client->request('POST', $hook,
                    [
                        'json' =>
                            [
                                "channel" => env('SLACK_CHANNEL'),
                                "username" => "ramukaka",
                                "icon_emoji" => ":older_man::skin-tone-3:",
                                "text" => $message]
                    ]);
                
            }
            
        }
        return "Inactive User";
    }

    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = empty($value) ? null : date("Y-m-d", strtotime($value));
    }

    public function getDobAttribute($value)
    {
        if (!empty($value)) {

            if(request()->user()->profile->id == $this->id)
            {
                return date("d-m-Y", strtotime($value));
            }

            if($this->dob_private == 3)
            {
                return null;
            }
            if(!\Redis::sIsMember("followers:profile:".request()->user()->profile->id,$this->id) && $this->dob_private == 2)
            {
                return null;
            }
            return date("d-m-Y", strtotime($value));
        }
    }
    
    public function getExperienceAttribute(){
        $experiences = $this->experience()->get();
        $dates = $experiences->toArray();

        $experiences = $experiences->keyBy('id');
        $sortedExperience = collect([]);
        $endDates = [];
        foreach ($dates as $exp) {
            $id = $exp['id'];
            
            if (is_null($exp['end_date']) || $exp['current_company'] === 1) {
                $sortedExperience->push($experiences->get($id));
                continue;
            }
            $dateArray = explode("-", $exp['end_date']);
            $temp = array_fill(0, 3 - count($dateArray), '01');
            $tempdate = implode("-", array_merge($temp, $dateArray));
            $endDates[] = ['id' => $id, 'date' => $tempdate, 'time' => strtotime($tempdate)];
        }
        
        
        $currentCompanies = $sortedExperience->pluck('start_date','id')->toArray();
        $startDates = [];

        foreach($currentCompanies as $id=>$startDate){
            
            $dateArray = explode("-", $startDate);
            $temp = array_fill(0, 3 - count($dateArray), '01');
            $tempdate = implode("-", array_merge($temp, $dateArray));
            $startDates[] = ['id' => $id, 'date' => $tempdate, 'time' => strtotime($tempdate)];
        }
        $startDates = collect($startDates)->sortByDesc('time')->keyBy('id')->toArray();
        $sortedExperience = collect([]);
        
        foreach($startDates as $id=>$date){
            
            $sortedExperience->push($experiences->get($id));
        }
        
        
        $sorted = collect($endDates)->sortByDesc('time')->keyBy('id')->toArray();
        unset($endDates);
        
        foreach($sorted as $id=>$date){
            $sortedExperience->push($experiences->get($id));
        }
        
        unset($experiences);
        return $sortedExperience;
        
    }

    public function getEducationAttribute(){
        
        $educations = $this->education()->get();
        
        $dates = $educations->toArray();

        $educations = $educations->keyBy('id');
        $sortedEducation = collect([]);
        $endDates = [];
        foreach ($dates as $exp) {
            $id = $exp['id'];

            if (is_null($exp['end_date']) || $exp['ongoing'] === 1) {
                $sortedEducation->push($educations->get($id));
                continue;
            }
            $dateArray = explode("-", $exp['end_date']);
            $temp = array_fill(0, 3 - count($dateArray), '01');
            $tempdate = implode("-", array_merge($temp, $dateArray));
            $endDates[] = ['id' => $id, 'date' => $tempdate, 'time' => strtotime($tempdate)];
        }


        $currentColleges = $sortedEducation->pluck('start_date','id')->toArray();
        $startDates = [];

        foreach($currentColleges as $id=>$startDate){

            $dateArray = explode("-", $startDate);
            $temp = array_fill(0, 3 - count($dateArray), '01');
            $tempdate = implode("-", array_merge($temp, $dateArray));
            $startDates[] = ['id' => $id, 'date' => $tempdate, 'time' => strtotime($tempdate)];
        }
        $startDates = collect($startDates)->sortByDesc('time')->keyBy('id')->toArray();
        $sortedEducation = collect([]);

        foreach($startDates as $id=>$date){

            $sortedEducation->push($educations->get($id));
        }


        $sorted = collect($endDates)->sortByDesc('time')->keyBy('id')->toArray();
        unset($endDates);

        foreach($sorted as $id=>$date){
            $sortedEducation->push($educations->get($id));
        }

        unset($educations);
        return $sortedEducation;

    }

    public function experience()
    {
        return $this->hasMany('App\Profile\Experience');
    }

    public function awards()
    {
        return $this->belongsToMany('App\Profile\Award', 'profile_awards', 'profile_id', 'award_id');
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

    //specific to API
    public function getImageUrlAttribute()
    {
        try {
            return !is_null($this->image) ? \Storage::url($this->image) : null;
        } catch (\Exception $e){
            \Log::warning("Could not get image for profile:" . $this->id);
        }
    }

    //specific to API
    public function getHeroImageUrlAttribute()
    {
        try {
            return !is_null($this->hero_image) ? \Storage::url($this->hero_image) : null;
        } catch (\Exception $e){
            \Log::warning("Could not get hero image for profile:" . $this->id);
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
        return $this->belongsToMany('App\Profile', 'followers', 'follower_id', 'follows_id');

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
            ->join('channels', 'subscribers.channel_name', '=', 'channels.name')
            ->where('subscribers.profile_id', '=', $id)
            ->where('subscribers.channel_name', 'like', 'network.%')
            ->where('subscribers.channel_name', 'not like', 'feed.' . $id)
            ->where('subscribers.channel_name', 'not like', 'network.' . $id)
            ->where('subscribers.channel_name', 'not like', 'public.' . $id)
            ->whereNull('subscribers.deleted_at')
            ->get();
        return \App\Recipe\Profile::whereIn("id", $channelOwnerProfileIds->pluck('profile_id')->toArray())->get();
    }

    public function getFollowingProfilesAttribute()
    {
        $count = \Redis::SCARD("following:profile:".$this->id);
        if( $count > 0 && \Redis::sIsMember("following:profile:".$this->id,$this->id)){
                $count = $count - 1;
        }
        
//        if ($count > 1000000) {
//            $count = round($count / 1000000, 1) . "m";
//        } elseif ($count > 1000) {
//            $count = round($count / 1000, 1) . "k";
//        }

        return ['count' => $count];

    }

    public static function getFollowers($id)
    {
        //just get the profile ids first
        //then fire another query to build the required things

        $profileIds = \DB::table('profiles')->select('profiles.id')
            ->join('subscribers', 'subscribers.profile_id', '=', 'profiles.id')
            ->where('subscribers.channel_name', 'like', 'network.' . $id)
            ->where('subscribers.profile_id', '!=', $id)
            ->whereNull('profiles.deleted_at')
            ->whereNull('subscribers.deleted_at')
            ->get();

        return \App\Recipe\Profile::whereIn('id', $profileIds->pluck('id')->toArray())->get();
    }

    /**
     * Get people following me.
     *
     * @return array
     */
    public function getFollowerProfilesAttribute()
    {
        $count = \Redis::SCARD("followers:profile:".$this->id);
        if(\Redis::sIsMember("followers:profile:".$this->id,$this->id)){
                $count = $count - 1;
        }
    
        if($count === 0){
            return ['count' => 0, 'profiles' => null];
        }
        
//        if ($count > 1000000) {
//            $count = round($count / 1000000, 1) . "m";
//        } elseif ($count > 1000) {
//            $count = round($count / 1000, 1) . "k";
//        }
    
        return ['count' => $count];

    }

    public function getMutualFollowersAttribute()
    {
        if($this->id != request()->user()->profile->id)
        {
            $profileIds = \Redis::SINTER("followers:profile:".$this->id,"followers:profile:".request()->user()->profile->id);
            if(!count($profileIds)){
                return ['count' => 0, 'profiles' => []];
            }
            $i = 0;
            $profileInfo = [];
            foreach ($profileIds as $profileId)
            {
                if($i == 5)
                    break;
                $profileInfo[] = "profile:small:".$profileId;
                $i++;
            }
            $data = [];
            if(count($profileInfo))
            $data = \Redis::mget($profileInfo);

            foreach($data as &$profile){
                $profile = json_decode($profile);
            }
            return ['count' => count($profileIds), 'profiles' => $data];
        }
    }

    public function photos()
    {
        return $this->belongsToMany('App\Photo', 'profile_photos', 'profile_id', 'photo_id');
    }

    public function projects()
    {
        return $this->hasMany('App\Profile\Project');
    }

    public function training()
    {
        return $this->hasMany('App\Profile\Training');
    }

    public function affiliation()
    {
        return $this->hasMany('App\Profile\Affiliation');
    }

    public function education()
    {
        return $this->hasMany('App\Education');
    }

    public function companies()
    {
        return $this->hasManyThrough('\App\Company', 'App\User');
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
        return $filename === null ? $relativePath : storage_path("app/" . $relativePath) . "/" . $filename;
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
        return $this->belongsToMany('\App\Ideabook', 'ideabook_profiles', 'profile_id', 'ideabook_id');
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

        $channel = Channel::where($whereClause, $owner->id)->where('name', 'like', $channelName)->first();

        if ($channel === null) {
            if (!$createIfNotExist) {
                throw new ModelNotFoundException("Channel not found.");
            }

            $channel = Channel::create(['name' => $channelName, $whereClause => $owner->id]);
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
        if ($owner instanceof Company === false) {
            $this->subscribe("network", $owner);
        }

        return $this->subscribe("public", $owner);
    }

    public function subscribe($channelName, &$owner)
    {
        return $this->getChannel($channelName, $owner, true)->subscribe($this->id);
    }

    public function unsubscribeNetworkOf(&$owner)
    {
        $this->unsubscribe("network", $owner);
        return $this->unsubscribe("public", $owner);
    }

    public function unsubscribe($channelName, &$owner)
    {
        return $this->getChannel($channelName, $owner, false)->unsubscribe($this->id);
    }

    //todo: remove this method if not used.
    private function addSubscriber(Profile $owner)
    {
        $channelName = 'network.' . $this->id;
        $channel = $this->channels()->where('name', 'like', $channelName)->first();

        if (!$channel) {
            //create channel
            $channel = $this->channels()->create(['name' => $channelName]);
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
        return Payload::select('payload')->whereHas('channel', function ($query) use ($profileId) {
            $query->where('channels.profile_id', $profileId)
                ->where('channels.name', 'not like', 'network.' . $profileId)
                ->where('channels.name', 'not like', 'public.' . $profileId);
        })->orderBy('created_at', 'desc')->get();
    }

    /**
     * Feed which a user would see if he visits his /profile page.
     */
    public function profileFeed()
    {
        $profileId = $this->id;
        return Payload::select('payload')->whereHas('channel', function ($query) use ($profileId) {
            $query->where('channel_name', 'profile.' . $profileId);
        })->orderBy('created_at', 'desc')->get();
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
        })->orderBy('created_at', 'desc')->get();
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
        return \Redis::sIsMember("following:profile:" . $profileId,$followerProfileId) === 1;
        //return Subscriber::where('profile_id', $followerProfileId)->where("channel_name", 'like', 'network.' . $profileId)->count() === 1;
    }

    //specific to API
    public function getResumeUrlAttribute()
    {
        return !is_null($this->resume) ? \Storage::url($this->resume) : null;
    }

    public function getAddressAttribute($value)
    {
        if (!empty($value)) {

            if(request()->user()->profile->id == $this->id)
            {
                return $value;
            }

            if($this->address_private == 3)
            {
                return null;
            }
            if(!\Redis::sIsMember("followers:profile:".request()->user()->profile->id,$this->id) && $this->address_private == 2)
            {
                return null;
            }
            return $value;
        }
    }

    public function getPhoneAttribute($value)
    {
        if (!empty($value)) {

            if(request()->user()->profile->id == $this->id)
            {
                return $value;
            }

            if($this->phone_private == 3)
            {
                return null;
            }
            if(!\Redis::sIsMember("followers:profile:".request()->user()->profile->id,$this->id) && $this->phone_private == 2)
            {
                return null;
            }
            return $value;
        }
    }

    public function getNotificationCountAttribute()
    {
        return \DB::table('notifications')->whereNull('last_seen')->where('notifiable_id',request()->user()->profile->id)->count();
    }

    public function getNotificationContent($action = null)
    {
//        if($action && $action == 'follow') {
            return [
                'name' => strtolower(class_basename(self::class)),
                'id' => $this->id,
                'tagline' => $this->tagline,
                'image' => $this->imageUrl,
                'content' => null,
            ];
//        }
    }

    public function getMessageCountAttribute()
    {
        return \DB::table('chat_members')->whereNull('last_seen')->where('profile_id',request()->user()->profile->id)->count();
    }

    public function getAddPasswordAttribute()
    {
        if(request()->user()->profile->id != $this->id)
        {
            return false;
        }
        else
        {
            return \DB::table('users')->whereNull('password')->where('id',request()->user()->id)->exists();
        }
    }

    public function routeNotificationForMail()
    {
        return $this->user->email;
    }

    public function getUnreadNotificationCountAttribute()
    {
        return \DB::table('notifications')->whereNull('read_at')->where('notifiable_id',request()->user()->profile->id)->count();
    }

    public function getPreviewContent()
    {
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_profile/'.$this->id;
        $data['owner'] = $this->id;
        $data['title'] = 'Check out '.$this->name.'\'s profile on TagTaste';
        $data['description'] = substr($this->tagline,0,155);
        $data['ogTitle'] = 'Check out '.$this->name.'\'s profile on TagTaste';
        $data['ogDescription'] = substr($this->tagline,0,155);
        $data['ogImage'] = $this->imageUrl;
        $data['cardType'] = 'summary_large_image';
        $data['ogUrl'] = env('APP_URL').'/profile/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/profile/'.$this->id;
        if(empty($this->imageUrl)) {
            $data['cardType'] = 'summary';
        }

        return $data;

    }

    public function getremainingMessagesAttribute()
    {
        if(request()->user()->profile->id == $this->id)
        {
            $remaining = \DB::table('chat_limits')->select('remaining')->where('profile_id',$this->id)->first();
            return $remaining->remaining;
        }
    }

}
