<?php

namespace App\Console;

use App\Console\Commands\DispatchJob;
use App\Console\Commands\AddHandle;
use App\Console\Commands\AddSuggestionData;
use App\Console\Commands\BackupDatabase;
use App\Console\Commands\PaymentReminder;
use App\Console\Commands\Build\Cache\Collaboration;
use App\Console\Commands\Build\Cache\Companies;
use App\Console\Commands\Build\Cache\Followers;
use App\Console\Commands\Build\Cache\Following;
use App\Console\Commands\Build\Cache\Job;
use App\Console\Commands\Build\Cache\Photo;
use App\Console\Commands\Build\Cache\Profiles;
use App\Console\Commands\Build\Cache\PublicReviewProduct;
use App\Console\Commands\Build\Cache\Recipe;
use App\Console\Commands\Build\Cache\Share;
use App\Console\Commands\Build\Cache\Shoutout;
use App\Console\Commands\Build\Cache\Polling;
use App\Console\Commands\Build\Cache\Survey;
use App\Console\Commands\CapitalizeExpertise;
use App\Console\Commands\ChatMergeMessage;
use App\Console\Commands\CloseCollaborate;
use App\Console\Commands\CollaborationQuestions;
use App\Console\Commands\CapitalizeUserName;
use App\Console\Commands\CompleteTastingMail;
use App\Console\Commands\CountryCodeFix;
use App\Console\Commands\DeletePhoto;
use App\Console\Commands\ExpirePolling;
use App\Console\Commands\FixCollaborateImage;
use App\Console\Commands\GenerateThumbnails;
use App\Console\Commands\InsertPublicReviewQuestionair;
use App\Console\Commands\InsertPublicReviewQuestionair1;
use App\Console\Commands\ProfileDelete;
use App\Console\Commands\ProgressiveImage;
use App\Console\Commands\RegisterCompanyFromGoogle;
use App\Console\Commands\RegisterFromGoogle;
use App\Console\Commands\RemoveDuplicateFromReviewTable;
use App\Console\Commands\RemoveNullFcmTokens;
use App\Console\Commands\RemoveSpecialCharsHandle;
use App\Console\Commands\ServiceInterruption;
use App\Console\Commands\SetPlatformAndroid;
use App\Console\Commands\ShoutoutPreviewUpdate;
use App\Console\Commands\SocialConnectedAddFlag;
use App\Console\Commands\UpdateNotificationModel;
use App\Console\Commands\UpdateProfileCompiledInfo;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SettingChanges as SettingChanges;

use App\Console\Commands\Build\Graph\DateOfBirth as GraphDateofBirth;
use App\Console\Commands\Build\Graph\Cuisines as GraphCuisines;
use App\Console\Commands\Build\Graph\FoodieType as GraphFoodieType;
use App\Console\Commands\Build\Graph\Specializations as GraphSpecialisations;
use App\Console\Commands\Build\Graph\Education as GraphEducation;
use App\Console\Commands\Build\Graph\Experiance as GraphExperiance;
use App\Console\Commands\Build\Graph\Profiles as GraphProfiles;
use App\Console\Commands\Build\Graph\Companies as GraphCompanies;
use App\Console\Commands\Build\Graph\PublicReviewProduct as GraphPublicReviewProducts;

use App\Console\Commands\Build\Graph\Build\Following as GraphFollowing;
use App\Console\Commands\Build\Graph\Build\UserDoB as GraphUserDoB;
use App\Console\Commands\Build\Graph\Build\UserCuisine as GraphUserCuisine;
use App\Console\Commands\Build\Graph\Build\UserFoodieType as GraphUserFoodieType;
use App\Console\Commands\Build\Graph\Build\UserSpecialization as GraphUserSpecialization;
use App\Console\Commands\Build\Graph\Build\UserEducation as GraphUserEducation;
use App\Console\Commands\Build\Graph\Build\UserExperiance as GraphUserExperiance;
use App\Console\Commands\Build\Graph\Build\UserPublicReview as GraphUserPublicReview;
use App\Console\Commands\Build\Graph\Build\UserPolling as GraphUserPolling;
use App\Console\Commands\Build\Graph\Build\UserSurveys as GraphUserSurveys;
use App\Console\Commands\Build\Graph\Build\UserInterestCollaborate as GraphUserInterestCollaborate;



