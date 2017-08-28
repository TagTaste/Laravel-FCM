<?php

namespace App;

use App\Company\Address;
use App\Company\Advertisement;
use App\Company\Book;
use App\Company\Coreteam;
use App\Company\Patent;
use App\Company\Status;
use App\Company\Type;
use App\Traits\PushesToChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Storage;

class Company extends Model
{
    use PushesToChannel, SoftDeletes;
    
    protected $fillable = [
        'name',
        'about',
        'logo',
        'hero_image',
        'phone',
        'email',
        'registered_address',
        'established_on',
        'type',
        'employee_count',
        'client_count',
        'annual_revenue_start',
        'annual_revenue_end',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'instagram_url',
        'youtube_url',
        'pinterest_url',
        'google_plus_url',
        'tagline',
        'establishments',
        'cuisines',
        'websites',
        'milestones',
        'speciality',
        'handle',
        'city',
        'user_id'
    ];
    
    protected $visible = [
        'id',
        'name',
        'about',
        'logo',
        'hero_image',
        'phone',
        'email',
        'registered_address',
        'established_on',
        'type',
        'employee_count',
        'client_count',
        'annual_revenue_start',
        'annual_revenue_end',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'instagram_url',
        'youtube_url',
        'pinterest_url',
        'google_plus_url',
        'tagline',
        'establishments',
        'cuisines',
        'websites',
        'advertisements','addresses','type','status','awards','photos','patents','books','portfolio','coreteam','gallery',
        'created_at',
        'milestones',
        'speciality',
        'profileId',
        'handle',
        'followerProfiles',
        'rating',
        'city',
        'is_admin'
    ];
    
    protected $with = ['advertisements','addresses','type','status','awards','patents','books','portfolio','productCatalogue','coreteam','gallery'];

    protected $appends = ['statuses','companyTypes','profileId','followerProfiles','rating','is_admin'];
    
    public static function boot()
    {
        parent::boot();
        
        self::created(function(Company $company){
            $profile = $company->user->profile;
            $profile->subscribe("public",$company);
            $profile->subscribe("network",$company);
            
            //add creator as a user of his company
            $company->addUser($company->user);
            
            //make searchable
            \App\Documents\Company::create($company);
        });
        
        self::updated(function(Company $company){
            
            //update the document
            \App\Documents\Company::create($company);
        });
    }

    public function setEstablishedOnAttribute($value)
    {
        $this->attributes['established_on'] = date("Y-m-d",strtotime($value));
    }

    public function getEstablishedOnAttribute($value)
    {
        if (!empty($value)) {
            return date("d-m-Y", strtotime($value));
        }
    }
    
    public function photos()
    {
        return $this->belongsToMany('App\Photo','company_photos','company_id','photo_id');
    }

    public function awards()
    {
        return $this->belongsToMany('App\Company\Award','company_awards','company_id','award_id');
    }

    public function coreteam()
    {
        return $this->hasMany(Coreteam::class);
    }

    //company creater user 
    public function user()
    {
        return $this->belongsTo('App\User');
    }
 
    public function users()
    {
        return $this->belongsToMany('App\User','company_users','company_id','user_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Company\Status','status_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Company\Type','type');
    }

