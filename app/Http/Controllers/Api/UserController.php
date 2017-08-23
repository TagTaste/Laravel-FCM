<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Events\SendVerificationEmail;

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


        $result = ['status'=>'success'];

        $user = \App\Profile\User::addFoodie($request->input('user.name'),$request->input('user.email'),
            $request->input('user.password'));
        $result['result'] = ['user'=>$user];

        dispatch(new SendVerificationEmail($user));

        return view("verification");

//        return response()->json($result);
    }
    public function verify(Request $request,$token)
    {
        $user = User::where("email_token", $token)->first();
        if($user)
        {
            $user->verified_at = Carbon::now()->toDateTimeString();;
            $user->save();
            return view("emailconfirm", ["user" => $user]);

        }
    }
}
