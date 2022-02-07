<?php

namespace App\Console\Commands\Build;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class SurveyApplicantStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:build:applicants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $models = \DB::table('survey_applicants')->whereNull("deleted_at")->get();
        foreach ($models as $model) {
            Redis::set("surveys:application_status:$model->survey_id:profile:$model->profile_id", $model->application_status);
        }
    }
}
