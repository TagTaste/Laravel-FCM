<?php

namespace App\Profile;

use App\Events\Auth\Registered;
use \App\User as BaseUser;

class User extends BaseUser
{
    protected $with = ['profile','companies']; //'articles','ideabooks','companies'

    protected $visible = ['name','email','profile','companies']; //'articles','recommend','ideabooks',
    
    public static function boot()
    {
        parent::boot();

        self::created(function(User $user){
            $user->profile()->create([]);
        });

        self::deleting(function($user){
            if($user->profile->count()){
                $user->profile->delete();
            }

            if($user->social->count()){
                $user->social->delete();
            }

            if($user->products->count()){
                $user->products->delete();
            }

            if($user->ideabooks->count()){
                $user->ideabooks->delete();
            }

            if($user->articles->count()){
                $user->articles->delete();
            }

        });
    }

    public function articles()
    {
        return $this->hasMany('\App\Article');
    }

    public function profile() {
        return $this->hasOne('\App\Profile');
    }

    public function addFoodieImage($fileUrl)
    {
        $file = file_get_contents($fileUrl);
        $filename = str_random(20) . ".jpg";
        file_put_contents(storage_path('app/files/') . $filename,$file);

        $profile = $this->addProfileValue('foodie','image',$filename);
        if(!$profile){
            throw new \Exception("Could not create profile.");
        }

        $when = Carbon::now()->addSecond(10);
        $this->notify((new NotifyUserAvatarUpdateComplete())->delay($when));

        return $profile;

    }

    public function addProfileValue($profileType,$attributeName, $value)
    {
        $typeId = ProfileType::getTypeId($profileType);

        $attribute = ProfileAttribute::where('name','like','%' . $attributeName . '%')->where('profile_type_id','=',$typeId)->first();

        return $this->profile()->create(['profile_attribute_id'=>$attribute->id,'type_id'=>$typeId,'value'=>$value]);
    }

    public function getProfileAttributeId($name)
    {
        $attribute = \App\ProfileAttribute::select('id')->where('name','like', $name . '_id')->first();
        if(!$attribute){
            throw new \Exception("Could not find $name attribute");
        }
        return $attribute;
    }

    public function getProfileId($name)
    {
        $attribute = $this->getProfileAttributeId($name);

        $profile = \App\Profile::select('id')->where('profile_attribute_id','=',$attribute->id)->where('user_id','=',$this->id)->first();
        if(!$profile){
            throw new \Exception("User has not created $name profile yet.");
        }

        return $profile->id;
    }

    public function getFoodieProfileId()
    {
        try {
            $profileId = $this->getProfileId('foodie');
        } catch (\Exception $e){
            throw $e;
        }

        return $profileId;
    }

    public function getChefProfileId() {

        try {
            $profileId = $this->getProfileId("chef");
        } catch (\Exception $e){
            //cascade, get foodie profile in the end.
            $profileId = $this->getFoodieProfileId();
        }

        return $profileId;

    }

    public static function getAdmin()
    {
        return static::select('id','name')->whereHas("roles",function($query){
            $query->where('name','like','admin');
        })->first();
    }

    public function attachDefaultRole()
    {
        $role = Role::where('name', '=', 'foodie')->first();

        if(!$role){
            throw new \Exception("Could not find default role");
        }


        $this->attachRole($role);
        return;
    }

    public function createDefaultProfile()
    {
        Profile::createDefaultProfile($this->id);
        return;
    }

    public function createDefaultIdeabook()
    {
        $publicId = Privacy::defaultId();
        return $this->ideabooks()->create(['name'=>'Ideabook','description'=>'All your ideas in one place.','privacy_id'=>$publicId]);
    }

    public function getArticles()
    {
        return \App\Article::with('template','dish','blog')
            ->where('user_id','=',$this->id)
            ->get();
    }

    public function social()
    {
        return $this->hasMany('\App\SocialAccount');
    }

    public static function findSocialAccount($provider,$providerId)
    {
        $user = static::whereHas('social',function($query) use ($provider,$providerId){
            $query->where('provider','like',$provider)->where('provider_user_id','=',$providerId);
        })->first();

        if(!$user){
            throw new SocialAccountUserNotFound($provider);
        }

        return $user;
    }

    public static function addFoodie($name, $email = null, $password, $socialRegistration = false, $provider = null, $providerUserId = null, $avatar = null)
    {

        $user = static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'social_registration'=>$socialRegistration
        ]);

        if(!$user){
            throw new \Exception("Could not create user.");
        }

        //attach default role
        $user->attachDefaultRole();

        //create default profile
        //$user->createDefaultProfile();

        //check social registration
        if($socialRegistration){
            $user->createSocialAccount($provider,$providerUserId,$avatar);
        }

        $user->createDefaultIdeabook();
        
        event(new Registered($user));
        return $user;
    }
    
    public function createSocialAccount($provider,$providerUserId,$avatar)
    {
        //create social account
        $this->social()->create([
            'provider' => $provider,
            'provider_user_id' => $providerUserId,
            //     'profile_type_id' => ProfileType::getTypeId('foodie')
        ]);
    
        //get profile image from $provider
        if($avatar){
            $job = (new FetchUserAvatar($this,$avatar))->onQueue('registration')
                ->delay(Carbon::now()->addSeconds(10));
            \Log::info('Queueing job...');
        
            dispatch($job);
        }
    }

    public function getSocial($typeId)
    {
        $social = $this->social()->where('profile_type_id','=',$typeId)->first();

        if(!$social){
            throw new \Exception("Social account not found for Profile Type $typeId.");
        }

        return $social;

    }

    public function products(){
        return $this->hasMany('\App\Product');

    }

    public function ideabooks()
    {
        return $this->hasMany('\App\Ideabook');
    }

    public function getDefaultIdeabook()
    {
        $ideabook = $this->ideabooks->first();
        if(!$ideabook){
            throw new \Exception("Ideabook not created yet.");
        }

        return $ideabook;
    }

    public function restore()
    {
        return $this->softRestore();
    }

    public function getRecommendAttribute()
    {
        $recommendations = Recommend::get();

        return $recommendations;
    }

    public function companies()
    {
        return $this->hasMany('App\Company');
    }
    
    public function isPartOfCompany($companyId)
    {
        $company = $this->companies()->find($companyId);
        if($company){
            return true;
        }
    
        return CompanyUser::where('company_id',$companyId)->where("user_id",$this->id)->count() === 1;
    }
}