    public function websites()
    {
        return $this->hasMany('App\Company\Website','company_id','id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    public function patents()
    {
        return $this->hasMany(Patent::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function rating()
    {
        return $this->hasMany(CompanyRating::class,'company_id');
    }

    public function getStatusesAttribute($value = null)
    {
        return Status::all()->pluck('name','id');
    }

    public function getCompanyTypesAttribute($value = null)
    {
        return Type::all()->pluck('name','id');
    }
    
    public function portfolio()
    {
        return $this->hasMany(\App\Company\Portfolio::class);
    }
    
    //there should be a better way to write the paths.
    public static function getLogoPath($profileId,$id, $filename = null)
    {
        $relativePath = "images/c/{$id}/l";
    
        Storage::makeDirectory($relativePath);
        if($filename === null){
            return $relativePath;
        }
        return storage_path("app/" . $relativePath . "/" . $filename);
        
    }
    
    //there should be a better way to write the paths.
    public static function getHeroImagePath($profileId, $id, $filename = null)
    {
        $relativePath = "images/c/{$id}/hi";
        Storage::makeDirectory($relativePath);
        if($filename == null){
            return $relativePath;
        }
        return storage_path("app/" . $relativePath . "/" . $filename);
    }
    
    public function jobs()
    {
        return $this->hasMany(\App\Job::class);
    }
    
    public function applications()
    {
        return $this->jobs()->join('applications','jobs.id','=','applications.job_id')
            ->join('profiles','applications.profile_id','=','profiles.id')
            ->where('jobs.company_id',$this->id)->get();
    }
    
    public function products()
    {
        return $this->hasMany(\App\Company\Product::class);
    }
    
    public function collaborate()
    {
        return $this->hasMany(\App\Collaborate::class);
    }
    
    public function shoutouts()
    {
        return $this->hasMany(Shoutout::class);
    }
    
    public function channels()
    {
        return $this->hasMany(Channel::class);
    }
    
    /**
     * Get Company User Profiles
     *
     * @return \App\CompanyUser[]|null
     */
    public function getUsers()
    {
        return \App\CompanyUser::with('profile')->where("company_id",$this->id)->get();
    }
    
    
    /**
     * Add User to Company
     *
     * @param $userId
     * @return bool|void
     */
    public function addUser($user)
    {
        //check if already exists
        
        $exists = $this->users()->find($user->id);
        
        if($exists){
            throw new \Exception("User {$exists->name} already exists in the company.");
        }
        
        //attach the user
        $this->users()->attach($user->id,['profile_id'=>$user->profile->id]);
        
        //subscribe the user to the company feed
        $user->completeProfile->subscribe("public",$this);
        $user->completeProfile->subscribe("network",$this);
        return true;
    }
    
    /**
     * Remove user from company;
     *
     * @param $userId
     * @return bool
     */
    public function removeUser($userId)
    {
        $user = User::find($userId);
        
        if(!$user){
            throw new \Exception("User $userId does not exist. Cannot add to Company " . $this->name);
        }
        
        $user = CompanyUser::where('user_id',$userId)->where('company_id',$this->id)->first();
        
        if(!$user){
            throw new \Exception("User " . $userId ." does not belong to company " . $this->name);
        }
    
        //unsubscribe the user to the company feed
        $user->profile->unsubscribe("public",$this);
        $user->profile->unsubscribe("network",$this);
    
        return $user->delete();
    }
    
    public function checkCompanyUser($userId)
    {
        if($this->user_id === $userId){
            return true;
        }
        
        return CompanyUser::where('company_id',$this->id)->where("user_id",$userId)->count() === 1;
    }
    
    public function getLogoAttribute($value)
    {
        return !is_null($value) ? \Storage::url($value) : null;
    }
    
    public function getHeroImageAttribute($value)
    {
        return !is_null($value) ? \Storage::url($value) : null;
    }
    
    public function getProfileIdAttribute()
    {
        return $this->user->profile->id;
    }
    
    public function getFollowerProfilesAttribute()
    {
    
        //if you use \App\Profile here, it would end up nesting a lot of things.
        $profiles = Company::getFollowers($this->id);
    
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
    
    public static function getFollowers($id)
    {
        //just get the profile ids first
        //then fire another query to build the required things
        
        $profileIds = \DB::table('profiles')->select('profiles.id')
            ->join('subscribers','subscribers.profile_id','=','profiles.id')
            ->where('subscribers.channel_name','like','company.public.' . $id)
//            ->where('subscribers.profile_id','!=',$id)
            ->whereNull('profiles.deleted_at')
            ->whereNull('subscribers.deleted_at')
            ->get();
        
        return \App\Recipe\Profile::whereIn('id',$profileIds->pluck('id')->toArray())->get();
    }
    
    public function isFollowing($followerProfileId)
    {
        return Subscriber::where('profile_id',$followerProfileId)->where("channel_name",'like','company.public.' . $this->id)->exists();
    }

    public function getRatingAttribute()
    {
        return $this->rating()->avg('rating');
    }
    
    public function productCatalogue()
    {
        return $this->hasMany(ProductCatalogue::class);
    }
    
    public function getIsAdminAttribute()
    {
        $userId = request()->user()->id;
        return $this->users()->where('user_id','=',$userId)->exists();
    }

    public function gallery()
     {
         return $this->hasMany(\App\Company\Gallery::class);
     }

}
