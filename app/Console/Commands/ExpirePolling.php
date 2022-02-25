<?php

namespace App\Console\Commands;

use App\Company;
use App\Events\ExpirePoll;
use App\Notify\Profile;
use App\Recipe\Company as RecipeCompany;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpirePolling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expires_on:polling';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set deleted_at in when poll is expired';
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

        \App\Polling::where('created_at', '<=', Carbon::now()->subMinutes(5)->toDateTimeString())
            ->where('is_expired', 0)->whereNull('deleted_at')
            ->orderBy('id')->chunk(100, function ($models) {

                foreach ($models as $model) {
                    
                    $mData = $model;
                    $profiles =  Profile::select('profiles.*')->join('poll_votes', 'poll_votes.profile_id', '=', 'profiles.id')->where("poll_votes.created_at",">",$mData->updated_at)
                        ->where('poll_votes.poll_id', $mData->id)->get();
                    
                    
                    if(isset($mData->company_id) && !empty($mData->company_id)){
                        $admin = Profile::join("company_users","profiles.id","=company_users.profile_id")->where("company_users.company_id",$mData->company_id)->select("profiles.*")->get();
                    }else{
                        $admin = Profile::where('id', $mData->profile_id)->first();
                    }
                    $profiles->push($admin);
                    foreach ($profiles as $profile) {
                        $event = $mData;
                        if($admin->id==$profile->id){
                        
                            $event->isAdmin = true;
                        }
                        $event->profile = $profile;

                        $company = null;
                        if(isset($mData->company_id) && !empty($mData->company_id)){
                            $company = DB::table("companies")->where("id",$mData->company_id)->first();
                        }
                        event(new ExpirePoll($event,$admin,null,null,'expirepoll',$company));                       
                        
                    }
                    DB::table("poll_questions")->where("id",$model->id)->update(['expired_time' => Carbon::now()->toDateTimeString(), 'is_expired' => 1]);
                }
            });
    }
}
