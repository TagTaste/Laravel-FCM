<?php

namespace App\Console\Commands\Build\Suggestion;

use Carbon\Carbon;
use Illuminate\Console\Command;

class Company extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:suggestion:company';

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
        \DB::table('companies')->whereNUll('deleted_at')
            ->orderBy('id')->chunk(100,function($owners){
                foreach ($owners as $owner)
                {
                    $companyIds = \Redis::sMembers('suggested:company:'.$owner->id);

                    foreach ($companyIds as $companyId)
                    {
                        \Redis::sRem('suggested:company:'.$owner->id,$companyId);
                    }
                }
            });

        \DB::table('suggestion_engine')->where('type','company')
            ->orderBy('profile_id')->chunk(100,function($owners){
                foreach($owners as $owner){
                    $suggestedIds = $owner->suggested_id;
                    $suggestedIds = explode(',',$suggestedIds);
                    foreach ($suggestedIds as $suggestedId)
                    {
                        if(!\Redis::sIsMember('following:profile:'.$owner->profile_id, "company.".$suggestedId))
                        {
                            \Redis::sAdd('suggested:company:'.$owner->profile_id,$suggestedId);
                        }
                    }
                }
            });



    }
}
