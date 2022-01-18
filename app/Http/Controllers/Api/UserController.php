<?php

namespace App\Http\Controllers\Api;

use App\Events\EmailVerification;
use App\Exceptions\Auth\SocialAccountUserNotFound;
use App\Profile;
use App\SocialAccount;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Events\Actions\JoinFriend;
use App\Jobs\RemoveDuplicateFromAppInfo;

class UserController extends Controller
{

    public function register(Request $request)
    {
        if(!$request->has('user')){
           return $this->sendError("Missing user data.");
        }
        
        $validator = Validator::make($request->input('user'), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        if($validator->fails()){
            return ['status'=>'failed','errors'=>$validator->messages(),'result'=>[]];
        }

        $alreadyVerified = false;
        $result = ['status'=>'success','newRegistered' =>true];
        $user = \App\Profile\User::addFoodie($request->input('user.name'),$request->input('user.email'),$request->input('user.password'),
            false,null,null,null,$alreadyVerified,null,null,null);
        $result['result'] = ['user'=>$user,'token'=>  \JWTAuth::attempt(
            ['email'=>$request->input('user.email')
                ,'password'=>$request->input('user.password')])];
        $companies = \App\Company::whereIn('id',[111,137,322])->get();
        foreach ($companies as $company) {
            $model = $user->completeProfile->subscribeNetworkOf($company);
            if($model) {
                //companies the logged in user is following
                \Redis::sAdd("following:profile:" . $user->profile->id, "company.$company->id");

                //profiles that are following $channelOwner
                \Redis::sAdd("followers:company:" . $company->id, $user->profile->id);
            }
        }
        $mail = (new \App\Jobs\EmailVerification($user))->onQueue('emails');
        \Log::info('Queueing Verified Email...');

        dispatch($mail);

        return response()->json($result);
    }

    public function verify(Request $request,$token)
    {
        $user = \App\Profile\User::where("email_token", $token)->first();
        if($user)
        {
            $user->verified_at = \Carbon\Carbon::now()->toDateTimeString();
            $this->model = $user->save();
            return $this->sendResponse();

        }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password'          => 'required|min:6',
            'password'              => 'required|min:6|confirmed|different:current_password',
            'password_confirmation' => 'required|min:6',
        ]);

        if($validator->fails()){
            return ['status'=>'failed','errors'=>$validator->messages(),'result'=>[]];
        }

        $user = \App\Profile\User::findorFail($request->user()->id);
        $hashedPassword = $user->password;
        if (Hash::check($request->input("current_password"), $hashedPassword)) {
            //Change the password
            $this->model = $user->fill([
                'password' => Hash::make($request->input("password"))
            ])->save();
            $mail = (new \App\Jobs\PasswordConfirmation($user))->onQueue('emails');
            \Log::info('Queueing Verified Email...');

            dispatch($mail);
            return $this->sendResponse();
        }
        
