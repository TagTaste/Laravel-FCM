<?php

namespace App\Profile;

use App\Api\Recommend;
use App\CompanyUser;
use App\Events\Auth\Registered;
use App\Exceptions\Auth\SocialAccountUserNotFound;
use App\Invitation;
use App\Privacy;
use App\Profile;
use App\Role;
use \App\User as BaseUser;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class User extends BaseUser
{
    protected $with = ['profile']; //'articles','ideabooks','companies'

    protected $visible = ['name','email','profile','verified_at']; //'articles','recommend','ideabooks',
    
    public static function boot()
    {
        parent::boot();

        self::created(function(User $user){
            $profile=$user->profile()->create([]);
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

    public static function findSocialAccount($provider,$providerId,$socialiteUser,$socialiteUserLink)
    {
        $user = static::whereHas('social',function($query) use ($provider,$providerId){
            $query->where('provider','like',$provider)->where('provider_user_id','=',$providerId);
        })->first();

        if(!$user){
            throw new SocialAccountUserNotFound($provider);
        }

        if($provider == 'google')
        {
            $user->updateProfileInfo($provider,null, $socialiteUserLink);

        }
        else
        {
            $user->updateProfileInfo($provider,$socialiteUser['user'], $socialiteUserLink);
        }
        return $user;
    }

    public static function addFoodie($name, $email = null, $password, $socialRegistration = false,
                                     $provider = null, $providerUserId = null, $avatar = null,$alreadyVerified = 0,$accessToken = null,$socialLink = null,$socialiteUserInfo)
    {

        $user = BaseUser::withTrashed()->where('email',$email)->first();

        if($user)
        {
            $user->restore();
        }
        else
        {
            $user = static::create([
                'name' => ucwords($name),
                'email' => $email,
                'password' => is_null($password) ? null : bcrypt($password),
                'email_token' =>str_random(15),
                'social_registration'=>$socialRegistration,
                'verified_at'=> $alreadyVerified ? \Carbon\Carbon::now()->toDateTimeString() : null
            ]);
        }

        if(!$user){
            throw new \Exception("Could not create user.");
        }

        //attach default role
        //$user->attachDefaultRole();

        //create default profile
        //$user->createDefaultProfile();

        //check social registration
        if($socialRegistration){
            $user->createSocialAccount($provider,$providerUserId,$avatar,$accessToken,$socialLink,true,$socialiteUserInfo);
        }

        $user->createDefaultIdeabook();
        
        event(new Registered($user));
        return $user;
    }
    
    public function createSocialAccount($provider,$providerUserId,$avatar,$accessToken,$socialLink = null,$newavatar = false,$socialiteUserInfo)
    {
        //create social account
        $this->social()->create([
            'provider' => $provider,
            'provider_user_id' => $providerUserId,
            'access_token' =>$accessToken
            //     'profile_type_id' => ProfileType::getTypeId('foodie')
        ]);
    
        //get profile image from $provider
        if($avatar && $newavatar){
            $filename = $this->getAvatarImage($avatar);
            $s3 = \Storage::disk('s3');
            $filePath = 'images/p/' . $this->profile->id;
            $resp = $s3->putFile($filePath, new File($filename), ['visibility'=>'public']);
            $meta = ['tiny_photo'=>$resp];
            $imageMeta = ['original_photo'=>$resp,'tiny_photo'=>$resp,'meta'=>$meta];
            Profile::where('id',$this->profile->id)->update(['image'=>$resp,'image_meta'=>json_encode($imageMeta,true)]);
        }
        \App\User::where('email',$this->email)->update(['verified_at'=>\Carbon\Carbon::now()->toDateTimeString()]);
        if(isset($this->profile->id))
            \App\Profile::where('id',$this->profile->id)->update([$provider.'_url'=>$socialLink]);
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

    public function getAvatarImage($avatar)
    {
        
        //$file = file_get_contents($avatar);
        $file = $this->get_web_page($avatar);
        $filename = str_random(20) . ".jpg";
        $path = 'images/p/' . $this->profile->id;
        $path = storage_path($path);

        if(!is_dir($path) && !mkdir($path,0755,true)){
            \Log::info("Did not create directory.");
        }
        $filename = $path . "/" . $filename;
        file_put_contents($filename,$file);
        return $filename;
        
    }
    
    public function get_web_page( $url )
    {
        $url = urldecode($url);
        $url = htmlspecialchars_decode($url);
        $options = array(
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
            CURLOPT_CAINFO => app_path("cacert.pem")
        );
        
        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );
        \Log::debug($err);
        \Log::debug($errmsg);
        \Log::debug($header);
        return $content;
        
//        $header['errno']   = $err;
//        $header['errmsg']  = $errmsg;
//        $header['content'] = $content;
//        return $header;
    }

    public function updateProfileInfo($provider, $socialiteUserInfo, $socailLink = null)
    {
//        $dob = $this->profile->dob;
//        $dob = isset($dob)&&!is_null($dob) ? $dob : isset($socialiteUserInfo['birthday']) ? $socialiteUserInfo['birthday'] : null;
//        $res = explode("/", $dob);
//        $dob = $res[2]."-".$res[0]."-".$res[1];
//        $location = $this->profile->address;
//        $location = isset($location)&&!is_null($location) ? $location :
//            isset($socialiteUserInfo['location']['name']) ? $socialiteUserInfo['location']['name'] : null;
//
//        $gender = isset($this->profile->gender) ? $this->profile->gender : isset($socialiteUserInfo['gender']) ? $socialiteUserInfo['gender'] : null;

        \App\User::where('email',$this->email)->update(['verified_at'=>\Carbon\Carbon::now()->toDateTimeString()]);

        if(isset($this->profile->id))
            \App\Profile::where('id',$this->profile->id)->update([$provider.'_url'=>$socailLink]);
//        ,'dob'=>$dob,'address'=>$location,
//            'gender'=>$gender

        return true;
    }
}
