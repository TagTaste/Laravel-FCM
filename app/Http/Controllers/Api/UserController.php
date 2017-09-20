<?php

namespace App\Http\Controllers\Api;

use App\Company\Coreteam;
use App\Events\Chat\Invite;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\EmailVerification;
use Illuminate\Support\Facades\Hash;

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
            $invitation->update(["accepted_at"=>$accepted_at]);

            $alreadyVerified = true;
        }
        $user = \App\Profile\User::addFoodie($request->input('user.name'),$request->input('user.email'),$request->input('user.password'),$alreadyVerified);
        $result['result'] = ['user'=>$user];

        if(!$alreadyVerified)
        {
            $mail = (new \App\Jobs\EmailVerification($user))->onQueue('emails');
            \Log::info('Queueing Verified Email...');

            dispatch($mail);
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
}
