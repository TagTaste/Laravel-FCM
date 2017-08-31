<?php

namespace App\Http\Controllers\Api;

use App\Company\Coreteam;
use App\Events\Chat\Invite;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\EmailVerification;

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
            $inviteCodeCheck = \App\Invitation::where('invite_code', $request->input("invite_code"))
                        ->where('email',$request->input('user.email'))->first();
            if(!$inviteCodeCheck)
            {
                return $this->sendError("please use correct invite code");
            }
            $accepted_at = \Carbon\Carbon::now()->toDateTimeString();
            $inviteCodeCheck->update(["accepted_at"=>$accepted_at]);

            $alreadyVerified = true;
        }
        $user = \App\Profile\User::addFoodie($request->input('user.name'),$request->input('user.email'),$request->input('user.password'));
        $result['result'] = ['user'=>$user];

        if(!$alreadyVerified)
        {
            event(new EmailVerification($user));
        }

        return $this->sendResponse();
    }

    public function verify(Request $request,$token)
    {
        $user = User::where("email_token", $token)->first();
        if($user)
        {
            $user->verified_at = Carbon::now()->toDateTimeString();;
            $this->model = $user->save();
            return $this->sendResponse();

        }
    }
}
