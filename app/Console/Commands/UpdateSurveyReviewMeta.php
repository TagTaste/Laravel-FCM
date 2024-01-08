<?php

namespace App\Console\Commands;

use App\Profile;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\SurveyAttemptMapping;
use App\SurveyAnswers;
use App\SurveysEntryMapping;

class UpdateSurveyReviewMeta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:survey:reviewMeta';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update current status, start and end review time for survey reviews';
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
        //update start review time, end review time and duration in seconds

        //update surveys_attempt_mapping using surveys_entry_mapping and survey_attempt_mapping

        SurveyAttemptMapping::chunk(200, function($reviews){
            foreach($reviews as $review){
                //first check in surveys_entry_mapping
                $startActivity = config("constant.SURVEY_ACTIVITY.START");
                $startDateData =  \DB::select("SELECT MIN(created_at) as start_date FROM `surveys_entry_mapping` where surveys_attempt_id = $review->id AND activity = '$startActivity' AND deleted_at IS NULL");
                
                $endActivity = config("constant.SURVEY_ACTIVITY.END");
                $endDateData =  \DB::select("SELECT MAX(created_at) as end_date FROM `surveys_entry_mapping` where surveys_attempt_id = $review->id AND activity = '$endActivity' AND deleted_at IS NULL");

                $startDate = $startDateData[0]->start_date;
                $endDate = $endDateData[0]->end_date;
                
                if(isset($startDate)){
                    $data = ["start_review" => $startDate, "current_status" => 3];
                    if(isset($endDate)){
                        $data["end_review"] = $endDate;
                        $data["current_status"] = 2;
                        $data["duration"] = strtotime($endDate) - strtotime($startDate);
                    }
                    $review->update($data);
                }else{
                    //Check in survey_attempt_mapping
                    $data = ["start_review" => $review->created_at, "current_status" => 3];
                    if(isset($review->completion_date)){
                        $durationInSec = strtotime($review->completion_date) - strtotime($review->created_at);
                        $data["end_review"] = $review->completion_date;
                        $data["current_status"] = 2;
                        $data["duration"] = $durationInSec;
                    }
                    $review->update($data);
                }
            }            
        });
    }
}
