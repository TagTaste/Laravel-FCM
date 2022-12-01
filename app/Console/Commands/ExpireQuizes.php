<?php

namespace App\Console\Commands;

use App\Events\ExpireQuiz;
use App\Notify\Profile;
use App\Quiz;
use App\Events\DeleteFeedable;
use App\CompanyUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireQuizes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expires_on:quiz';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'set deleted_at in when quiz is expired';
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

        Quiz::with([])->where('expired_at', '<', date("Y-m-d"))->where('state', "=", config("constant.QUIZ_STATES.PUBLISHED"))->whereNull('deleted_at')
            ->orderBy('created_at')->chunk(100, function ($models) {

                foreach ($models as $model) {

                    $companyId = $model->company_id;
                    if (isset($companyId)) {
                        $company = DB::table("companies")->where("id", $model->company_id)->first();

                        $profiles = CompanyUser::where('company_id', $companyId)->get();
                        foreach ($profiles as $profile) {
                            $model->profile = $profile;
                            event(new ExpireQuiz($model, null, null, null, 'expireQuiz', $company));
                        }
                    } else {
                        event(new ExpireQuiz($model, null, null, null, 'expireQuiz', null));
                    }

                    $model->update(['state' => config("constant.QUIZ_STATES.EXPIRED")]);
                    event(new DeleteFeedable($model));
                   // $model->removeFromGraph(); //remove this poll from neo4j
                   
                }
            });
    }
}
