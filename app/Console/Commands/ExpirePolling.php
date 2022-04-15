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

        \App\Polling::where('created_at', '<=', Carbon::now()->subDay(7)->toDateTimeString())
            ->where('is_expired', 0)->whereNull('deleted_at')
            ->orderBy('id')->chunk(100, function ($models) {

                foreach ($models as $model) {

                    $mData = $model;

                    if (isset($mData->company_id) && !empty($mData->company_id)) {
                        $admin = Profile::join("company_users", "profiles.id", "company_users.profile_id")->where("company_users.company_id", $mData->company_id)->select(["profiles.*", "company_users.company_id as company_id"])->get();
                        $count = 1;
                    } else {
                        $count = 0;
                        $admin = Profile::where('id', $mData->profile_id)->first();
                    }
                    $profiles =  Profile::select('profiles.*')->join('poll_votes', 'poll_votes.profile_id', '=', 'profiles.id')
                        ->where('poll_votes.poll_id', $mData->id)->whereNotIn('profiles.id', array_column($admin->toArray(), 'id'))->get();

                    if ($count) {
                        foreach ($admin as $k) {
                            $profiles->push($k);
                        }
                    } else {
                        $profiles->push($admin);
                    }
                    DB::table('poll_questions')->where('id', $mData->id)->update(['expired_time' => Carbon::now()->toDateTimeString(), 'is_expired' => 1]);
                    $model->removeFromGraph(); //remove this poll from neo4j
                    foreach ($profiles as $profile) {
                        $event = $mData;

                        if (isset($profile->company_id) || ($count == 0 && $admin->id == $profile->id)) {

                            $event->isAdmin = true;
                        }
                        $event->profile = $profile;

                        $company = null;
                        if (isset($mData->company_id) && !empty($mData->company_id)) {
                            $company = DB::table("companies")->where("id", $mData->company_id)->first();
                        }
                        $adminProf = null;
                        if ($count == 0) {
                            $adminProf = $admin;
                        }
                        event(new ExpirePoll($event, $adminProf, null, null, 'expirepoll', $company));
                    }
                }
            });
    }
}
