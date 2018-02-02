<?php

namespace App\Console\Commands;

use App\Collaborate;
use App\Events\DeleteFeedable;
use App\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SetInviteCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SetInviteCode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set invite code  in user table';

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
        //this run only once after that remove from kernel.php this file

        \DB::table("users")->whereNull('invite_code')->orderBy('id')->chunk(100,function($models){
            foreach($models as $model){
                \DB::table('users')->where('id',$model->id)->update(['invite_code'=>mt_rand(100000, 999999)]);
            }
        });


    }
}
