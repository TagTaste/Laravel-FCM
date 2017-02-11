<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All API controllers are namespaced to App\Http\Controllers\Api,
| have their route alias start with "api.",
| and reside in App\Http\Controllers\Api folder. Duh.
|
*/

use Illuminate\Support\Facades\Route;

//has prefix api/
Route::group(['namespace'=>'Api',
    'as' => 'api.' //note the dot.
    ],function(){


    //unauthenticated routes.
        Route::post('/user/register',['uses'=>'UserController@register']);
        Route::get("profile/images/{id}.jpg",['as'=>'profile.image','uses'=>'ProfileController@image']);
        Route::get("profile/hero/{id}.jpg",['as'=>'profile.heroImage','uses'=>'ProfileController@heroImage']);
        Route::get('profile/{id}',['uses'=>'ProfileController@show']);


        Route::group(['namespace'=>'Profile','prefix'=>'profile/{profileId}','as'=>'profile.','middleware'=>'api.checkProfile'], function(){
            Route::resource('albums','AlbumController');
            Route::group(['namespace'=>'Album','prefix'=>'albums/{albumId}'],function(){
                Route::get('photo/{id}.jpg',['as'=>'photos.image','uses'=>'PhotoController@apiImage']);
                Route::resource('photos','PhotoController');
            });
            Route::resource('companies','CompanyController');

            Route::resource('tagboards','TagBoardController');
            Route::resource("experiences","ExperienceController");

        });


    Route::resource('companies','CompanyController');

    //authenticated routes.
        Route::group(['middleware'=>'api.auth'],function(){

            Route::resource('profile','ProfileController');
            Route::get('dish/image/{id}','DishController@dishImages');
            Route::post('profile/follow',['uses'=>'ProfileController@follow']);



            Route::resource('albums','AlbumController');
            Route::resource('photos','PhotoController');
            Route::resource('company','CompanyController');
            Route::resource('tagboard','TagBoardController');

            Route::resource("books","ProfileBookController");
            Route::resource("shows","ProfileShowController");
            Route::resource("projects","ProjectController");
            Route::resource("experiences","ExperienceController");
            Route::resource("awards","AwardController");
            Route::resource("certifications","CertificationController");
        });
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