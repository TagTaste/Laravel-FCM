<?php

namespace App\Console\Commands\Build\Suggestion;

use Carbon\Carbon;
use Illuminate\Console\Command;

class Job extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:suggestion:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 're-build redis key from database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \DB::table('profiles')->whereNUll('deleted_at')
            ->orderBy('id')->chunk(100,function($owners){
                foreach ($owners as $owner)
                {
                    $jobIds = \Redis::sMembers('suggested:job:'.$owner->id);

                    foreach ($jobIds as $jobId)
                    {
                        \Redis::sRem('suggested:job:'.$owner->id,$jobId);
                    }
                }
            });

        \DB::table('suggestion_engine')->where('type','job')
            ->orderBy('profile_id')->chunk(100,function($owners){
                foreach($owners as $owner){
                    $suggestedIds = $owner->suggested_id;
                    $suggestedIds = explode(',',$suggestedIds);
                    foreach ($suggestedIds as $suggestedId)
                    {
                        $hasApplied = \DB::table('applications')->where('job_id',$suggestedId)->where('profile_id',$owner->profile_id)->exists();
                        if(!$hasApplied)
                        {
                            \Redis::sAdd('suggested:job:'.$owner->profile_id,$suggestedId);
                        }
                    }
                }
            });



    }
}
