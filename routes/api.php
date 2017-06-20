<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
            //categories
                Route::resource("categories","CategoryController");
            
            //share
                Route::post("share/{modelName}/{id}",'ShareController@store');
                Route::delete("share/{modelName}/{id}",'ShareController@delete');
                Route::post("share/{modelname}/{id}/like",'ShareLikeController@store');
                Route::get("share/{modelname}/{id}/like",'ShareLikeController@index');

            //shoutouts
                Route::resource("shoutout",'ShoutoutController');
                 Route::group(['prefix'=>'shoutout/{shoutoutId}'],function(){
                        Route::resource("like",'ShoutoutLikeController');
                });
           
            
            //channel names for socket.io
                Route::get('channels/{id}/public',function($id){
                    return response()->json(['public.' . $id]);
                });
                Route::get('channels',function(Request $request){
                    $profileId = $request->user()->profile->id;
                    return response()->json(\App\Channel::names($profileId));
                });
            
            //feeds
                Route::get("feed",'FeedController@feed');
                Route::get("like",'LikeController@like');
                Route::get("feed/{profileId}",'FeedController@public');
                Route::get("feed/companies/{companyId}",'FeedController@company');
                //is the network feed required?
                //what does it mean?
                //Route::get("feed/network",'FeedController@network');
            
            //jobs
                Route::get("jobs/all","JobController@all");
                Route::get("jobs/filters", "JobController@filters");
                Route::resource("jobs","JobController");
            
            //similar
                Route::get("similar/{relationship}/{relationshipId}",'SimilarController@similar');
            
            //fields for collaboration
                Route::resource("fields",'FieldController');
            
            //collaborate
            
                //collaborate templates
                 Route::resource("collaborate/templates","CollaborateTemplateController");
        
                //collaborates shortlist
                    Route::get("collaborate/shortlisted","CollaborateController@shortlisted");
                    Route::post("collaborate/{id}/shortlist","CollaborateController@shortlist");
                    
                //collaborate
                    Route::get("collaborate/all","CollaborateController@all");
                    Route::get("collaborate/filters","CollaborateController@filters");
                    Route::post("collaborate/{id}/like","CollaborateController@like");
                    Route::post("collaborate/{id}/apply","CollaborateController@apply");
                    Route::resource("collaborate/{collaborateId}/fields",'CollaborationFieldController');
                    Route::resource("collaborate","CollaborateController");
    
                //collaborate comments
                    Route::group(['namespace'=>'Collaborate','prefix'=>'collaborate/{collaborateId}','as'=>'collaborate.'],function(){
                        Route::resource('comments','CommentController');
                    });
            
            //recipes
                Route::get('recipes/image/{id}','RecipeController@recipeImages');
                Route::resource("recipes","RecipeController");
                
            //tag
                Route::post("tag/{tagboardId}/{relationship}/{relationshipId}/note","TagController@updateNote");
                Route::post("tag/{tagboardId}/{relationship}/{relationshipId}","TagController@tag");
            
            //comments
                Route::get('comments/{model}/{modelId}','CommentController@index');
                Route::post('comments/{model}/{modelId}','CommentController@store');
                Route::delete('comments/{id}','CommentController@destroy');
            
            //search
                Route::get("search/{type?}",'SearchController@search');
                Route::get("suggest/{type}",'SearchController@suggest');
            //Route::post('like/{model}/{modelId}','LikeController@store');
            
            //notifications
                Route::get('notifications/unread','NotificationController@unread');
                Route::post("notifications/read/{id}",'NotificationController@read');
                Route::resource("notifications",'NotificationController');

            //designations
            Route::get("designations", "DesignationController@index");

            //notification is read or not
            Route::post('update/{modelName}/{id}','UpdateController@isRead');
            //get all notification of particular profile
            Route::get('update/{id}','UpdateController@show');


            //profile routes
            
            Route::post('profile/follow',['uses'=>'ProfileController@follow']);
            Route::post('profile/unfollow',['uses'=>'ProfileController@unfollow']);
            Route::get('profile/{id}/followers',['uses'=>'ProfileController@followers']);
            Route::get('profile/{id}/following',['uses'=>'ProfileController@following']);
           
            Route::get('/profiles','ProfileController@all');
            Route::resource('profile','ProfileController');
            
            Route::resource('companies','CompanyController');
    
            //namespace profile
            Route::group(['namespace'=>'Profile','prefix'=>'profiles/{profileId}','as'=>'profile.','middleware'=>'api.checkProfile'], function(){
                //Route::resource('albums','AlbumController');
                Route::post("recipes/{id}/like","RecipeController@like");
                Route::resource("recipes","RecipeController");
                
                Route::post("collaborate/{id}/approve","CollaborateController@approve");
                Route::post("collaborate/{id}/reject","CollaborateController@reject");
                Route::resource("collaborate","CollaborateController");
    
                Route::post("jobs/{id}/apply", "JobController@apply");
                Route::post("jobs/{id}/unapply", "JobController@unapply");
                Route::get('jobs/{id}/applications', 'JobController@applications');
                Route::post("jobs/{id}/applications/{shortlistedProfileId}/shortlist","JobController@shortlist");
    
                Route::resource("jobs","JobController");
                
                Route::get('photo/{id}.jpg',['as'=>'photos.image','uses'=>'PhotoController@image']);
                Route::resource('photos','PhotoController');
                Route::group(['namespace'=>'Photo','prefix'=>'photos/{photoId}','as'=>'comments.'],function(){
                    Route::resource('comments','CommentController');
                    Route::resource('like','PhotoLikeController');
                });
                
                Route::post('companies/{id}/follow','CompanyController@follow');
                Route::post('companies/{id}/unfollow','CompanyController@unfollow');
                Route::get('companies/{id}/followers','CompanyController@followers');
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

                    Route::resource("catalogue","CompanyCatalogueController");


                    Route::post("collaborate/{id}/approve","CollaborateController@approve");
                    Route::post("collaborate/{id}/reject","CollaborateController@reject");
                    Route::resource("collaborate","CollaborateController");

                    
                    Route::get('photo/{id}.jpg',['as'=>'photos.image','uses'=>'PhotoController@image']);
    
                    Route::resource('photos','PhotoController');
                    Route::group(['namespace'=>'Photo','prefix'=>'photos/{photoId}','as'=>'comments.'],function(){
                        Route::resource('comments','CommentController');
                    });
                    
                    Route::resource("portfolio","PortfolioController");
                    Route::post("jobs/{id}/apply", "JobController@apply");
                    Route::post("jobs/{id}/unapply", "JobController@unapply");
                    Route::get('jobs/{id}/applications', 'JobController@applications');
                    Route::post("jobs/{id}/applications/{shortlistedProfileId}/shortlist","JobController@shortlist");
    
                    Route::resource("jobs","JobController");
                    Route::resource("products","ProductController");
                    Route::resource("users","UserController");
                });
    
                Route::resource('tagboards','TagBoardController');
                Route::post("tagboards/{id}/like","TagBoardController@like");

                Route::resource("experiences","ExperienceController");
                Route::resource("books","BookController");
                Route::resource("shows","ShowController");
                Route::resource("projects","ProjectController");
                Route::resource("experiences","ExperienceController");
                Route::resource("awards","AwardController");
                Route::resource("certifications","CertificationController");
                Route::resource("professional","ProfessionalController");
               
            });
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

Route::get('social/login/{provider}', 'Auth\LoginController@handleProviderCallback');

Route::get('{handle}','Api\HandleController@show');