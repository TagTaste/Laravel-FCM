<?php

namespace App\Console;

use App\Console\Commands\Build\Cache\Collaboration;
use App\Console\Commands\Build\Cache\Followers;
use App\Console\Commands\Build\Cache\Following;
use App\Console\Commands\Build\Cache\Job;
use App\Console\Commands\Build\Cache\Photo;
use App\Console\Commands\Build\Cache\Profiles;
use App\Console\Commands\Build\Cache\Recipe;
use App\Console\Commands\Build\Cache\Share;
use App\Console\Commands\Build\Search\Company;
use App\Console\Commands\RegisterCompanyFromGoogle;
use App\Console\Commands\RegisterFromGoogle;
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
        Recipe::class,
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
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
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
