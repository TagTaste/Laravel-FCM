<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware'=>'jwt.auth'],function(){
    Route::get('/profile', function (Request $request) {
        return $request->user()->get();
    });
});

Route::post('/user/create',function(Request $request){
    //return response()->json($request->only('user'));
    return response()->json(['status'=>'success','result'=> ['user'=>\App\User::create($request->input('user'))]]);
    });

Route::post('login',function(Request $request){
    $credentials = $request->only('email','password');

    try {
        // attempt to verify the credentials and create a token for the user
        if (! $token = \JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
        // something went wrong whilst attempting to encode the token
        return response()->json(['error' => 'could_not_create_token'], 500);
    }

    return response()->json(compact('token'));

});
