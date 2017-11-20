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

Route::post('login',function(Request $request){
    $credentials = $request->only('email','password');
//    $userVerified = \App\Profile\User::where('email',$credentials['email'])->whereNull('verified_at')->first();
//    if($userVerified)
//    {
//        return response()->json(['error' => 'Please verify your email address'], 401);
//    }
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
// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

//has prefix api/ - defined in RouteServiceProvider.php
Route::group(['namespace'=>'Api', 'as' => 'api.' //note the dot.
    ],function(){

    //unauthenticated routes.
        Route::post('/user/register',['uses'=>'UserController@register']);
        Route::post('/user/fcmToken',['uses'=>'UserController@fcmToken']);
        Route::get('/user/verify/email/{token}', 'UserController@verify');
        Route::get("profile/images/{id}.jpg",['as'=>'profile.image','uses'=>'ProfileController@image']);
        Route::get("profile/hero/{id}.jpg",['as'=>'profile.heroImage','uses'=>'ProfileController@heroImage']);

    //authenticated routes.
        Route::group(['middleware'=>'api.auth'],function(){

            //change password
                Route::post("change/password","UserController@changePassword");
            //chat
                Route::get("chatrooms","ChatController@rooms");
                Route::post("chats/{chatId}/members/addAdmin",'Chat\\MemberController@addAdmin');
                Route::post("chats/{chatId}/messages/{id}/markRead",'Chat\\MessageController@markRead');
                Route::resource("chats/{chatId}/members",'Chat\\MemberController');
                Route::resource("chats/{chatId}/messages",'Chat\\MessageController');
                Route::resource("chats","ChatController");
    
            //product categories
                Route::resource("categories","CategoryController");
            
            //share
                Route::post("share/{modelname}/{id}/like",'ShareLikeController@store');
                Route::post("share/{modelName}/{id}",'ShareController@store');
                Route::delete("share/{modelName}/{id}",'ShareController@delete');
                Route::get("share/{modelname}/{id}/like",'ShareLikeController@index');

            //shoutouts
                Route::resource("shoutout",'ShoutoutController');
                 Route::group(['prefix'=>'shoutout/{shoutoutId}'],function(){
                        Route::resource("like",'ShoutoutLikeController');
                });

            //invites
            Route::post("invites","InviteController@invite");
            //company rating
            Route::get("companies/{companyId}/rating","CompanyRatingController@getRating");
            Route::post("companies/{companyId}/rating","CompanyRatingController@rating");
            
            //channel names for socket.io
                Route::get('channels/companies/{id}/public',function($id){
                    return response()->json(['company.public.' . $id]);
                });
                Route::get('channels/{id}/public',function($id){
                    return response()->json(['public.' . $id]);
                });
                Route::get('channels',function(Request $request){
                    $profileId = $request->user()->profile->id;
                    return response()->json(\App\Channel::names($profileId));
                });

            //feedback
                Route::resource("feedback","FeedbackController");


            //feeds
                Route::get("feed",'FeedController@feed');

            //people who like post

                Route::get("peopleLiked/{modelname}/{id}","LikeController@peopleLiked");
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
                //collaborate categories
                Route::resource("collaborate/categories","CollaborateCategoryController");
                
                //collaborate templates
                 Route::resource("collaborate/templates","CollaborateTemplateController");
        
                //collaborates shortlist
                    Route::get("collaborate/shortlisted","CollaborateController@shortlisted");
                    Route::post("collaborate/{id}/shortlist","CollaborateController@shortlist");
                    
                //collaborate
                    Route::get("collaborate/all","CollaborateController@all");
                    Route::get("collaborate/filters","CollaborateController@filters");
                    Route::post("collaborate/{id}/like","CollaborateController@like");
                    Route::get("collaborate/{id}/applications","CollaborateController@applications");
                    Route::get("collaborate/{id}/archived","CollaborateController@archived");
                    Route::post("collaborate/{id}/apply","CollaborateController@apply");
                    Route::resource("collaborate/{collaborateId}/fields",'CollaborationFieldController');
                    Route::resource("collaborate","CollaborateController");
    
                //collaborate comments
                    Route::group(['namespace'=>'Collaborate','prefix'=>'collaborate/{collaborateId}','as'=>'collaborate.'],function(){
                        Route::resource('comments','CommentController');
                    });

            //photos
                Route::resource("photos","PhotoController");
                
            //recipes rating
                Route::post("recipes/{recipeId}/rate","RecipeRatingController@rate");

            //recipes
                Route::get('recipes/image/{id}','RecipeController@recipeImages');
            Route::get("recipes/filters", "RecipeController@filters");
            Route::get("recipes/properties", "RecipeController@properties");
            Route::resource("recipes","RecipeController");

            //tagboard
                Route::resource('tagboards','TagBoardController');

            //tag
                Route::post("tag/{tagboardId}/{relationship}/{relationshipId}/note","TagController@updateNote");
                Route::post("tag/{tagboardId}/{relationship}/{relationshipId}","TagController@tag");
            
            //comments
                Route::get('comments/{model}/{modelId}','CommentController@index');
                Route::post('comments/{model}/{modelId}','CommentController@store');
                Route::delete('comments/{id}','CommentController@destroy');
            
            //search
                Route::get("search/{type?}",'SearchController@search')->middleware('search.save');
                Route::get("autocomplete/filter/{model}/{key}",'SearchController@filterAutoComplete');
                Route::get("autocomplete",'SearchController@autocomplete');
                
            //history
                Route::get("history/{type}","HistoryController@history");
            //Route::post('like/{model}/{modelId}','LikeController@store');
            
            //notifications
                Route::get('notifications/unread','NotificationController@unread');
                Route::post("notifications/read/{id}",'NotificationController@read');
                Route::post("notifications/markAllAsRead","NotificationController@markAllAsRead");
                Route::resource("notifications",'NotificationController');

            //designations
            Route::get("designations", "DesignationController@index");

            //notification is read or not
            Route::post('update/{modelName}/{id}','UpdateController@isRead');
            //get all notification of particular profile
            Route::get('update','UpdateController@index');


            //profile routes
            //remove when profile/tagging api run proper on website and app
            //website all followers
            Route::get("profile/allFollowerslist",['uses'=>'ProfileController@oldtagging']);
            //app all followers
            Route::get("profile/allFollowers",['uses'=>'ProfileController@allFollowers']);


            Route::get("profile/tagging",['uses'=>'ProfileController@tagging']);
            Route::post('profile/follow',['uses'=>'ProfileController@follow']);
            Route::post('profile/unfollow',['uses'=>'ProfileController@unfollow']);
            Route::get('profile/{id}/followers',['uses'=>'ProfileController@followers']);
            Route::get("profile/{id}/mutualFollowers",['uses'=>'ProfileController@mutualFollowers']);
            Route::get('profile/{id}/following',['uses'=>'ProfileController@following']);
            Route::get("profile/{id}/recent",['uses'=>'ProfileController@recentUploads']);
            Route::get('/people','ProfileController@all');
            Route::get("people/filters", "ProfileController@filters");
//            Route::post("profile/filters", "ProfileController@filtersData");
            Route::resource('profile','ProfileController');

            //company filter
            Route::get("companies/filters", "CompanyController@filters");
            Route::resource('companies','CompanyController');

            //recipes cuisine
            Route::resource("cuisine",'CuisineController');
            //namespace profile
            Route::group(['namespace'=>'Profile','prefix'=>'profiles/{profileId}','as'=>'profile.','middleware'=>'api.checkProfile'], function(){
                //Route::resource('albums','AlbumController');
                Route::post("recipes/{id}/like","RecipeController@like");
                Route::resource("recipes","RecipeController");
                
                Route::post("collaborate/{id}/approve","CollaborateController@approve");
                Route::post("collaborate/{id}/reject","CollaborateController@reject");
                Route::get("collaborate/interested","CollaborateController@interested");
                Route::get("collaborate/expired","CollaborateController@expired");
                Route::post("collaborate/{id}/reopen","CollaborateController@reopen");
                Route::resource("collaborate","CollaborateController");
    
                Route::post("jobs/{id}/apply", "JobController@apply");
                Route::post("jobs/{id}/unapply", "JobController@unapply");
                Route::get('jobs/{id}/applications', 'JobController@applications');
                Route::post("jobs/{id}/applications/{shortlistedProfileId}/shortlist","JobController@shortlist");
                Route::get("jobs/applied","JobController@applied");
                Route::get("jobs/expired","JobController@expired");
                Route::post("jobs/{id}/reopen","JobController@reopen");

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
    
                //namespace company - Checks for company admin
                Route::group(['namespace'=>'Company','prefix'=>'companies/{companyId}','as'=>'companies.','middleware'=>'api.CheckCompanyAdmin'],function(){
                    Route::resource("websites","WebsiteController");
                    //Route::resource("blogs","BlogController");
                    Route::resource("advertisements","AdvertisementController");
                    Route::resource("addresses","AddressController");
                    Route::resource("books","BookController");
                    Route::resource("patents","PatentController");
                    Route::resource("awards","AwardController");
                    Route::post("coreteam/ordering","CoreteamController@ordering");
                    Route::resource("coreteam","CoreteamController");
                    Route::resource("affiliations","AffiliationController");
                    Route::resource("gallery","GalleryController");

                    Route::resource("catalogue","CompanyCatalogueController");
                    Route::resource("products/catalogue","ProductCatalogueController");
                    
                    Route::post("collaborate/{id}/approve","CollaborateController@approve");
                    Route::post("collaborate/{id}/reject","CollaborateController@reject");
                    Route::get("collaborate/expired","CollaborateController@expired");
                    Route::get("collaborate/interested","CollaborateController@interested");
                    Route::post("collaborate/{id}/reopen","CollaborateController@reopen");
                    Route::resource("collaborate","CollaborateController");

                    
                    Route::get('photo/{id}.jpg',['as'=>'photos.image','uses'=>'PhotoController@image']);
    
                    Route::resource('photos','PhotoController');
                    Route::group(['namespace'=>'Photo','prefix'=>'photos/{photoId}','as'=>'comments.'],function(){
                        Route::resource('comments','CommentController');
                        Route::resource('like','PhotoLikeController');
                    });
                    
                    Route::resource("portfolio","PortfolioController");
                    
                    Route::get('jobs/{id}/applications', 'JobController@applications');
                    Route::post("jobs/{id}/applications/{shortlistedProfileId}/shortlist","JobController@shortlist");
                    Route::get("jobs/expired","JobController@expired");
                    Route::post("jobs/{id}/reopen","JobController@reopen");
                    Route::resource("jobs","JobController");
                    Route::resource("products","ProductController");
                    Route::resource("users","UserController");
                });
    
                //Company namespace - Does not check for company admin
                Route::group(['namespace'=>'Company','prefix'=>'companies/{companyId}','as'=>'companies.'],function(){
                    Route::post("jobs/{id}/apply", "JobController@apply");
                    Route::post("jobs/{id}/unapply", "JobController@unapply");
                });
                
                Route::resource('tagboards','TagBoardController');
                Route::post("tagboards/{id}/like","TagBoardController@like");

                Route::resource("experiences","ExperienceController");
                Route::resource("books","BookController");
                Route::resource("shows","ShowController");
                Route::resource("projects","ProjectController");
                Route::resource("awards","AwardController");
                Route::resource("education","EducationController");
                Route::resource("patents","PatentController");
                Route::resource("certifications","CertificationController");
                Route::resource("professional","ProfessionalController");
                Route::resource("affiliations","AffiliationController");
                Route::resource("trainings","TrainingController");


            });
//            Route::resource('albums','AlbumController');
//            Route::resource('photos','PhotoController');
//            Route::resource("books","BookController");
//            Route::resource("shows","ProfileShowController");
//            Route::resource("projects","ProjectController");
//            Route::resource("experiences","ExperienceController");
//            Route::resource("awards","AwardController");
//            Route::resource("certifications","CertificationController");
    
            
            Route::post("/preview",function(Request $request){
                $url = $request->input('url');
                $tags = \App\Preview::get($url);
                
                return response()->json(['data'=>$tags,'errors'=>[],'messages'=>null]);
            });
            
            Route::get('@{handle}','HandleController@show');
    
            Route::get("apk_version",function(){
                $version = \App\Version::getVersion();
                return response()->json($version);
            });
    
            Route::post("apk_version",function(Request $request){
                $version = \App\Version::setVersion($request->input('compatible_version'),$request->input('latest_version'));
                return response()->json($version);
            });
            
        }); // end of authenticated routes. Add routes before this line to be able to
            // get current logged in user.
});
