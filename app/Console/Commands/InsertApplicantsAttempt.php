<?php

namespace App\Console\Commands;

use App\Recipe\Profile;
use App\SurveyAnswers;
use Illuminate\Console\Command;
use App\surveyApplicants;
use App\SurveyAttemptMapping;
use App\Surveys;

class InsertApplicantsAttempt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:applicantsAttempt';

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
        //
        $attemptMapping = [];
        $applicants = surveyApplicants::whereNull("deleted_at")->whereIn("application_status", [2, 3])->get()->toArray();
        foreach ($applicants as $applicant) {
          $profile =  Profile::where("id",$applicant["profile_id"])->whereNull("deleted_at")->first();
           $survey = Surveys::where("id",$applicant["survey_id"])->whereNull("deleted_at")->first();
           if(!empty($profile) && !empty($survey)){
            $attemptMapping["survey_id"] = $applicant["survey_id"];
            $attemptMapping["profile_id"] = $applicant["profile_id"];
            $attemptMapping["attempt"] = 1;
            if ($applicant["application_status"] == config("constant.SURVEY_APPLICANT_ANSWER_STATUS.COMPLETED")) {
                $attemptMapping["completion_date"] = $applicant["completion_date"];
            }
            $minTime = SurveyAnswers::where("survey_id", $applicant["survey_id"])->where("profile_id", $applicant["profile_id"])->whereNull("deleted_at")->orderBy("created_at")->first();
            $attemptMapping["created_at"] = !empty($minTime) ? $minTime->created_at : $applicant["created_at"];
            SurveyAttemptMapping::create($attemptMapping);
        }
        }
    }
}
