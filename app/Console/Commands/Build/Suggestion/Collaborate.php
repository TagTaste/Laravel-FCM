<?php

namespace App\Console\Commands\Build\Suggestion;

use Carbon\Carbon;
use Illuminate\Console\Command;

class Collaborate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:suggestion:collaborate';

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
                    $collaborateIds = \Redis::sMembers('suggested:collaborate:'.$owner->id);

                    foreach ($collaborateIds as $collaborateId)
                    {
                        \Redis::sRem('suggested:collaborate:'.$owner->id,$collaborateId);
                    }
                }
            });

        \DB::table('suggestion_engine')->where('type','collaborate')
            ->orderBy('profile_id')->chunk(100,function($owners){
                foreach($owners as $owner){
                    $suggestedIds = $owner->suggested_id;
                    $suggestedIds = explode(',',$suggestedIds);
                    foreach ($suggestedIds as $suggestedId)
                    {
                        \Redis::sAdd('suggested:job:'.$owner->profile_id,$suggestedId);
                    }
                }
            });



    }
}
