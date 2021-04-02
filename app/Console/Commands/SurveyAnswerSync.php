<?php

namespace App\Console\Commands;

use App\SurveyAnswers;
use App\surveyApplicants as AppSurveyApplicants;
use Illuminate\Console\Command;
use SurveyApplicants;

class SurveyAnswerSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:surveyapplicantsync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to sync existing answered survey to applicants table';

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
        $getAnswerDetails = SurveyAnswers::groupBy("profile_id")->groupBy("survey_id")->get();
        foreach ($getAnswerDetails as $values) {
            $getApplicantDetails = \DB::table("survey_applicants")->where("profile_id", "=", $values->profile_id)->where("survey_id", "=", $values->survey_id)->first();
            if (empty($getApplicantDetails)) {
                $profile = $values->profile;
                $inputs = [
                    'is_invited' => 0, 'profile_id' => $profile->id, 'survey_id' => $values->survey_id,
                    'message' => null, 'address' => null,
                    'city' => null, 'age_group' => $this->calcDobRange(date("Y", strtotime($profile->dob))), 'gender' => $profile->gender, 'hometown' => $profile->hometown, 'current_city' => $profile->city, "completion_date" => null, "created_at" => date("Y-m-d H:i:s",strtotime($values->created_at)),'updated_at'=>date("Y-m-d H:i:s",strtotime($values->updated_at)),'deleted_at' => null
                ];

                $ins = \DB::table('survey_applicants')->insert($inputs);
            }
        }
    }

    public function calcDobRange($year){
        
        if($year > 2000){
            return "gen-z";
        }else if($year >= 1981 && $year <= 2000){
            return "millenials";
        }else if($year >= 1961 && $year <=1980 ){
            return "gen-x";
        }else{
            return "yold";
        }
    }
}
