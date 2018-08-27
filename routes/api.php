<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\SearchClient;
use Maatwebsite\Excel\Facades\Excel;



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
            return response()->json(['error' => 'invalid_credentials','message'=>'The username or password is incorrect.'], 401);
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

// Preview routes
Route::get('preview/{modelName}/{modelId}','Api\PreviewController@show');
Route::get('preview/{modelName}/{modelId}/shared/{shareId}','Api\PreviewController@showShared');
Route::get("/public/seeall/{modelName}",'PublicViewController@seeall');
Route::get('public/{modelName}/{modelId}','PublicViewController@modelView');
Route::get('public/similar/{modelName}/{modelId}','PublicViewController@similarModelView');
Route::get('public/{modelName}/{modelId}/shared/{shareId}','PublicViewController@modelSharedView');

//has prefix api/ - defined in RouteServiceProvider.php
Route::group(['namespace'=>'Api', 'as' => 'api.' //note the dot.
    ],function(){
       
        Route::post('/verifyInviteCode','UserController@verifyInviteCode');
    //unauthenticated routes.
        Route::post('/user/register',['uses'=>'UserController@register']);
        Route::get("profile/images/{id}.jpg",['as'=>'profile.image','uses'=>'ProfileController@image']);
        Route::get("profile/hero/{id}.jpg",['as'=>'profile.heroImage','uses'=>'ProfileController@heroImage']);
    //newsletter
    Route::post('newsletters','NewsletterController@store');

    Route::get('/user/verify/email/{token}', 'UserController@verify');

    //authenticated routes.
        Route::middleware(['api.auth','optimizeImages'])->group(function(){
            Route::post('/user/fcmToken',['uses'=>'UserController@fcmToken']);
            Route::post('/user/feedIssue',['uses'=>'UserController@feedIssue']);
            Route::post('/logout','UserController@logout');
            Route::post('/user/verify/phone','UserController@phoneVerify');

            Route::post('/user/requestOtp','UserController@requestOtp');

            /**
             * Route to update user invite code, this roiute will be mostly used by the admin dashboard
             */
            Route::post('/user/updateInviteToken','UserController@updateInviteToken');

            Route::get('social/link/{provider}','UserController@socialLink');

            Route::get('suggestion/{modelName}','SuggestionEngineController@suggestion');
            Route::post('suggestion/{modelName}','SuggestionEngineController@suggestionIgonre');

            Route::group(['namespace'=>'V1','prefix'=>'v1/','as'=>'v1.'],function() {
                Route::get("feed",'FeedController@feed');
                Route::group(['namespace'=>'Profile','prefix'=>'profiles/{profileId}','as'=>'profile.','middleware'=>'api.checkProfile'], function(){
                    Route::resource("photos","PhotoController");
                    Route::resource("collaborate","CollaborateController");
                    Route::group(['namespace'=>'Company','prefix'=>'companies/{companyId}','as'=>'companies.','middleware'=>'api.CheckCompanyAdmin'],function(){
                        Route::resource("collaborate/{id}/scopeOfReview","CollaborateController@scopeOfReview");
                        Route::resource("collaborate","CollaborateController");
                    });


                });
            });
            //change password
                Route::post("change/password","UserController@changePassword");



            //chat
                Route::post('chatMessage',"ChatController@chatMessage");
                Route::post('chatShareMessage',"ChatController@chatShareMessage");
                Route::get('chatGroup',"ChatController@chatGroup");
                Route::get("chatrooms","ChatController@rooms");
                Route::post("chats/{chatId}/members/addAdmin",'Chat\\MemberController@addAdmin');
                Route::post("chats/{chatId}/members/removeAdmin",'Chat\\MemberController@removeAdmin');
                Route::post("chats/{chatId}/messages/{id}/markRead",'Chat\\MessageController@markRead');
                Route::resource("chats/{chatId}/members",'Chat\\MemberController');
                Route::resource("chats/{chatId}/messages",'Chat\\MessageController');
                Route::resource("chats","ChatController");
    
            //product categories
                Route::resource("categories","CategoryController");
            
            //share
                Route::post("share/{modelname}/{id}/like",'ShareLikeController@store');
                Route::post("share/{modelName}/{id}",'ShareController@store');
                Route::get("share/{modelName}/{id}/{modelId}",'ShareController@show');
                Route::delete("share/{modelName}/{id}",'ShareController@delete');
                Route::get("share/{modelname}/{id}/like",'ShareLikeController@index');

            //send mail to applicants of job or collaborate
            Route::post("{feature}/{featureId}/message","ChatController@featureMessage");

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

            //search api without admin
            Route::get("companies/{companyId}/getUserWithoutAdmin","CompanyController@getUserWithoutAdmin");
            
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

            //get premium companies

            Route::get("profile/premium","ProfileController@getPremium");
            
            //jobs
                Route::get("jobs/all","JobController@all");
                Route::get("jobs/filters", "JobController@filters");
                Route::resource("jobs","JobController");
                //Route::post("jobs/message","ChatController@jobMessage");
            
            //similar
                Route::get("similar/{relationship}/{relationshipId}",'SimilarController@similar');
            
            //fields for collaboration
                Route::resource("fields",'FieldController');
            
            //collaborate
                //collaborate categories
                Route::resource("collaborate/categories","CollaborateCategoryController");
                Route::get('collaborate/types',"CollaborateController@types");
                Route::get('batchesColor',"CollaborateController@batchesColor");


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
                    Route::post("uploadImage","CollaborateController@uploadImageCollaborate");
                    Route::delete("deleteImages","CollaborateController@deleteImages");
                    Route::resource("collaborate","CollaborateController");


                    //product review related api

                    Route::get("userBatches","CollaborateController@userBatches");
                    Route::post("seenBatchesList","CollaborateController@seenBatchesList");
                    Route::get("tastingMethodology","CollaborateController@tastingMethodology");
                    Route::get("profilesJobs","CollaborateController@profilesJobs");
                    Route::get("profilesSpecialization","CollaborateController@profilesSpecialization");


            Route::group(['namespace'=>'Collaborate','prefix'=>'collaborate/{collaborateId}','as'=>'collaborate.'],function(){
                Route::get("userBatches",'BatchController@userBatches');
                Route::post("beginTasting",'BatchController@beginTasting');
                Route::get("batches/{id}/currentStatus",'BatchController@getCurrentStatus');
                Route::post('removeFromBatch','BatchController@removeFromBatch');
                Route::post('assignBatch','BatchController@assignBatch');
                Route::get("batches/{id}/getShortlistedPeople","BatchController@getShortlistedPeople");
                Route::get("batches/{id}/getShortlistedSearchPeople","BatchController@getShortlistedSearchPeople");
                //reports
                Route::get("batches/{id}/headers/{headerId}/reports","BatchController@reports");
                Route::get("batches/{id}/headers/{headerId}/questions/{questionId}/comments","BatchController@comments");

                    //filter for dashboard of product review
                Route::get("dashboard/filters","BatchController@filters");

                Route::resource('batches','BatchController');
                Route::post('shortlistPeople','ApplicantController@shortlistPeople');
                Route::post('rejectPeople','ApplicantController@rejectPeople');
                Route::post('inviteForReview','ApplicantController@inviteForReview'); //not need
                Route::post('acceptInvitation','ApplicantController@acceptInvitation');
                Route::post('rejectInvitation','ApplicantController@rejectInvitation');// make api as show interested
                Route::post("showInterest","ApplicantController@store");
                Route::get("getShortlistApplicants","ApplicantController@getShortlistApplicants");
                Route::get("getRejectApplicants","ApplicantController@getRejectApplicants");
                Route::get("getInvitedApplicants","ApplicantController@getInvitedApplicants");
                Route::get("getUnassignedApplicants","ApplicantController@getUnassignedApplicants");
                Route::resource('collaborateApplicants','ApplicantController');
                // api for product-review tasting
                Route::get("headers/{id}/question/{questionId}/search","QuestionController@getNestedOptionSearch");
                Route::get("headers/{id}/question/{questionId}","QuestionController@getNestedQuestions");
                Route::post("headers/{headerId}","ReviewController@reviewAnswers");
                Route::get("headers/{id}","QuestionController@reviewQuestions");
                Route::get("headers","QuestionController@headers");
                Route::post("insertHeaders","QuestionController@insertHeaders");
                Route::post("insertHeaders/{id}/insertQuestions","QuestionController@insertQuestions");
                Route::post("headers/{headerId}/insertQuestion/{id}/aroma","QuestionController@aromQuestions");

                //collaborate comments

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
                Route::get('comments/{id}/{modelName}/{modelId}','CommentController@notificationComment');
                Route::post('comments/{model}/{modelId}','CommentController@store');
                Route::post('comments/{id}/{modelName}/{modelId}','CommentController@update');
                Route::delete('comments/{id}','CommentController@destroy');
                Route::delete('comments/{id}/{modelName}/{modelId}','CommentController@commentDelete');
                Route::get('comment/tagging','CommentController@tagging');


            //search
                Route::get("filterSearch/{type?}",'SearchController@filterSearch');
                Route::get("search/{type?}",'SearchController@search')->middleware('search.save');
                Route::get("autocomplete/filter/{model}/{key}",'SearchController@filterAutoComplete');
                Route::get("autocomplete",'SearchController@autocomplete');
                Route::get("searchForApp/{type?}",'SearchController@searchForApp');
                //history
                Route::get("history/{type}","HistoryController@history");
            //Route::post('like/{model}/{modelId}','LikeController@store');
            
            //notifications
                Route::post('notifications/{type}/seen','NotificationController@seen');
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


            /// ------- Settings Routes -------

            Route::get('settings', 'SettingController@showProfile');
            Route::get('settings/company/{id}', 'SettingController@showCompany');

            Route::post('settings', 'SettingController@store');

            /// ---- End Settings Routes ------


            //profile routes

            //phone verify
            Route::post('profile/phoneVerify','ProfileController@phoneVerify');
            Route::post('profile/requestOtp','ProfileController@requestOtp');
            Route::post('profile/verify/email','ProfileController@sendVerifyMail');
            //remove when profile/tagging api run proper on website and app
            //website all followers
            Route::get("profile/allFollowerslist",['uses'=>'ProfileController@oldtagging']);
            //app all followers
            Route::get("profile/allFollowers",['uses'=>'ProfileController@allFollowers']);

            // facebook friends
            Route::get("profile/facebookFriends", ['uses'=> 'ProfileController@fbFriends']);
            Route::post("profile/followFbFriends", ['uses'=> 'ProfileController@followFbFriends']);


            //check handle
//            Route::post("profile/handleAvailable", ['uses'=>'ProfileController@handleAvailable']);

            Route::get("profile/tagging",['uses'=>'ProfileController@tagging']);
            Route::post('profile/follow',['uses'=>'ProfileController@follow']);
            Route::post('profile/unfollow',['uses'=>'ProfileController@unfollow']);
            Route::get('profile/{id}/followers',['uses'=>'ProfileController@followers']);
            Route::get("profile/{id}/mutualFollowers",['uses'=>'ProfileController@mutualFollowers']);
            Route::get('profile/{id}/following',['uses'=>'ProfileController@following']);
            Route::get("profile/{id}/recent",['uses'=>'ProfileController@recentUploads']);
            Route::get('/people','ProfileController@all');
            Route::get('/people/onboarding','ProfileController@onboarding');
            Route::get("people/filters", "ProfileController@filters");
//            Route::post("profile/filters", "ProfileController@filtersData");
            Route::resource('profile','ProfileController');


            // onboarding routes
            Route::get('onboarding/skills', 'OnboardingController@skills');
            Route::get('onboarding/autocomplete/skills', 'OnboardingController@autoCompleteSkills');

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
                Route::resource("collaborate","CollaborateController");
    
                Route::post("jobs/{id}/apply", "JobController@apply");
                Route::post("jobs/{id}/unapply", "JobController@unapply");
                Route::get('jobs/{id}/applications', 'JobController@applications');
                Route::post("jobs/{id}/applications/{shortlistedProfileId}/shortlist","JobController@shortlist");
                Route::get("jobs/applied","JobController@applied");
                Route::get("jobs/expired","JobController@expired");

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

                Route::resource("shippingAddress","ShippingAddressController");
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
    
//            Route::get("apk_version",function(){
//                $version = \App\Version::getVersion();
//                return response()->json($version);
//            });
//
//            Route::post("apk_version",function(Request $request){
//                $version = \App\Version::setVersion($request->input('compatible_version'),$request->input('latest_version'));
//                return response()->json($version);
//            });

            Route::get('apk_version', 'VersionController@getAndroidVersion');
            Route::post('apk_version', 'VersionController@setAndroidVersion');
            Route::get('ios_version', 'VersionController@getIosVersion');
            Route::post('ios_version', 'VersionController@setIosVersion');

        }); // end of authenticated routes. Add routes before this line to be able to
            // get current logged in user.

            Route::get("csv/college",function (Request $request){
                $this->model = [];
                $studentDetail = \DB::table("education")
                ->select(\DB::raw('COUNT(*) as count, college'))
                ->groupBy("college")
                ->get();

                // ->select("education.profile_id as profile_id", "users.name as name","users.email as email","profiles.phone as phoneNo")
                //     ->join('profiles','profiles.id','=','education.profile_id')
                //     ->join('users','users.id','=','profiles.user_id')
                //     ->where('education.ongoing','=',1)
                //     ->orWhere('education.end_date','like','%2018%')
                //     ->whereNull('profiles.deleted_at')
                //     ->groupBy('profiles.id')
                //     ->get();
                   
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file_colleges.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );
        
                $columns = array('count','college');
        
                $str = '';
                foreach ($columns as $c) {
                    $str = $str.$c.',';
                }
                $str = $str."\n";
        
                foreach($studentDetail as $review) {
                    foreach ($columns as $c) {
                        $str = $str.$review->{$c}.',';
                    }
                    $str = $str."\n";
                }
       
                return response($str, 200, $headers);
        
            });


            Route::get("csv/designation",function (Request $request){
                $this->model = [];
                $studentDetail = \DB::table("experiences")
                ->select(\DB::raw('COUNT(*) as count, designation, GROUP_CONCAT(company) as companies'))
                ->orderBy("designation","desc")
                ->groupBy("designation")
                ->get();
                   
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file_designations.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );
        
                $columns = array('count','designation','companies');
        
                $str = '';
                foreach ($columns as $c) {
                    $str = $str.$c.',';
                }
                $str = $str."\n";
        
                foreach($studentDetail as $review) {
                    foreach ($columns as $c) {
                        $str = $str.$review->{$c}.',';
                    }
                    $str = $str."\n";
                }
       
                return response($str, 200, $headers);
        
            });

            Route::get("csv/language",function (Request $request){
                $this->model = [];
                $studentDetail = \DB::table("profile_filters")
                ->select(\DB::raw('COUNT(*) as count, value as language'))
                ->where('key','=','language')
                ->groupBy("value")
                ->get();
                   
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file_language.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );
        
                $columns = array('count','language');
        
                $str = '';
                foreach ($columns as $c) {
                    $str = $str.$c.',';
                }
                $str = $str."\n";
        
                foreach($studentDetail as $review) {
                    foreach ($columns as $c) {
                        $str = $str.$review->{$c}.',';
                    }
                    $str = $str."\n";
                }
       
                return response($str, 200, $headers);
        
            });

            Route::get("csv/skill",function (Request $request){
                $this->model = [];
                $studentDetail = \DB::table("profile_filters")
                ->select(\DB::raw('COUNT(*) as count, value as skill'))
                ->where('key','=','skills')
                ->groupBy("value")
                ->get();
                   
                $headers = array(
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=file_skills.csv",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0"
                );
        
                $columns = array('count','skill');
        
                $str = '';
                foreach ($columns as $c) {
                    $str = $str.$c.',';
                }
                $str = $str."\n";
        
                foreach($studentDetail as $review) {
                    foreach ($columns as $c) {
                        $str = $str.$review->{$c}.',';
                    }
                    $str = $str."\n";
                }
       
                return response($str, 200, $headers);
        
            });


});
