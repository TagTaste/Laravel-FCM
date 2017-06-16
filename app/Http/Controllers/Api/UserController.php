<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function register(Request $request)
    {
        if(!$request->has('user')){
            $this->sendError("Missing user data.");
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

        $user = \App\User::addFoodie($request->input('user.name'),$request->input('user.email'),$request->input('user.password'));
        $result['result'] = ['user'=>$user];

        return response()->json($result);
    }
}
