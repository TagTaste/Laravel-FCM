<?php

namespace App\Http\Controllers\Api;

use App\Company\Coreteam;
use App\Events\Chat\Invite;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        if($request->input("invite"))
        {
            $check = \App\Invitation::where('invite_code', $request->input("invite"))->first();
            if($check)
            {
                if($check->email!=$request->input('user.email'))
                {
                    return $this->sendError("please use correct emailid");
                }
                $accepted_at = \Carbon\Carbon::now()->toDateTimeString();
                \App\Invitation::where('email',$request->input('user.email'))->update(['accepted'=>1,"accepted_at"=>$accepted_at]);
            }
            else
            {
                return $this->sendError("please use correct invite code");

            }
        }
        $user = \App\Profile\User::addFoodie($request->input('user.name'),$request->input('user.email'),$request->input('user.password'));
        $result['result'] = ['user'=>$user];

        return response()->json($result);
    }
}
