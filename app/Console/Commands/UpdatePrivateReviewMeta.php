<?php

namespace App\Console\Commands;

use App\Profile;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

use App\Collaborate\BatchAssign;
use App\Collaborate\Review;
use App\CollaborateTastingEntryMapping;

class UpdatePrivateReviewMeta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_private_review_meta';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update current status, start and end review time for private reviews';
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
        //update collaborate_batches_assign using collaborate_tasting_entry_mapping and collaborate_tasting_user_review
        
        BatchAssign::chunk(200, function($reviews){
            foreach($reviews as $review){
                //first check in collaborate_tasting_entry_mapping
                $startActivity = config("constant.REVIEW_ACTIVITY.START");
                $startDate =  \DB::select("SELECT MIN(created_at) as start_date FROM `collaborate_tasting_entry_mapping` where profile_id=$review->profile_id AND collaborate_id=$review->collaborate_id AND batch_id=$review->batch_id AND activity = '$startActivity' AND deleted_at IS NULL");
                
                
                $endActivity = config("constant.REVIEW_ACTIVITY.END");
                $endDate =  \DB::select("SELECT MAX(created_at) as end_date FROM `collaborate_tasting_entry_mapping` where profile_id=$review->profile_id AND collaborate_id=$review->collaborate_id AND batch_id=$review->batch_id AND activity = '$endActivity' AND deleted_at IS NULL");

                $start_date = $startDate[0]->start_date;
                $end_date = $endDate[0]->end_date;
                
                if(isset($start_date) || isset($end_date)){
                     $startDate = json_decode(json_encode(($startDate[0]->start_date), true));
                     $endDate = json_decode(json_encode(($endDate[0]->end_date), true));

                     $data = ["start_review" => $start_date, "current_status" => 2];
                     if(isset($end_date)){
                        $data["end_review"] = $end_date;
                        $data["current_status"] = 3;
                        $data["duration"] = strtotime($end_date) - strtotime($start_date);
                     }
                     BatchAssign::where('id', $review->id)->update($data);
                }else{
                    //Check in collaborate_tasting_user_review
                    $reviewData = \DB::select("SELECT MIN(created_at) as start_date, MAX(updated_at) as end_date, MAX(current_status) as current_status FROM `collaborate_tasting_user_review` WHERE profile_id = $review->profile_id AND collaborate_id = $review->collaborate_id AND batch_id = $review->batch_id");

                    if($reviewData[0]->current_status == 3){
                        $durationInSec = strtotime($reviewData[0]->end_date) - strtotime($reviewData[0]->start_date);
                        $data = ["start_review" => $reviewData[0]->start_date, 
                        "end_review" => $reviewData[0]->end_date,
                        "current_status" => 3,
                        "duration" => $durationInSec];
                        BatchAssign::where('id', $review->id)->update($data);
                    }
                }
            }            
        });
    }
}
