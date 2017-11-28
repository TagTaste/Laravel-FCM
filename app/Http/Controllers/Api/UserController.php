<?php

namespace App\Http\Controllers\Api;

use App\Events\EmailVerification;
use App\Profile;
use App\User;
use GuzzleHttp\Client;
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
        $result = ['status'=>'success'];
        if($request->input("invite_code"))
        {
            $invitation = \App\Invitation::where('invite_code', $request->input("invite_code"))
                        ->where('email',$request->input('user.email'))->first();
            if(!$invitation)
            {
                return $this->sendError("please use correct invite code");
            }
            $accepted_at = \Carbon\Carbon::now()->toDateTimeString();
            $invitation->update(["accepted_at"=>$accepted_at,'state'=>\App\Invitation::$registered]);
            $alreadyVerified = true;
            $profileId = $invitation->profile_id;
        }
        $user = \App\Profile\User::addFoodie($request->input('user.name'),$request->input('user.email'),$request->input('user.password'),$alreadyVerified);
        $result['result'] = ['user'=>$user,'token'=>  \JWTAuth::attempt(
            ['email'=>$request->input('user.email')
                ,'password'=>$request->input('user.password')])];
        
        if(!$alreadyVerified)
        {
            $mail = (new \App\Jobs\EmailVerification($user))->onQueue('emails');
            \Log::info('Queueing Verified Email...');

            dispatch($mail);
        }
        else
        {
            $profiles = \App\Profile::with([])->where('id',$profileId)->orWhere('user_id',$user->id)->get();

            $loginProfile = $profiles[0]->user_id == $user->id ? $profiles[0] : $profiles[1];
            $profile = $profiles[0]->user_id != $user->id ? $profiles[0] : $profiles[1];
            event(new JoinFriend($profile , $loginProfile));
        }
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
        if($user)
        {
            $this->model = \DB::table("app_info")->insert(["profile_id"=>$request->user()->profile->id,'fcm_token'=>$request->input('fcm_token')]);
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
        $this->model = \DB::table("app_info")->where('fcm_token',$request->input('fcm_token'))->delete();
        return $this->sendResponse();
    }
}