use App\Console\Commands\Build\SurveyApplicantStatus;
use App\Console\Commands\SurveyAnswerSync;
use App\Console\Commands\InsertTTFBQuestion as TTFBQuestionaire;
use App\Console\Commands\makePaidTasters;
use App\Console\Commands\TTFBQuestionUpload;
use App\Console\Commands\removeNotifications;
use App\Console\Commands\syncPollElasticSearch;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        RegisterFromGoogle::class,
        RegisterCompanyFromGoogle::class,
        
        //Rebuild Cache
        Followers::class,
        Following::class,
        Profiles::class,
        Companies::class,
        Recipe::class,
        Shoutout::class,
        Photo::class,
        Collaboration::class,
        Job::class,
        Share::class,
        SettingChanges::class,
        PublicReviewProduct::class,
        Polling::class,
        Survey::class,
        
        // Rebuild Graph
        GraphPublicReviewProducts::class,
        GraphProfiles::class,
        GraphCompanies::class,
        GraphFollowing::class,
        GraphFoodieType::class,
        GraphCuisines::class,
        GraphSpecialisations::class,
        GraphDateofBirth::class,
        GraphEducation::class,
        GraphUserDoB::class,
        GraphUserCuisine::class,
        GraphUserFoodieType::class,
        GraphUserSpecialization::class,
        GraphUserEducation::class,
        GraphExperiance::class,
        GraphUserExperiance::class,
        removeNotifications::class,
        GraphUserPublicReview::class,
        GraphUserPolling::class,
        GraphUserSurveys::class,
        GraphUserInterestCollaborate::class,

        //Rebuild Search
        \App\Console\Commands\Build\Search\Collaboration::class,
        \App\Console\Commands\Build\Search\Company::class,
        \App\Console\Commands\Build\Search\Job::class,
        \App\Console\Commands\Build\Search\Profile::class,
        \App\Console\Commands\Build\Search\Recipe::class,
        \App\Console\Commands\Build\Search\PublicReviewProduct::class,
        \App\Console\Commands\Build\Search\Hashtag::class,
        \App\Console\Commands\Build\Search\UniqueHashtag::class,
        \App\Console\Commands\Build\Search\Surveys::class,
        //Meta
            //Collab
            \App\Console\Commands\Build\Meta\Collaboration\CountApplications::class,
            
            //Occupation
            \App\Console\Commands\Build\Meta\Job\CountApplications::class,
        
            //Likes
        \App\Console\Commands\Build\Meta\Likes::class,
    
        //Filters
        \App\Console\Commands\Build\Filters\Profile::class,
        \App\Console\Commands\Build\Meta\Likes::class,
        \App\Console\Commands\ChangePassword::class,
        \App\Console\Commands\Build\Filters\Company::class,
        \App\Console\Commands\Build\Filters\Job::class,
        \App\Console\Commands\Build\Filters\Collaboration::class,
        \App\Console\Commands\Build\Filters\Recipe::class,
        \App\Console\Commands\Build\Filters\PublicReviewProduct::class,
        //command for temporary
        \App\Console\Commands\DateFixCompany::class,
        //for date fixes delete after run commands
        \App\Console\Commands\DateFixProfile::class,
        //set expireon in job and collaboration run once
        \App\Console\Commands\SetExpireon::class,
        \App\Console\Commands\ExpireonJob::class,
        \App\Console\Commands\ExpireonCollaboration::class,
        \App\Console\Commands\ExpireSurveys::class,
        \App\Console\Commands\EmployeeCount::class,
        \App\Console\Commands\Build\CompanyAdmins::class,
        \App\Console\Commands\MandatoryFieldsCollaboration::class,

        \App\Console\Commands\ChangeAffiliationsCommand::class,
        \App\Console\Commands\ChatGroup::class,

        // set update profile compiled detail run once
        UpdateProfileCompiledInfo::class,

        //expire poll
        ExpirePolling::class,
        
        //API VERSION
        \App\Console\Commands\Api\Version::class,

        \App\Console\Commands\ExpireReopen::class,
        //fixKeywords::class,
        ProfileDelete::class,
        
        //generate thumbnails
        GenerateThumbnails::class,

        //Fixes
        CountryCodeFix::class,
      
        // Capitalize each word of expertise of a user
        CapitalizeExpertise::class,

        // Strip special chars and whitespaces in handle
        RemoveSpecialCharsHandle::class,

        // Backup
        BackupDatabase::class,

        // Add onboarding_step column to profiles table
        \App\Console\Commands\UpdateOnboardingStep::class,

        \App\Console\Commands\SetInviteCode::class,

        // Set platform as Android
        SetPlatformAndroid::class,

        // Remove null fcm tokens
        RemoveNullFcmTokens::class,

        // Update notification model
        UpdateNotificationModel::class,

        ServiceInterruption::class,

        DeletePhoto::class,

        AddHandle::class,

        AddSuggestionData::class,

        //for suggestion command store in redis
        \App\Console\Commands\Build\Suggestion\Profile::class,
        \App\Console\Commands\Build\Suggestion\Company::class,
        \App\Console\Commands\Build\Suggestion\Job::class,
        \App\Console\Commands\Build\Suggestion\Collaborate::class,

        //for suggestion command store in database
        \App\Console\Commands\Build\Suggestion\SuggestionToDatabase\Profile::class,
        \App\Console\Commands\Build\Suggestion\SuggestionToDatabase\Company::class,
        \App\Console\Commands\Build\Suggestion\SuggestionToDatabase\Job::class,
        \App\Console\Commands\Build\Suggestion\SuggestionToDatabase\Collaborate::class,

        FixCollaborateImage::class,
        CollaborationQuestions::class,

        //for product review commands
        \App\Console\Commands\Build\ProductReview\Batches::class,
        \App\Console\Commands\Build\ProductReview\CurrentStatusReview::class,
        \App\Console\Commands\Build\ProductReview\UserBatches::class,


        \App\Console\Commands\MergeCollaborators::class,
        CapitalizeUserName::class,

        //update shutout preview

        ShoutoutPreviewUpdate::class,

        \App\Console\Commands\InsertGlobalQuestion::class,
        TTFBQuestionaire::class,
        TTFBQuestionUpload::class,
        \App\Console\Commands\UpdateGlobalQuestion::class,
        CloseCollaborate::class,

        \App\Console\Commands\FillTableRecepient::class,
        \App\Console\Commands\FillChatType::class,
        \App\Console\Commands\ChatFileModify::class,
        \App\Console\Commands\FillUnreadCount::class,
        \App\Console\Commands\DeleteSingleChat::class,
        \App\Console\Commands\PreviewChangesMessage::class,
        \App\Console\Commands\MailIOS::class,
        \App\Console\Commands\ChangeBucket::class,
        \App\Console\Commands\FollowTagtaste::class,
        \App\Console\Commands\AddHeaderSelectionType::class,
        ChatMergeMessage::class,
        CompleteTastingMail::class,
        //\App\Console\Commands\DeleteUsers::class,
        \App\Console\Commands\MailUnverifiedUserVerificationEmail::class,
        SurveyAnswerSync::class,

        RemoveDuplicateFromReviewTable::class,

        InsertPublicReviewQuestionair::class,
        InsertPublicReviewQuestionair1::class,


        //Progressive image
        ProgressiveImage::class,


        //update social connection
        SocialConnectedAddFlag::class,

        DispatchJob::class,
        makePaidTasters::class,
        removeNotifications::class,

        //paymentlink reminder
        PaymentReminder::class,

        SurveyApplicantStatus::class,
        \App\Console\Commands\CollaborateReviewCalculation::class,
        \App\Console\Commands\BannerExpire::class,


        syncPollElasticSearch::class,
        \App\Console\Commands\ReviewCalculation::class,
        
        syncPollElasticSearch::class,
        \App\Console\Commands\ReviewCalculation::class,
        \App\Console\Commands\CollaborationExpiresOnUpdate::class



    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('expires_on:job')->dailyAt('12:00');
        $schedule->command('expires_on:collaboration')->dailyAt('12:00');

        //daily polling expire at 12
        $schedule->command('expires_on:polling')->dailyAt('12:05');

        //daily polling expire at 12
        $schedule->command(UpdateProfileCompiledInfo::class)->dailyAt('12:00');


        //daily survey expire at 12
        $schedule->command('expires_on:surveys')->dailyAt('12:00');


        $schedule->command('command:remove-notification')->dailyAt('01:00');
        // $schedule->command('profile_compiled_detail:update')->dailyAt('14:05');

        //$schedule->command('backup:db')->withoutOverlapping(15)->dailyAt('00:00');
        //$schedule->command('follow:company')->weekly()->wednesdays()->at('00:00');;
        //$schedule->command('ServiceInterruptionMail')->dailyAt('18:20');

        //command for redis store suggestion
        // $schedule->command("build:suggestion:collaborate")->dailyAt('00:05');
        // $schedule->command("build:suggestion:job")->dailyAt('00:15');
        // $schedule->command("build:suggestion:company")->dailyAt('00:20');
        // $schedule->command("build:suggestion:profile")->dailyAt('00:25');

        //command for db store suggestion
        // $schedule->command("build:suggestion:suggestiontodatabase:collaborate")->dailyAt('01:01');
        // $schedule->command("build:suggestion:suggestiontodatabase:job")->dailyAt('02:01');
        // $schedule->command("build:suggestion:suggestiontodatabase:company")->dailyAt('03:01');
        // $schedule->command("build:suggestion:suggestiontodatabase:profile")->dailyAt('04:01');

        //payment link reminder command

        $schedule->command('reminder:PaymentLink')->dailyAt('01:00');
        $schedule->command('expires_on:banner')->dailyAt('12:10');



        // $schedule->command('review:calculation')->dailyAt('01:00');
       // $schedule->command('SetExpireon:Collab')->dailyAt('12:00');



    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
