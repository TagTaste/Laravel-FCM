<?php

namespace App\Profile;

use App\Api\Recommend;
use App\Company\Coreteam;
use App\CompanyUser;
use App\Events\Auth\Registered;
use App\Exceptions\Auth\SocialAccountUserNotFound;
use App\Invitation;
use App\Jobs\FetchUserAvatar;
use App\Privacy;
use App\Profile;
use App\Role;
use \App\User as BaseUser;
use Carbon\Carbon;

class User extends BaseUser
{
    protected $with = ['profile']; //'articles','ideabooks','companies'

    protected $visible = ['name','email','profile']; //'articles','recommend','ideabooks',
    
    public static function boot()
    {
        parent::boot();

        self::created(function(User $user){
            $profile=$user->profile()->create([]);
            //update core team profile when using invite code registration
            $coreteam = Coreteam::where('email',$user->email)->where('invited',1)->first();
            if($coreteam)
            {
                $coreteam->update(['profile_id'=>$profile->id,'invited'=>0]);
            }
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

    public static function addFoodie($name, $email = null, $password, $socialRegistration = false,
                                     $provider = null, $providerUserId = null, $avatar = null,$alreadyVerified = 0,$accessToken = null,$inviteCode)
    {
        $user = static::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'email_token' =>str_random(15),
            'social_registration'=>$socialRegistration,
            'verified_at'=> $alreadyVerified ? \Carbon\Carbon::now()->toDateTimeString() : null
        ]);

        if(!$user){
            throw new \Exception("Could not create user.");
        }
        $accepted_at = \Carbon\Carbon::now()->toDateTimeString();
        Invitation::where('invite_code', $inviteCode)->update(["accepted_at"=>$accepted_at,'state'=>Invitation::$registered]);
        \Log::info($inviteCode);

        //attach default role
        $user->attachDefaultRole();

        //create default profile
        //$user->createDefaultProfile();

        //check social registration
        if($socialRegistration){
            $user->createSocialAccount($provider,$providerUserId,$avatar,$accessToken);
        }

        $user->createDefaultIdeabook();
        
        event(new Registered($user));
        return $user;
    }
    
    public function createSocialAccount($provider,$providerUserId,$avatar,$accessToken)
    {
        //create social account
        $this->social()->create([
            'provider' => $provider,
            'provider_user_id' => $providerUserId,
            'access_token' =>$accessToken
            //     'profile_type_id' => ProfileType::getTypeId('foodie')
        ]);
    
        //get profile image from $provider
        if($avatar){
            $file = file_get_contents("https://graph.facebook.com/$providerUserId/picture?type=normal");
            $filename = str_random(20) . ".jpg";
            file_put_contents(storage_path('app/images/p/'.$this->profile->id) . $filename,$file);

            Profile::where('id',$this->profile->id)->update(['image'=>'images/p/'.$this->profile->id.'/'.$filename]);
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
