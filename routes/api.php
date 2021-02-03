<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\SearchClient;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;



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

Route::group(['namespace'=>'Api\Company','prefix'=>'meta/'], function() {
    Route::resource('statuses','StatusController');
    Route::resource('types','TypeController');
});
Route::get('privacy','Api\PrivacyController@index');
Route::post('mail/tieReport','GeneralMailController@tieReportMail');
Route::post('mail/foodComposition','GeneralMailController@foodCompositionMail');
Route::post('login',function(Request $request) {
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
Route::post('social/login/auth/linkedin', 'Auth\LoginController@loginLinkedin');
Route::get('social/login/{provider}', 'Auth\LoginController@handleProviderCallback');
Route::get('/cities', 'Auth\LoginController@getCities');
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
Route::get("public/product/{modelId}/food-shot","PublicViewController@foodShot");
Route::get('public/similar/{modelName}/{modelId}','PublicViewController@similarModelView');
Route::get('public/{modelName}/{modelId}/shared/{shareId}','PublicViewController@modelSharedView');
Route::get('preview/chefOfTheWeek', 'Api\PreviewController@showChefProfile');

// faq question answer api
Route::get('categoriesQuestionAnswer/{id}','FaqsController@categoriesQuestionAnswer');
Route::get('allCategories','FaqsController@allCategories');
Route::post('storeCategories','FaqsController@storeCategories');
Route::resource('faqs','FaqsController');

// career email controller
Route::post('career-mail','CareerEmailController@careerMail');

/**
 * Route to report content and profile password.
 */
Route::get('/sitemap.xml', 'SitemapController@index');
Route::get('/sitemap-products.xml', 'SitemapController@publicReviewProducts');
Route::get('/sitemap-profiles.xml', 'SitemapController@profiles');
Route::get('/sitemap-companies.xml', 'SitemapController@companies');
Route::get('/sitemap-shoutout.xml', 'SitemapController@shoutouts');
Route::get('/sitemap-photos.xml', 'SitemapController@photos');
Route::get('/sitemap-collaborations.xml', 'SitemapController@collaborations');
Route::get('/sitemap-polling.xml', 'SitemapController@polls');
Route::get('/sitemap-shared-photos.xml', 'SitemapController@sharedPhotos');
Route::get('/sitemap-shared-collaborations.xml', 'SitemapController@sharedCollaborations');
Route::get('/sitemap-shared-products.xml', 'SitemapController@sharedProducts');
Route::get('/sitemap-shared-pollings.xml', 'SitemapController@sharedPolls');
Route::get('/sitemap-shared-shoutout.xml', 'SitemapController@sharedShoutouts');
Route::get('/sitemap-miscellaneous.xml', 'SitemapController@miscellaneous');

// unsubscribe
/**
 * this will do both subscribe and unsubscribe just pass subscribe or 
 * unsubscribe with the hash value you received and reason id for the 
 * reason of unsubscribe.
 */
Route::get("settingUpdate/{type}","SettingController@updateSetting");

/**
 * to get or insert the reason for unsubscribe to the table. use the get
 * route to recieve the resons for unsubscribe with id and post route to insert 
 * the resons as per your requirement.
 */
Route::get("unsubscribe/reason","SettingController@getUnSubscribeReason");

/**
 * this route will post the reason for the particular user for unsubscribe 
 * just pass the hash value and the reason id you received from the above 
 * route.
 */
Route::post("unsubscribe/reason","SettingController@reasonUnsubscribe");

/**
 * has prefix api/ - defined in RouteServiceProvider.php
 * note the dot.
 */
Route::group(['namespace'=>'Api', 'as' => 'api.' ], function() {
    Route::post('/verifyInviteCode','UserController@verifyInviteCode');
    
    /**
     * Unauthenticated routes.
     */
    Route::post('/user/register',['uses'=>'UserController@register']);
    Route::get("profile/images/{id}.jpg",['as'=>'profile.image','uses'=>'ProfileController@image']);
    Route::get("profile/hero/{id}.jpg",['as'=>'profile.heroImage','uses'=>'ProfileController@heroImage']);
    
    /**
     * newsletter.
     */
    Route::post('newsletters','NewsletterController@store');
    Route::get('/user/verify/email/{token}', 'UserController@verify');

    /**
     * Authenticated routes.
     */
    Route::middleware(['api.auth','optimizeImages'])->group(function(){
        Route::post('/user/fcmToken',['uses'=>'UserController@fcmToken']);
        Route::post('/user/device/info',['uses'=>'UserController@getApkDeviceInfo']);
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
        /**
         * Routes for hashtag
         */
        Route::get('/hashtag/suggestions', 'HashtagController@suggestions');
        Route::get('/hashtag/trending', 'HashtagController@trending');
        Route::get('/hashtag/feed', 'HashtagController@feed');
        Route::group(['namespace'=>'V2','prefix'=>'v2/','as'=>'v2.'],function() {
            //multiple photos api
            Route::resource("photos","PhotoController");

            // review page api
            Route::get("public-review/explore","ExploreController@exploreForReview");
            Route::get("collection/{collectionId}","ExploreController@getCollection");
            Route::get("collection/{collectionId}/elements","ExploreController@getCollectionElements");
            
            Route::resource("shoutout",'ShoutoutController');
            Route::resource("polling","PollingController");
            Route::resource("collaborate","CollaborateController");
            Route::post("campus-connect","CampusConnectController@store");
            Route::get("share/{modelName}/{id}/{modelId}",'ShareController@show');
            Route::get("feed","FeedController@feed");
            Route::get("feed/{modelName}/{modelId}/{device}/{interactionTypeId}","FeedController@feedInteraction");
            Route::get('suggestion/profile','SuggestionEngineController@suggestionProfile');
            Route::get('suggestion/company','SuggestionEngineController@suggestionCompany');
            Route::get('ad_engine','SuggestionEngineController@adEngineList');

            //namespace profile
            Route::group(['namespace'=>'Profile','prefix'=>'profiles/{profileId}','as'=>'profile.','middleware'=>'api.checkProfile'], function() {
                //namespace company - Checks for company admin
                Route::group(['namespace'=>'Company','prefix'=>'companies/{companyId}','as'=>'companies.','middleware'=>'api.CheckCompanyAdmin'],function(){
                    Route::resource("users","UserController");
                });
            });

            Route::get("search/explore","ExplorePageController@explore");
            Route::get("search_test/explore_test","ExplorePageController@exploreTest");
        });
        //Routes to get personalised meta
        Route::get("/meta/{modelName}/{modelId}","MetaController@getMeta");
        Route::get("/meta/{modelName}/{id}/{modelId}","MetaController@getSharedMeta");
        Route::group(['namespace'=>'V1','prefix'=>'v1/','as'=>'v1.'], function() {
            Route::post("{feature}/{featureId}/message","ChatController@featureMessage");
            Route::get("chatGroup",'ChatController@chatGroup');
            Route::post("chatShareMessage",'ChatController@shareAsMessage');
            Route::get("chatDisconnect",'ChatController@disconnect');
            Route::get("getChatId",'ChatController@getChatId');
            Route::get("chats/search",'ChatController@chatSearch');
            Route::get("chatRoom",'ChatController@rooms');
            Route::post("shareAsMessage",'ChatController@shareAsMessage');
            Route::get("chats/{chatId}/chatInfo",'ChatController@chatInfo');
            Route::resource("chats","ChatController");
            Route::group(['namespace'=>'Chat','prefix'=>'chats/{chatId}','as'=>'chats.'], function() {
                Route::get("getMembersToSearch","MemberController@getMembersToSearch");
                Route::get("getMembersToAdd","MemberController@getMembersToAdd");
                Route::post("members/addAdmin",'MemberController@addAdmin');
                Route::post("members/removeAdmin",'MemberController@removeAdmin');
                Route::delete("clearMessages",'MessageController@clearMessages');
                Route::post("uploadFile",'MessageController@uploadFile');
                Route::post("markAsRead",'MessageController@markAsRead');
                Route::delete("deleteChat",'MessageController@deleteChat');
                Route::delete("deleteMessage","MessageController@deleteMessage");
                Route::resource("messages","MessageController");
                Route::resource("members","MemberController");
            });

            Route::get("feed",'FeedController@feed');
            //search apis new
            Route::get("public-review/explore","SearchController@exploreForReview");
            // Route::get("search/new/explore","SearchController@newExplore");
            Route::get("search/explore","SearchController@newExplore");
            Route::get("search/discover",'SearchController@discover');
            Route::get("search/{type?}",'SearchController@search');
            Route::get("search/specialization/{id}",'SearchController@searchSpecializationPeople');
            Route::group(['namespace'=>'Profile','prefix'=>'profiles/{profileId}','as'=>'profile.','middleware'=>'api.checkProfile'], function() {
                Route::resource("photos","PhotoController");
                Route::get("collaborate/draft","CollaborateController@draft");
                Route::get('collaborate/{collaborateId}/allSubmissions', 'CollaborateController@allSubmissions');
                Route::post('collaborate/{collaborateId}/updateSubmissionStatus', 'CollaborateController@updateSubmissionStatus');
                Route::post("collaborate/{id}/close","CollaborateController@collaborateClose");
                Route::get('collaborate/{collaborateId}/allSubmissions/{userId}', 'CollaborateController@allSubmissions');
                Route::post('collaborate/{collaborateId}/updateSubmissionStatus', 'CollaborateController@updateSubmissionStatus');
                Route::post("collaborate/{id}/scopeOfReview","CollaborateController@scopeOfReview");
                Route::post("collaborate/{id}/uploadQuestion","CollaborateController@uploadQuestion");
                Route::resource("collaborate","CollaborateController");
                Route::post('collaborate/{collaborateId}/assignRole', 'CollaborateController@assignRole');
                Route::get('collaborate/{collaborateId}/roles', 'CollaborateController@getRoles');
                Route::delete('collaborate/{collaborateId}/deleteRoles','CollaborateController@deleteRoles');
                Route::get('collaborate/{collaborateId}/getRole','CollaborateController@getProfileRole');
                Route::get("collaborate/{id}/cities","CollaborateController@getCities");
                Route::get("collaborate/{id}/cities/{cityId}/outlets","CollaborateController@getOutlets");
                Route::get("collaborate/{id}/cities/{cityId}/outlets/{addressId}","CollaborateController@outletStatus");
                Route::group(['namespace'=>'Company','prefix'=>'companies/{companyId}','as'=>'companies.','middleware'=>'api.CheckCompanyAdmin'],function(){
                    Route::post('collaborate/{collaborateId}/assignRole', 'CollaborateController@assignRole');
                    Route::post("collaborate/{id}/scopeOfReview","CollaborateController@scopeOfReview");
                    Route::post("collaborate/{id}/uploadQuestion","CollaborateController@uploadQuestion");
                    Route::get("collaborate/{id}/cities","CollaborateController@getCities");
                    Route::get("collaborate/{id}/cities/{cityId}/outlets","CollaborateController@getOutlets");
                    Route::get("collaborate/{id}/cities/{cityId}/outlets/{addressId}","CollaborateController@outletStatus");
                    Route::post("collaborate/{id}/close","CollaborateController@collaborateClose");
                    Route::get("collaborate/draft","CollaborateController@draft");
                    Route::get('collaborate/{collaborateId}/allSubmissions', 'CollaborateController@allSubmissions');
                    Route::post('collaborate/{collaborateId}/updateSubmissionStatus', 'CollaborateController@updateSubmissionStatus');
                    Route::resource("collaborate","CollaborateController");
                    Route::resource('photos','PhotoController');
                    Route::get('collaborate/{collaborateId}/roles', 'CollaborateController@getRoles');
                    Route::get('collaborate/{collaborateId}/allSubmissions/{userId}', 'CollaborateController@allSubmissions');
                    Route::post('collaborate/{collaborateId}/updateSubmissionStatus', 'CollaborateController@updateSubmissionStatus');
                    Route::delete('collaborate/{collaborateId}/deleteRoles','CollaborateController@deleteRoles');
                    Route::get('collaborate/{collaborateId}/getRole','CollaborateController@getProfileRole'); 
                });
            });
        });

        /**
         * Route to report content and profile password.
         */
        Route::get('report-type/{reportCategory}',"ReportController@getReportTypeList");
        Route::post('report-content',"ReportController@reportContent");
        Route::post('report-user',"ReportController@reportUser");


        /**
         * Route to change password.
         */
        Route::post("change/password","UserController@changePassword");

        // polling
        Route::post('polling/{id}/like','PollingController@like');
        Route::post('polling/{id}/vote','PollingController@userPollVote');
        Route::post('polling/{id}/renew','PollingController@renew');
        Route::resource('polling','PollingController');
        Route::post('polling/{id}/updateOptions/{optionId}','PollingController@updateOptions');
        Route::post('polling/{id}/addOption','PollingController@addOption');
        Route::delete('polling/{id}/deleteOptions','PollingController@deleteOptions');

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
        Route::get("share/product/{id}/like",'ShareLikeController@productLikeIndex');
        Route::post("share/product/{id}/like", 'ShareLikeController@productLikeStore');
        Route::post("share/{modelname}/{id}/like",'ShareLikeController@store');
        Route::post("share/product/{id}",'ShareController@productStore');
        Route::post("share/{modelName}/{id}",'ShareController@store');
        Route::get("share/product/{id}/{modelId}", 'ShareController@showProduct');
        Route::get("share/{modelName}/{id}/{modelId}",'ShareController@show');
        Route::delete("share/product/{id}",'ShareController@productShareDelete');
        Route::delete("share/{modelName}/{id}",'ShareController@delete');
        Route::get("share/{modelname}/{id}/like",'ShareLikeController@index');
        Route::patch("share/product/{id}", 'ShareController@productUpdate');
        Route::patch("share/{modelname}/{id}", 'ShareController@update');

        //send mail to applicants of job or collaborate
        Route::post("{feature}/{featureId}/message","ChatController@featureMessage");

        //shoutouts
        Route::resource("feed_card",'FeedCardController');

        //shoutouts
        Route::resource("shoutout",'ShoutoutController');
        Route::group(['prefix'=>'shoutout/{shoutoutId}'], function() {
            Route::resource("like",'ShoutoutLikeController');
        });

        //global image upload function
        Route::post("globalImageUpload","PhotoController@globalImageUpload");

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
        Route::post('profile/{id}/update-details',['uses'=>'ProfileController@updateDetails']);
        Route::post('company/{id}/update-details',['uses'=>'CompanyController@updateDetails']);

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
        Route::get("collaboration/mandatoryFields", "CollaborateController@dynamicMandatoryFields");
        Route::get("collaborate/{id}/mandatoryFields", "CollaborateController@collaborationMandatoryFields");
        Route::get("mandatoryField/{type}","CollaborateController@mandatoryField");
        Route::resource("collaborate/categories","CollaborateCategoryController");
        Route::get('collaborate/types',"CollaborateController@types");
        Route::get('batchesColor',"CollaborateController@batchesColor");//->middleware('permissionCollaborate');

        //collaborate templates
        Route::resource("collaborate/templates","CollaborateTemplateController");

        //collaborates shortlist
        Route::get("collaborate/shortlisted","CollaborateController@shortlisted");
        Route::post("collaborate/{id}/shortlist","CollaborateController@shortlist");
        Route::get("collaborate/{id}/applicantFilters","CollaborateController@applicantFilters");
        //collaborate
        Route::get("collaborate/all","CollaborateController@all");
        Route::get("collaborate/filters","CollaborateController@filters");
        Route::post("collaborate/{id}/like","CollaborateController@like");
        Route::get("collaborate/{id}/applications","CollaborateController@applications");
        Route::get("collaborate/{id}/archived","CollaborateController@archived");
        Route::get("collaborate/{id}/archived/export","CollaborateController@archivedExport");
        Route::post("collaborate/{id}/apply","CollaborateController@apply");
        Route::patch("collaborate/{id}/addAddress","CollaborateController@addAddress");
        Route::post("collaborate/{id}/contestSubmission","CollaborateController@contestSubmission");
        Route::get("collaborate/{id}/getSubmissions","CollaborateController@getSubmissions");
        Route::resource("collaborate/{collaborateId}/fields",'CollaborationFieldController');
        Route::post("uploadImage","CollaborateController@uploadImageCollaborate");
        Route::post("uploadBrandLogo","CollaborateController@uploadBrandLogo");
        Route::delete("deleteImages","CollaborateController@deleteImages");
        Route::resource("collaborate","CollaborateController");

        //product review related api
        Route::get("userBatches","CollaborateController@userBatches");
        Route::post("seenBatchesList","CollaborateController@seenBatchesList");
        Route::get("tastingMethodology","CollaborateController@tastingMethodology");
        Route::get("profilesOccupations","CollaborateController@profilesJobs");
        Route::get("profilesSpecialization","CollaborateController@profilesSpecialization");
        Route::get("profilesCategory","CollaborateController@profilesCategory");
        Route::get("profilesAllergens","CollaborateController@profilesAllergens");
        Route::post("uploadGlobalNestedOption","CollaborateController@uploadGlobalNestedOption");
        Route::post("uploadGlobalQuestion","CollaborateController@uploadGlobalQuestion");
        Route::get("globalQuestion/{id}","CollaborateController@globalQuestionParticular");
        Route::get("globalQuestion","CollaborateController@globalQuestion");
        Route::get("getCities","CollaborateController@getCities");
        Route::post("addCities","CollaborateController@addCities");
        Route::get("collaborateCloseReason","CollaborateController@collaborateCloseReason");


        Route::group(['namespace'=>'Collaborate','prefix'=>'collaborate/{collaborateId}','as'=>'collaborate.'],function() {
            //Route::group(['middleware' => ['permissionCollaborate']], function () {
                Route::get("userBatches",'BatchController@userBatches');
                Route::put("batches/{batchId}/foodBillStatus",'BatchController@foodBillStatus');
                Route::post("beginTasting",'BatchController@beginTasting');//required
                Route::get("batches/{id}/currentStatus",'BatchController@getCurrentStatus');
                Route::post('removeFromBatch','BatchController@removeFromBatch');//required
                Route::post('assignBatch','BatchController@assignBatch');//required 
                Route::get("batches/{id}/getShortlistedPeople","BatchController@getShortlistedPeople");
                Route::get("batches/{id}/getShortlistedSearchPeople","BatchController@getShortlistedSearchPeople");

                //reports
                Route::get("batches/{id}/getPRProfile","BatchController@getPRProfile");//->middleware('permissionCollaborate');
                Route::get("batches/{id}/reportPdf","BatchController@reportPdf");//->middleware('permissionCollaborate');
                Route::get("reportSummary","BatchController@reportSummary");
                Route::get("batches/{id}/reportHeader","QuestionController@reportHeader");
                Route::get("batches/{id}/headers/{headerId}/reports","BatchController@reports");//->middleware('permissionCollaborate');
                Route::get("batches/{id}/headers/{headerId}/questions/{questionId}/options", "BatchController@optionReports");
                Route::get("batches/{id}/headers/{headerId}/questions/{questionId}/options/{optionId}", "BatchController@optionIdReports");
                Route::get("batches/{id}/headers/{headerId}/questions/{questionId}/comments","BatchController@comments");
                Route::get("getHeaderWeight","BatchController@getHeaderWeight")->middleware('permissionCollaborate');
                Route::post("storeHeaderWeight","BatchController@storeHeaderWeight");//->middleware('permissionCollaborate');

                //filter for dashboard of product review
                Route::get("dashboard/filters","BatchController@filters");//->middleware('permissionCollaborate');
                Route::get("dashboard/report/filters","BatchController@reportFilters")->middleware('permissionCollaborate');
                Route::get("batches/hutCsv","BatchController@allHutCsv");
                Route::get("batches/{id}/hutCsv","BatchController@hutCsv");
                Route::resource('batches','BatchController');
                Route::post('shortlistPeople','ApplicantController@shortlistPeople')->middleware('permissionCollaborate');
                Route::post('rejectPeople','ApplicantController@rejectPeople')->middleware('permissionCollaborate'); 
                Route::post('inviteForReview','ApplicantController@inviteForReview');//not need
                Route::post('rejectInvitation','ApplicantController@rejectInvitation');// make api as show interested
                Route::post("rejectDocument","ApplicantController@rejectDocument");//api to reject document of applicant
                Route::post("acceptDocument", "ApplicantController@acceptDocument");
                Route::get("getShortlistApplicants","ApplicantController@getShortlistApplicants");
                Route::get("getRejectApplicants","ApplicantController@getRejectApplicants");//->middleware('permissionCollaborate');
                Route::get("getRejectApplicants/export","ApplicantController@getRejectApplicantsExport");
                Route::get("getInvitedApplicants","ApplicantController@getInvitedApplicants");//->middleware('permissionCollaborate');
                Route::get("getUnassignedApplicants","ApplicantController@getUnassignedApplicants");//->middleware('permissionCollaborate');
                Route::get("getApplicantFilter","ApplicantController@getApplicantFilter");//->middleware('permissionCollaborate');
            //});

            Route::post("showInterest","ApplicantController@store")->middleware('iosCollaborate');
            Route::get("cities/{cityId}/outlets","ApplicantController@getOutlets");
            Route::get("cities","ApplicantController@getCities");
            Route::get('collaborateApplicants/export','ApplicantController@export');//->
            Route::resource('collaborateApplicants','ApplicantController');//->middleware('permissionCollaborate');
            Route::post('acceptInvitation','ApplicantController@acceptInvitation');
            // api for product-review tasting
            Route::get("headers/{id}/question/{questionId}/search","QuestionController@getNestedOptionSearch");
            Route::get("headers/{id}/question/{questionId}/searchNestedParent","QuestionController@getNestedOptionSearchNestedParent");
            Route::get("headers/{id}/question/{questionId}","QuestionController@getNestedQuestions");
            Route::post("headers/{headerId}","ReviewController@reviewAnswers");
            Route::get("headers/{id}","QuestionController@reviewQuestions");
            Route::get("headers","QuestionController@headers");//->middleware('permissionCollaborate');
            Route::post("insertHeaders","QuestionController@insertHeaders");
            Route::post("insertHeaders/{id}/insertQuestions","QuestionController@insertQuestions");
            Route::post("headers/{headerId}/insertQuestion/{id}/aroma","QuestionController@aromQuestions");

            //collaborate comments
            Route::resource('comments','CommentController');
        });

        Route::group(['namespace'=>'PublicReviewProduct','prefix'=>'public-review/products/{productId}','as'=>'collaborate.'], function() {
            //reports
            Route::get("headers/reports","ReportController@reportHeaders");
            Route::get("reportPdf","ReportController@reportPdf");
            Route::get("reportSummary","ReportController@reportSummary");
            Route::get("headers/{headerId}/questions/{questionId}/option/{optionId}","ReportController@getAnswer");
            Route::get("headers/{headerId}/reports","ReportController@reports");
            Route::get("headers/{headerId}/questions/{questionId}/comments","ReportController@comments");
            Route::get("headers/{headerId}/questions/{questionId}/anyother", "ReportController@anyother");

            // api for product-review tasting
            Route::get("headers/{id}/question/{questionId}/search","QuestionController@getNestedOptionSearch");
            Route::get("headers/{id}/question/{questionId}/searchNestedParent","QuestionController@getNestedOptionSearchNestedParent");
            Route::get("headers/{id}/question/{questionId}","QuestionController@getNestedQuestions");
            Route::post("headers/{headerId}/review","ReviewController@reviewAnswers");
            Route::get("headers/{id}","QuestionController@reviewQuestions");
            Route::get("headers","QuestionController@headers");

            //collaborate comments
            Route::get('reviews/{reviewId}/comments',"ReviewController@comments");
            Route::post('reviews/{reviewId}/comments',"ReviewController@commentsPost");
            Route::delete('reviews/{reviewId}/comments/{commentId}',"ReviewController@commentsDelete");

            //get review
            Route::get("reviews/{id}","ReviewController@show");
            Route::get("reviews","ReviewController@index");
            Route::get("food-shot","ReviewController@foodShot");
            Route::get("filters","ReviewController@reviewFilter");
        });

        Route::post('products/suggestion','PublicReviewProductController@productSuggestion');
        Route::get('public-review/products/filters','PublicReviewProductController@getFilters');
        Route::get('public-review/products/{productId}/get-sample','PublicReviewProductController@getSample');
        Route::get('public-review/similarProducts/{productId}', 'PublicReviewProductController@similarProducts');

        Route::get("public-review/discover/products","PublicReviewProductController@discover");
        Route::get("public-review/category/{id}/products","PublicReviewProductController@categoryProducts");
        Route::post("uploadImageProduct","PublicReviewProductController@uploadImageProduct");
        Route::post("public-review/uploadGlobalNestedOption","PublicReviewProductController@uploadGlobalNestedOption");
        Route::post("public-review/products/{id}/uploadImageProduct","PublicReviewProductController@uploadImageProduct");
        Route::resource('public-review/products', 'PublicReviewProductController');
        Route::post('public-review/createFilters',"PublicReviewProductController@createFilters");

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
        // Route::get("filterSearch/product",'SearchController@productFilterSearch');
        Route::get("filterSearch/{type?}",'SearchController@filterSearch');

        Route::get("search/{type?}",'SearchController@search');
        Route::get("autocomplete/filter/{model}/{key}",'SearchController@filterAutoComplete');
        Route::get("autocomplete",'SearchController@autocomplete');
        Route::get("searchForApp/{type?}",'SearchController@searchForApp');
        //history
        Route::get("history/{type}","HistoryController@history");
        // Route::post('like/{model}/{modelId}','LikeController@store');

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
        //document upload
        Route::post("profile/uploadDocument","ProfileController@uploadDocument");
        Route::delete("profile/deleteDocument", "ProfileController@deleteDocument");
        //check handle
        // Route::post("profile/handleAvailable", ['uses'=>'ProfileController@handleAvailable']);
        Route::get("foodieType","ProfileController@foodieType");
        Route::get("interested/collections","ProfileController@interestedCollections");
        Route::get("establishmentType","ProfileController@establishmentType");
        Route::get("profile/getAllergens","ProfileController@getAllergens");
        Route::post("profile/addAllergens","ProfileController@addAllergens");
        Route::post("profile/reviewHelperText","ProfileController@reviewHelperText");
        Route::get("profile/tagging",['uses'=>'ProfileController@tagging']);
        Route::post('profile/nestedFollow',['uses'=>'ProfileController@nestedFollow']);
        Route::post('profile/follow',['uses'=>'ProfileController@follow']);
        Route::post('profile/unfollow',['uses'=>'ProfileController@unfollow']);
        Route::get('profile/{id}/followers',['uses'=>'ProfileController@followers']);
        Route::get("profile/{id}/mutualFollowers",['uses'=>'ProfileController@mutualFollowers']);
        Route::get('profile/{id}/following',['uses'=>'ProfileController@following']);
        Route::get("profile/{id}/recent",['uses'=>'ProfileController@recentUploads']);
        Route::get("profile/tastingCategory","ProfileController@tastingCategory");
        Route::get('/people','ProfileController@all');
        Route::get('/people/onboarding','ProfileController@onboarding');
        Route::get("people/filters", "ProfileController@filters");
        // Route::post("profile/filters", "ProfileController@filtersData");
        Route::resource('profile','ProfileController');

        // onboarding routes
        Route::get("onboarding/network","OnboardingController@getNetworkFollowers");
        Route::get('onboarding/skills', 'OnboardingController@skills');
        Route::get('onboarding/autocomplete/skills', 'OnboardingController@autoCompleteSkills');

        //company filter
        Route::get("companies/filters", "CompanyController@filters");
        Route::resource('companies','CompanyController');

        //recipes cuisine
        Route::post("uploadCuisine","CuisineController@uploadCuisine");
        Route::resource("cuisine",'CuisineController');
        //namespace profile
        Route::group(['namespace'=>'Profile','prefix'=>'profiles/{profileId}','as'=>'profile.','middleware'=>'api.checkProfile'], function() {
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
            Route::group(['namespace'=>'Photo','prefix'=>'photos/{photoId}','as'=>'comments.'],function() {
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
        // Route::resource('albums','AlbumController');
        // Route::resource('photos','PhotoController');
        // Route::resource("books","BookController");
        // Route::resource("shows","ProfileShowController");
        // Route::resource("projects","ProjectController");
        // Route::resource("experiences","ExperienceController");
        // Route::resource("awards","AwardController");
        // Route::resource("certifications","CertificationController");
    
        Route::post("/uploadFiles","UploadFilesController@uploadFiles");
        Route::post("/uploadContestFiles","UploadFilesController@uploadContestFiles");

        Route::post("/preview",function(Request $request){
            $url = $request->input('url');
            $tags = \App\Preview::get($url);
            return response()->json(['data'=>$tags,'errors'=>[],'messages'=>null]);
        });

        Route::get('@{handle}','HandleController@show');

        // Route::get("apk_version",function(){
        //     $version = \App\Version::getVersion();
        //     return response()->json($version);
        // });

        // Route::post("apk_version",function(Request $request){
        //     $version = \App\Version::setVersion($request->input('compatible_version'),$request->input('latest_version'));
        //     return response()->json($version);
        // });

        Route::get('apk_version', 'VersionController@getAndroidVersion');
        Route::post('apk_version', 'VersionController@setAndroidVersion');
        Route::get('ios_version', 'VersionController@getIosVersion');
        Route::post('ios_version', 'VersionController@setIosVersion');
        Route::get("ios_module_version","VersionController@getIosModuleVersion");
        Route::get("android_module_version","VersionController@getAndroidModuleVersion");
        Route::post("ios_module_version/{id}","VersionController@postIosModuleVersion");
        Route::post("android_module_version/{id}","VersionController@postAndroidModuleVersion");
        Route::resource("advertisements","AdvertisementController");

    }); // end of authenticated routes. Add routes before this line to be able to
    // get current logged in user.

    Route::get("csv/college",function (Request $request){
        $this->model = [];
        $comments = \DB::table('public_product_user_review')->where('source',2)->where('current_status',2)
            ->where('select_type',3)->get();

        $data = [];
        $index = 1;
        foreach ($comments as $comment)
        {
            $product = \App\PublicReviewProduct::where('id',$comment->product_id)->first();
            $profile = \App\Recipe\Profile::where('id',$comment->profile_id)->first();
            $data[] = ['S.No'=>$index,
                'TT Product Name'=> $product->name,
                'TT Product Id Link'=>"https://www.tagtaste.com/reviews/products/".$comment->product_id,
                'Taster Name'=> $profile->name,
                'Taster Profile Link'=> "https://www.tagtaste.com/profile/".$profile->id,
                'Comment'=> $comment->value
            ];
            $index++;
        }
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=review_comment.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        $columns = array('S.No','TT Product Name','TT Product Id Link','Taster Name','Taster Profile Link','Comment');

        $str = '';
        foreach ($columns as $c) {
            $str = $str.$c.',';
        }
        $str = $str."\n";
        foreach($data as $review) {
            foreach ($columns as $c) {
                $str = $str.$review[$c].',';
            }
            $str = $str."\n";
        }

        return response($str, 200, $headers);

    });


    Route::group(['namespace'=>'Survey','prefix'=>'survey','as'=>'survey.','middleware'=>'api.auth'],function() {
        Route::get('/reports/{id}','SurveyController@reports')->name("reports");      
        Route::get('/questions-list','SurveyController@question_list')->name("questions.list");
        Route::post('/save-survey','SurveyController@saveAnswers');
        Route::get('/{id}','SurveyController@index');  
        Route::post('/{id}','SurveyController@update');
        Route::delete('/{id}','SurveyController@destroy');
        Route::post('/','SurveyController@store');
        
    });
    
});