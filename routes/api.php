<?php

use Illuminate\Support\Facades\Route;
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

Route::group(['namespace'=>'Api\Company','prefix'=>'meta/'],function(){
    Route::resource('statuses','StatusController');
    Route::resource('types','TypeController');
});
Route::get('privacy','Api\PrivacyController@index');
//has prefix api/ - defined in RouteServiceProvider.php
Route::group(['namespace'=>'Api', 'as' => 'api.' //note the dot.
    ],function(){
    
    //unauthenticated routes.
        Route::post('/user/register',['uses'=>'UserController@register']);
        Route::get("profile/images/{id}.jpg",['as'=>'profile.image','uses'=>'ProfileController@image']);
        Route::get("profile/hero/{id}.jpg",['as'=>'profile.heroImage','uses'=>'ProfileController@heroImage']);

    //authenticated routes.
        Route::group(['middleware'=>'api.auth'],function(){
            //collaborate templates
            Route::resource("collaborate/templates","CollaborateTemplateController");
            
            //shoutouts
            Route::post("shoutout/{id}/like","ShoutoutController@like");
            Route::resource("shoutout",'ShoutoutController');
                 Route::group(['prefix'=>'shoutout/{shoutoutId}'],
                    function()
                    {
                        Route::resource("like",'ShoutoutLikeController');
                });
           
            
            //channel names for socket.io
                Route::get('channels',function(Request $request){
                    $profileId = $request->user()->profile->id;
                    return response()->json(\App\Channel::names($profileId));
                });
            
            //feeds
                Route::get("feed",'FeedController@feed');
                Route::get("feed/public",'FeedController@public');
                //is the network feed required?
                //what does it mean?
                //Route::get("feed/network",'FeedController@network');
            
            Route::get('profile/{id}',['uses'=>'ProfileController@show']);
    
            Route::get("jobs/filters", "JobController@filters");
            Route::resource("jobs","JobController");
            Route::get("similar/{relationship}/{relationshipId}",'SimilarController@similar');
            Route::post("collaborate/{id}/apply","CollaborateController@apply");
            Route::resource("collaborate","CollaborateController");
            Route::group(['namespace'=>'Collaborate','prefix'=>'collaborate/{collaborateId}','as'=>'collarabote.'],function(){
                Route::resource('comments','CommentController');
            });
            Route::get('recipes/image/{id}','RecipeController@recipeImages');
            Route::resource("recipes","RecipeController");
            
            Route::post("tag/{tagboardId}/{relationship}/{relationshipId}/note","TagController@updateNote");
            Route::post("tag/{tagboardId}/{relationship}/{relationshipId}","TagController@tag");
    
            Route::get('comments/{model}/{modelId}','CommentController@index');
            Route::post('comments/{model}/{modelId}','CommentController@store');
            
            Route::post('like/{model}/{modelId}','LikeController@store');
            
            Route::get('notifications/unread','NotificationController@unread');
            Route::post("notifications/read/{id}",'NotificationController@read');
            Route::resource("notifications",'NotificationController');

            Route::get("designations", "DesignationController@index");
            Route::resource('profile','ProfileController');
            Route::post('profile/follow',['uses'=>'ProfileController@follow']);
            Route::post('profile/unfollow',['uses'=>'ProfileController@unfollow']);

            //namespace profile
            Route::group(['namespace'=>'Profile','prefix'=>'profiles/{profileId}','as'=>'profile.','middleware'=>'api.checkProfile'], function(){
                //Route::resource('albums','AlbumController');
                Route::post("recipes/{id}/like","RecipeController@like");
                Route::resource("recipes","RecipeController");
                
                Route::post("collaborate/{id}/approve","CollaborateController@approve");
                Route::post("collaborate/{id}/reject","CollaborateController@reject");
                Route::resource("collaborate","CollaborateController");

                Route::get('photo/{id}.jpg',['as'=>'photos.image','uses'=>'PhotoController@image']);

                Route::resource('photos','PhotoController');
                Route::group(['namespace'=>'Photo','prefix'=>'photos/{photoId}','as'=>'comments.'],function(){
                    Route::resource('comments','CommentController');
                    Route::resource('like','PhotoLikeController');
                });
                
                Route::resource('companies','CompanyController');
                Route::get("companies/{id}/logo.jpg",['as'=>'company.logo','uses'=>'CompanyController@logo']);
                Route::get("companies/{id}/hero_image.jpg",['as'=>'company.heroImage','uses'=>'CompanyController@heroImage']);
    
                //namespace company
                Route::group(['namespace'=>'Company','prefix'=>'companies/{companyId}','as'=>'companies.'],function(){
                    Route::resource("websites","WebsiteController");
                    //Route::resource("blogs","BlogController");
                    Route::resource("advertisements","AdvertisementController");
                    Route::resource("addresses","AddressController");
                    Route::resource("books","BookController");
                    Route::resource("patents","PatentController");
                    Route::resource("awards","AwardController");
    
                    Route::post("collaborate/{id}/approve","CollaborateController@approve");
                    Route::post("collaborate/{id}/reject","CollaborateController@reject");
                    Route::resource("collaborate","CollaborateController");

                    
                    Route::get('photo/{id}.jpg',['as'=>'photos.image','uses'=>'PhotoController@image']);
    
                    Route::resource('photos','PhotoController');
                    Route::group(['namespace'=>'Photo','prefix'=>'photos/{photoId}','as'=>'comments.'],function(){
                        Route::resource('comments','CommentController');
                    });
                    
                    Route::resource("portfolio","PortfolioController");
                    Route::post("jobs/{id}/shortlist/{shortlistedProfileId}","JobController@shortlist");
                    Route::post("jobs/{id}/apply", "JobController@apply");
                    Route::post("jobs/{id}/unapply", "JobController@unapply");
                    Route::get('jobs/{id}/applications', 'JobController@applications');
                    Route::resource("jobs","JobController");
                    Route::resource("products","ProductController");
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

            //Route::resource('tagboard','TagBoardController');
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