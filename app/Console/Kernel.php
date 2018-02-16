<?php

namespace App\Console;

use App\Console\Commands\BackupDatabase;
use App\Console\Commands\Build\Cache\Collaboration;
use App\Console\Commands\Build\Cache\Companies;
use App\Console\Commands\Build\Cache\Followers;
use App\Console\Commands\Build\Cache\Following;
use App\Console\Commands\Build\Cache\Job;
use App\Console\Commands\Build\Cache\Photo;
use App\Console\Commands\Build\Cache\Profiles;
use App\Console\Commands\Build\Cache\Recipe;
use App\Console\Commands\Build\Cache\Share;
use App\Console\Commands\Build\Cache\Shoutout;
use App\Console\Commands\CapitalizeExpertise;
use App\Console\Commands\CountryCodeFix;
use App\Console\Commands\fixKeywords;
use App\Console\Commands\GenerateThumbnails;
use App\Console\Commands\ProfileDelete;
use App\Console\Commands\RegisterCompanyFromGoogle;
use App\Console\Commands\RegisterFromGoogle;
use App\Console\Commands\RemoveNullFcmTokens;
use App\Console\Commands\RemoveSpecialCharsHandle;
use App\Console\Commands\SetPlatformAndroid;
use App\Console\Commands\UpdateNotificationModel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        
        //Rebuild Search
        \App\Console\Commands\Build\Search\Collaboration::class,
        \App\Console\Commands\Build\Search\Company::class,
        \App\Console\Commands\Build\Search\Job::class,
        \App\Console\Commands\Build\Search\Profile::class,
        \App\Console\Commands\Build\Search\Recipe::class,
        
        //Meta
            //Collab
            \App\Console\Commands\Build\Meta\Collaboration\CountApplications::class,
            
            //Job
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
        //command for temporary
        \App\Console\Commands\DateFixCompany::class,
        //for date fixes delete after run commands
        \App\Console\Commands\DateFixProfile::class,
        //set expireon in job and collaboration run once
        \App\Console\Commands\SetExpireon::class,
        \App\Console\Commands\ExpireonJob::class,
        \App\Console\Commands\ExpireonCollaboration::class,
        \App\Console\Commands\EmployeeCount::class,
        \App\Console\Commands\Build\CompanyAdmins::class,

        \App\Console\Commands\ChangeAffiliationsCommand::class,
        \App\Console\Commands\ChatGroup::class,
        
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

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('expires_on:job')->dailyAt('12:00');
        $schedule->command('expires_on:collaboration')->dailyAt('12:00');
        $schedule->command('backup:db')->dailyAt('00:00');
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
