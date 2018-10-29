<?php

namespace App;

use App\Company\Address;
use App\Company\Advertisement;
use App\Company\Affiliation;
use App\Company\Book;
use App\Company\Coreteam;
use App\Company\Patent;
use App\Company\Status;
use App\Company\Type;
use App\Traits\PushesToChannel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

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
        'user_id',
        'affiliations',
        'style_logo',
        'style_hero_image',
        'logo_meta',
        'hero_image_meta'
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
        'city',
        'is_admin',
        'avg_rating',
        'review_count',
        'rating_count',
        'product_catalogue_count',
        'product_catalogue_category_count',
        'isFollowing',
        'employeeCountArray',
        'employeeCountValue',
        'affiliations',
        'style_logo',
        'style_hero_image',
        'company_id',
        'is_premium',
        'logo_meta',
        'hero_image_meta'
    ];
    
    protected $with = ['advertisements','addresses','type','status','awards','patents','books',
        'portfolio','productCatalogue','coreteam','gallery'];

    protected $appends = ['statuses','companyTypes','profileId','followerProfiles','is_admin','avg_rating','review_count','rating_count',
        'product_catalogue_count','product_catalogue_category_count','isFollowing','employeeCountArray','employeeCountValue', 'company_id'];

    private $empValue = ['1','2 - 10','11 - 50','51 - 200','201 - 500','501 - 1000','1001 - 5000','5001 - 10,000','10,000+'];

    public function getEmployeeCountArrayAttribute()
    {
        return $this->empValue;
    }

    public function getEmployeeCountValueAttribute()
    {
        return isset($this->empValue[$this->employee_count]) ? $this->empValue[$this->employee_count] : null;
    }

    public static function boot()
    {
        parent::boot();
        
        self::created(function(Company $company){
            $profile = $company->user->profile;
            $profile->subscribe("public",$company);
            $profile->subscribe("network",$company);
            
            //add creator as a user of his company
            $company->addUser($company->user);
            $company->addToCache();
            //make searchable
            \App\Documents\Company::create($company);
        });
        
        self::updated(function(Company $company){
            $company->addToCache();
    
            //update the document
            \App\Documents\Company::create($company);
        });
        
        self::deleting(function($company){
            \App\Documents\Company::delete($company);
            \App\Filter\Company::removeModel($company->id);
            $company->removeFromCache();
        });
    }

    public function getCompanyIdAttribute()
    {
        return $this->id;
    }
    
    public function addToCache()
    {
        $data = [
            'id' => $this->id,
            'profileId' => $this->profileId,
            'name' => $this->name,
            'logo' => $this->logo,
            'tagline' => $this->tagline,
            'city' =>$this->city
        ];
        \Redis::set("company:small:" . $this->id,json_encode($data));
    }

    public static function getFromCache($id)
    {
        return \Redis::get('company:small:' . $id);
    }

    public function removeFromCache()
    {
        \Redis::del("company:small:" . $this->id);
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

    public function affiliation()
    {
        return $this->hasMany(Affiliation::class);
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
        $this->users()->attach($user->id,['profile_id'=>$user->profile->id,'created_at'=>Carbon::now()->toDateTimeString()]);

//        //companies the logged in user is following
//        \Redis::sAdd("following:profile:" . $user->profile->id, "company.$this->id");
//
//        //profiles that are following $channelOwner
//        \Redis::sAdd("followers:company:" . $this->id, $user->profile->id);
        
        //subscribe the user to the company feed
//        $user->completeProfile->subscribe("public",$this);
//        $user->completeProfile->subscribe("network",$this);
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
//        $user->profile->unsubscribe("public",$this);
//        $user->profile->unsubscribe("network",$this);
        
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
        try{
            return !is_null($value) ? \Storage::url($value) : null;
        } catch (\Exception $e){
            \Log::warning("Couldn't get logo for company" . $this->id);
            \Log::warning($e->getMessage());
        }
    }
    
    public function getHeroImageAttribute($value)
    {
        if(is_null($value))
        {
            $fileId = 14 - $this->id % 14;
            return "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/app/bannerImage/".$fileId.".jpg";
        }
        else
        {
            return \Storage::url($value);
        }
    }
    
    public function getProfileIdAttribute()
    {
        return $this->user->profile->id;
    }
    
    public function getFollowerProfilesAttribute()
    {
    
        //if you use \App\Profile here, it would end up nesting a lot of things.
//        $profiles = Company::getFollowers($this->id);
        $count = \Redis::sCard("followers:company:" . $this->id);
//        if($count > 1000000)
//        {
//            $count = round($count/1000000, 1);
//            $count = $count."M";
//        }
//        elseif($count > 1000)
//        {
//
//            $count = round($count/1000, 1);
//            $count = $count."K";
//        }
    
        return ['count'=> $count];
    
    }
    
    public static function getFollowers($id)
    {
        $profileIds = \Redis::SMEMBERS("followers:company:" . $id);

        foreach ($profileIds as &$profileId)
        {
            $profileId = "profile:small:".$profileId;
        }
        $data = [];
        if(count($profileIds)) {
            $data = \Redis::mget($profileIds);
        }
        $followerProfileId = request()->user()->profile->id;
        foreach ($data as &$datum)
        {
            $datum = json_decode($datum,true);
            if(!isset($data['id'])){
                continue;
            }
            $datum['isFollowing'] = \Redis::sIsMember("following:profile:" . $followerProfileId,$datum['id']) == 1;
//            $datum['self'] = $followerProfileId === $datum['id'];
        }
        return $data;
    }
    
    public function getIsFollowingAttribute()
    {
        return $this->isFollowing(request()->user()->profile->id);
    }
    public function isFollowing($followerProfileId = null)
    {
        return \Redis::sIsMember("following:profile:" . $followerProfileId,"company." . $this->id) === 1;
    }
    
    public static function checkFollowing($followerProfileId,$id)
    {
        return \Redis::sIsMember("following:profile:" . $followerProfileId, "company." . $id) === 1;
    }

    public function getAvgRatingAttribute()
    {
        return $this->rating()->avg('rating');
    }

    public function getReviewCountAttribute()
    {
        return $this->rating()->whereNotNull('title')->count();
    }

    public function getRatingCountAttribute()
    {
        return $this->rating()->count();
    }
    
    public function productCatalogue()
    {
        return $this->hasMany(ProductCatalogue::class);
    }

    public function getProductCatalogueCountAttribute()
    {
        return $this->productCatalogue()->count();
    }

    public function getProductCatalogueCategoryCountAttribute()
    {
        return $this->productCatalogue()->whereNotNull('category')->groupBy('category')->count();
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
    
    //added by manda.
    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'content' => $this->name,
            'image' => $this->logo
        ];
    }

    public function getPreviewContent()
    {
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_profile/'.$this->id;
        $data['owner'] = $this->id;
        $data['title'] = 'Check out '.$this->name.' on TagTaste';
        $data['description'] = substr($this->about,0,155);
        $data['ogTitle'] = 'Check out '.$this->name.' on TagTaste';
        $data['ogDescription'] = substr($this->about,0,155);
        $data['ogImage'] = $this->logo;
        $data['cardType'] = 'summary_large_image';
        $data['ogUrl'] = env('APP_URL').'/companies/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/companies/'.$this->id;
        if(empty($this->logo)) {
            $data['cardType'] = 'summary';
        }

        return $data;

    }

}
