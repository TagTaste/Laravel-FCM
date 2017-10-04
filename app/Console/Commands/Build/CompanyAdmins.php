<?php

namespace App\Console\Commands\Build;

use Carbon\Carbon;
use Illuminate\Console\Command;

class CompanyAdmins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:companyAdmins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds company_users table';

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
        $now = Carbon::now()->toDateTimeString();
        \DB::table("companies")->select("companies.id as id",'companies.user_id','profiles.id as profile_id')
            ->join("profiles",'profiles.user_id','=','companies.user_id')
            ->orderBy('companies.id')->chunk(100,function($owners) use (&$now){
                foreach($owners as $owner){
                    $exists = \DB::table("company_users")
                        ->where("user_id",$owner->user_id)->where('company_id',$owner->id)->where('profile_id',$owner->profile_id)
                        ->exists();
                    if(!$exists){
                        \DB::table("company_users")->insert(['company_id'=>$owner->id,
                            'created_at'=>$now,
                            'user_id'=>$owner->user_id,'profile_id'=>$owner->profile_id]);
                    }
                }
            });
        
        
        
    }
}