        return $this->sendError("Your password has not been changed.");
    }
    
    public function fcmToken(Request $request)
    {        
        $user = User::where("id", $request->user()->id)->first();
        $platform = $request->has('platform') ? $request->input('platform') : 'android' ;
        $version = $request->hasHeader('X-VERSION') ? $request->header('X-VERSION') : ($request->hasHeader('X-VERSION-IOS') ? $request->header('X-VERSION-IOS') : NULL) ;
        $device_info = $request->has('device_info') ? $request->input('device_info') : NULL ;
        
        //updated by nikhil
        $deviceInfoJson = json_decode($device_info);
        $deviceName = '';
        $deviceIdentifier = '';
        if($platform == 'ios'){
            $deviceName = strtolower( $deviceInfoJson->deviceType);  
            $deviceIdentifier = $deviceInfoJson->identifierForVendor;           
        }else{
            $deviceName = strtolower($deviceInfoJson->SERIAL);
            $deviceIdentifier = $deviceInfoJson->ID;
        }
        
        if(strpos($deviceName, 'simulator') !== false || strpos($deviceName, 'emulator') !== false){
            //yes its a simulator  or emultor, so no need to store device info.
            return $this->sendError("Simulator/emulator detected.");
        }
        
        $deviceTokenExist = \DB::table('app_info')->where('device_info->identifierForVendor',$deviceIdentifier)->orWhere('device_info->ID',$deviceIdentifier)->exists();
        // $tokenExists = \DB::table('app_info')->where('profile_id',$request->user()->profile->id)->where('fcm_token', $request->input('fcm_token'))->where('platform',$platform)->exists();
        if($deviceTokenExist)
        {
            \DB::table("app_info")->where('device_info->identifierForVendor',$deviceIdentifier)->orWhere('device_info->ID',$deviceIdentifier)
                ->update(["profile_id"=>$request->user()->profile->id,'fcm_token'=>$request->input('fcm_token'),'platform'=>$platform,'user_app_version'=>$version, 'device_info'=>$device_info]);
            $this->model = 1;
            file_put_contents(storage_path("logs") . "/notification_test.txt", "Updating token for profile id : ".$request->user()->profile->id, FILE_APPEND);
            file_put_contents(storage_path("logs") . "/notification_test.txt", "++++++++++++++++++++++++\n\n", FILE_APPEND);    
            return $this->sendResponse();
        }
        if($user)
        {
            $this->model = \DB::table("app_info")->insert(["profile_id"=>$request->user()->profile->id,'fcm_token'=>$request->input('fcm_token'),'platform'=>$platform, 'user_app_version'=>$version, 'device_info'=>$device_info]);
            file_put_contents(storage_path("logs") . "/notification_test.txt", "insert token for profile id : ".$request->user()->profile->id, FILE_APPEND);
            file_put_contents(storage_path("logs") . "/notification_test.txt", "++++++++++++++++++++++++\n\n", FILE_APPEND);    
            return $this->sendResponse();
        }
        return $this->sendError("User not found.");
    }

    public function phoneVerify(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $phone = $request->input('phone');
        if(!isset($phone) || strlen($phone)!=10)
        {
            return $this->sendError("Please enter correct phone no.");
        }
        $otp = mt_rand(100000, 999999);
        $client = new Client();
        $response = $client->get("http://websmsapp.in/api/mt/SendSMS?APIKey=TRadsx6kDk6uGls2qlcN4g&senderid=TAGTST&channel=Trans&DCS=0&flashsms=0&number=91$phone&text=your otp is $otp&route=2");
        $this->model = Profile::where('id',$loggedInProfileId)->update(['otp'=>$otp]);
        return $this->sendResponse();
    }

    public function requestOtp(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $this->model = Profile::where('id',$loggedInProfileId)->where('otp',$request->input('otp'))->update(['verified_phone'=>1]);
        return $this->sendResponse();
    }

    public function logout(Request $request)
    {
        //updated by nikhil
        // $this->model = \DB::table("app_info")->where('fcm_token',$request->input('fcm_token'))
        //     ->where('profile_id',$request->user()->profile->id)->delete();
        $this->model = \DB::table("app_info")->where('fcm_token',$request->input('fcm_token'))->delete();
        file_put_contents(storage_path("logs") . "/notification_test.txt", "Deleting fcm token for profile id : ".$request->user()->profile->id." and token : ".$request->input('fcm_token'), FILE_APPEND);
        file_put_contents(storage_path("logs") . "/notification_test.txt", "+++++++++++++++++++++\n\n", FILE_APPEND);       

        return $this->sendResponse();
    }
    
    public function verifyInviteCode(Request $request)
    {
//        $this->model = \DB::table("users")->where('invite_code',$request->input("invite_code"))->exists() ? true : false;
        $this->model = true;

        return $this->sendResponse();
    }

    /**
     * updateInviteToken route clouser to update user invite code
     */
    public function updateInviteToken(Request $request)
    {
        $inviteToken = $request->input('invite_token');

        $this->model = User::find($request->user()->id);
        $this->model->used_invite_code = $inviteToken;
        $this->model->save();
        
        return $this->sendResponse();
    }

    public function socialLink(Request $request,$provider)
    {
        $socialiteUser = $request->all();
        if(isset($socialiteUser['remove'])&&$socialiteUser['remove'] == 1)
        {
            $this->model = SocialAccount::where('user_id',$request->user()->id)->where('provider',$provider)->delete();
            \App\Profile::where('id',$request->user()->profile->id)->update([$provider.'_url'=>null,'is_'.$provider.'_connected'=>0]);
            return $this->sendResponse();
        }

        $userExist = \DB::table('social_accounts')->where('user_id',$request->user()->id)->where('provider','like',$provider)->where('provider_user_id','=',$socialiteUser['id'])->exists();
        if(!$userExist)
        {
            $user = \App\Profile\User::where('email',$request->user()->email)->first();
            $socialiteUserLink = isset($socialiteUser['user']['link']) ? $socialiteUser['user']['link']:(isset($socialiteUser['user']['publicProfileUrl']) ? $socialiteUser['user']['publicProfileUrl'] : null);
            $this->model = $user->createSocialAccount($provider,$socialiteUser['id'],$socialiteUser['avatar_original'],$socialiteUser['token'],$socialiteUserLink,false,$socialiteUser['user']);
            return $this->sendResponse();
        }
        else
        {
            $socialiteUserLink = isset($socialiteUser['user']['link']) ? $socialiteUser['user']['link']:(isset($socialiteUser['user']['publicProfileUrl']) ? $socialiteUser['user']['publicProfileUrl'] : null);
            \App\Profile::where('id',$request->user()->profile->id)->update([$provider.'_url'=>$socialiteUserLink,'is_'.$provider.'_connected'=>1]);
            return $this->sendError("Already link ".$provider." with our platform");
        }

    }

    public function feedIssue(Request $request)
    {
        $this->model = \DB::table("app_info")->where("profile_id",$request->user()->profile->id)
                            ->where('fcm_token',$request->input('fcm_token'))->update(['is_active'=>0]);
                            
        file_put_contents(storage_path("logs") . "/notification_test.txt", "Inactive app_info tuple of profile id : ".$request->user()->profile->id, FILE_APPEND);
        file_put_contents(storage_path("logs") . "/notification_test.txt", "++++++++++++++++++++++++\n\n", FILE_APPEND);
                    
        return $this->sendResponse();
    }

    public function getApkDeviceInfo(Request $request)
    {
        $device_info = $request->has('device_info') ? $request->input('device_info') : NULL ;
        $this->model = \DB::table("app_info")->where('fcm_token',$request->input('fcm_token'))->where('profile_id',$request->user()->profile->id)->update(['app_version'=>$request->header('X-VERSION'),'device_info'=>$device_info]);
        file_put_contents(storage_path("logs") . "/notification_test.txt", "updating device info and apk version of profile id : ".$request->user()->profile->id." and fcm token : ".$request->input('fcm_token'), FILE_APPEND);
        file_put_contents(storage_path("logs") . "/notification_test.txt", "++++++++++++++++++++++++\n\n", FILE_APPEND);
          
        return $this->sendResponse();

    }


}
