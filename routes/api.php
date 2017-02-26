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
Route::group(['namespace'=>'Api\Company','prefix'=>'meta/'],function(){
    Route::resource('statuses','StatusController');
    Route::resource('types','TypeController');
});
//has prefix api/
Route::group(['namespace'=>'Api',
    'as' => 'api.' //note the dot.
    ],function(){


    //unauthenticated routes.
        Route::post('/user/register',['uses'=>'UserController@register']);
        Route::get("profile/images/{id}.jpg",['as'=>'profile.image','uses'=>'ProfileController@image']);
        Route::get("profile/hero/{id}.jpg",['as'=>'profile.heroImage','uses'=>'ProfileController@heroImage']);
        Route::get('profile/{id}',['uses'=>'ProfileController@show']);

    //why is this here? remove if it's unneeded.
    //Route::resource('companies','CompanyController');

    //authenticated routes.
        Route::group(['middleware'=>'api.auth'],function(){

            Route::resource('profile','ProfileController');
            Route::get('dish/image/{id}','DishController@dishImages');
            Route::post('profile/follow',['uses'=>'ProfileController@follow']);

            //namespace profile
            Route::group(['namespace'=>'Profile','prefix'=>'profiles/{profileId}','as'=>'profile.','middleware'=>'api.checkProfile'], function(){

                Route::resource('albums','AlbumController');
                //namespace albums
                Route::group(['namespace'=>'Album','prefix'=>'albums/{albumId}'],function(){

                    Route::get('photo/{id}.jpg',['as'=>'photos.image','uses'=>'PhotoController@apiImage']);

                    Route::resource('photos','PhotoController');
                    Route::group(['namespace'=>'Photo','prefix'=>'photos/{photoId}','as'=>'comments.'],function(){
                        Route::resource('comments','CommentController');
                    });
                });


                Route::resource('companies','CompanyController');
                //namespace company
                Route::group(['namespace'=>'Company','prefix'=>'companies/{companyId}','as'=>'companies.'],function(){
                    Route::resource("websites","WebsiteController");
                    Route::resource("blogs","BlogController");
                    Route::resource("advertisements","AdvertisementController");
                    Route::resource("addresses","AddressController");
                    Route::resource("books","BookController");
                    Route::resource("patents","PatentController");
                    Route::resource("awards","AwardController");
                    Route::resource("albums","AlbumController");

                });
                Route::resource('tagboards','TagBoardController');
                Route::resource("experiences","ExperienceController");
                Route::resource("books","BookController");
                Route::resource("shows","ShowController");
                Route::resource("projects","ProjectController");
                Route::resource("experiences","ExperienceController");
                Route::resource("awards","AwardController");
                Route::resource("certifications","CertificationController");
                Route::resource("professional","ProfessionalController");

            });

            //Route::resource('company','CompanyController');

            Route::resource('tagboard','TagBoardController');
//            Route::resource('albums','AlbumController');
//            Route::resource('photos','PhotoController');
//            Route::resource("books","BookController");
//            Route::resource("shows","ProfileShowController");
//            Route::resource("projects","ProjectController");
//            Route::resource("experiences","ExperienceController");
//            Route::resource("awards","AwardController");
//            Route::resource("certifications","CertificationController");
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