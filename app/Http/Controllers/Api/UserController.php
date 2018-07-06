<?php

namespace App\Http\Controllers\Api;

use App\Events\EmailVerification;
use App\Exceptions\Auth\SocialAccountUserNotFound;
use App\SocialAccount;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Events\Actions\JoinFriend;

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
            false,null,null,null,$alreadyVerified,null);
        $result['result'] = ['user'=>$user,'token'=>  \JWTAuth::attempt(
            ['email'=>$request->input('user.email')
                ,'password'=>$request->input('user.password')])];

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
        \Log::info($request->all());
        $platform = $request->has('platform') ? $request->input('platform') : 'android' ;
        $tokenExists = \DB::table('app_info')->where('profile_id',$request->user()->profile->id)->where('fcm_token', $request->input('fcm_token'))->where('platform',$platform)->exists();
        if($tokenExists)
        {
            $this->model = 1;
            return $this->sendResponse();
        }
        if($user)
        {
            $this->model = \DB::table("app_info")->insert(["profile_id"=>$request->user()->profile->id,'fcm_token'=>$request->input('fcm_token'),'platform'=>$platform]);
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

        $this->model = \DB::table("app_info")->where('fcm_token',$request->input('fcm_token'))
            ->where('profile_id',$request->user()->profile->id)->delete();
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
            $this->model = SocialAccount::where('user_id',$request->user()->id)->where('provider_user_id',$socialiteUser['id'])->delete();
            \App\Profile::where('id',$request->user()->profile->id)->update([$provider.'_url'=>null]);
            return $this->sendResponse();
        }

        $userExist = \DB::table('social_accounts')->where('user_id',$request->user()->id)->where('provider','like',$provider)->where('provider_user_id','=',$socialiteUser['id'])->exists();
        if(!$userExist)
        {
            $user = \App\Profile\User::where('email',$request->user()->email)->first();
            $socialiteUserLink = isset($socialiteUser['user']['link']) ? $socialiteUser['user']['link']:(isset($socialiteUser['user']['publicProfileUrl']) ? $socialiteUser['user']['publicProfileUrl'] : null);
            $user->createSocialAccount($provider,$socialiteUser['id'],$socialiteUser['avatar_original'],$socialiteUser['token'],$socialiteUserLink,false,$socialiteUser['user']);
            return $this->sendResponse();
        }
        else
        {
            $socialiteUserLink = isset($socialiteUser['user']['link']) ? $socialiteUser['user']['link']:(isset($socialiteUser['user']['publicProfileUrl']) ? $socialiteUser['user']['publicProfileUrl'] : null);
            \App\Profile::where('id',$request->user()->profile->id)->update([$provider.'_url'=>$socialiteUserLink]);
            return $this->sendError("Already link ".$provider." with our platform");
        }

    }

    public function feedIssue(Request $request)
    {
        $this->model = \DB::table("app_info")->where("profile_id",$request->user()->profile->id)
                            ->where('fcm_token',$request->input('fcm_token'))->update(['is_active'=>0]);
        return $this->sendResponse();
    }



}
