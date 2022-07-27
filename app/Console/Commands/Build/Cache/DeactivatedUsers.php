<?php

namespace App\Console\Commands\Build\Cache;

use Illuminate\Console\Command;
use App\Helper;
use Illuminate\Support\Facades\Redis;

class DeactivatedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:cache:deactivated_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuilds deactivated users cache';

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
        Redis::del('deactivated_users');
        \App\User::where('account_deactivated',1)->chunk(200, function($users){
            foreach($users as $user){
                $position = Redis::executeRaw(array('lpos','deactivated_users',$user->id));
                if(!is_numeric($position)){
                    echo "Updating ".$user->id."\n";
                    Redis::lpush('deactivated_users',$user->id);        
                }
            }
        });
    }
}
