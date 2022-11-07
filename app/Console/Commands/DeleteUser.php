<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:deactivated_user';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete deactivated users by checking account deactivation requests';
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

        DB::table('account_deactivate_requests')->where('deleted_on','<',Carbon::now()->toDateTimeString())->whereNull('deleted_at')
        ->orderBy('id')->chunk(100, function ($models) {
                foreach ($models as $model) {
                    $mData = $model;
                    $profile = \App\Profile::Where('id',$mData->profile_id)->withTrashed()->first();
                    $user = \App\User::where('id', $profile->user_id)->first();
                    $new_email = $user['email'].'_deleted';
                    \App\User::where('id',$profile->user_id)->update(['name'=>'Deleted User','email'=>$new_email]);
                    \App\Profile::where('id',$profile->id)->update(['phone'=>'','verified_phone'=>0]);
                                        
                    //update redis
                    $profile->addToCache();
                    $profile->addToCacheV2();       
                    \App\User::where('id',$profile->user_id)->update(['deleted_at'=>Carbon::now()]);
                    DB::table('account_deactivate_requests')->where('id', $mData->id)->update(['deleted_at'=>Carbon::now()]);         
                    echo "Deleting profile id: ".$profile->id;
                }
            });
    }
}
