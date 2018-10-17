<?php

namespace App\Console\Commands\Build\Suggestion;

use Carbon\Carbon;
use Illuminate\Console\Command;

class Profile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:suggestion:profile';

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
                    $profileIds = \Redis::sMembers('suggested:profile:'.$owner->id);

                    foreach ($profileIds as $profileId)
                    {
                        \Redis::sRem('suggested:profile:'.$owner->id,$profileId);
                    }
                }
            });

//        \DB::table('suggestion_engine')->where('type','profile')
//            ->orderBy('profile_id')->chunk(100,function($owners){
//                foreach($owners as $owner){
//                    $suggestedIds = $owner->suggested_id;
//                    $suggestedIds = explode(',',$suggestedIds);
//                    foreach ($suggestedIds as $suggestedId)
//                    {
//                        if(!\Redis::sIsMember('following:profile:'.$owner->profile_id, $suggestedId))
//                        {
//                            \Redis::sAdd('suggested:profile:'.$owner->profile_id,$suggestedId);
//                        }
//                    }
//                }
//            });



    }
}
