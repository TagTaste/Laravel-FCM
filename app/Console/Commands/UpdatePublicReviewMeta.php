<?php

namespace App\Console\Commands;

use App\Profile;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use  App\PublicReviewProduct\Review;


class UpdatePublicReviewMeta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:public:reviewMeta';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update current status, start and end review time for public reviews';
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
        //update public_review_user_timings using public_review_entry_mapping and public_product_user_review

        \DB::table('public_review_user_timings')->orderBy('id', 'asc')->chunk(200, function($reviews){
            foreach($reviews as $review){
                //first check in public_review_entry_mapping
                $startActivity = config("constant.REVIEW_ACTIVITY.START");
                $startDateData =  \DB::select("SELECT MIN(created_at) as start_date FROM `public_review_entry_mapping` where profile_id=$review->profile_id AND product_id='$review->product_id' AND activity = '$startActivity' AND deleted_at IS NULL");
                
                
                $endActivity = config("constant.REVIEW_ACTIVITY.END");
                $endDateData =  \DB::select("SELECT MAX(created_at) as end_date FROM `public_review_entry_mapping` where profile_id=$review->profile_id AND product_id='$review->product_id' AND activity = '$endActivity' AND deleted_at IS NULL");

                $startDate = $startDateData[0]->start_date;
                $endDate = $endDateData[0]->end_date;
                
                if(isset($startDate)){
                     $data = ["start_review" => $startDate, "current_status" => 1];
                     if(isset($endDate)){
                        $data["end_review"] = $endDate;
                        $data["current_status"] = 2;
                        $data["duration"] = strtotime($endDate) - strtotime($startDate);
                     }
                     \DB::table('public_review_user_timings')->where('id', $review->id)->update($data);
                }else{
                    //Check in public_product_user_review
                    $reviewData = \DB::select("SELECT MAX(updated_at) as end_date, MAX(current_status) as current_status FROM `public_product_user_review` WHERE profile_id = $review->profile_id AND product_id = '$review->product_id'");

                    $data = ["start_review"=> $review->created_at, "current_status" => 1];

                    if($reviewData[0]->current_status == 2){
                        $durationInSec = strtotime($reviewData[0]->end_date) - strtotime($review->created_at);
                        $data["end_review"] = $reviewData[0]->end_date;
                        $data["duration"] = $durationInSec;
                        $data["current_status"] = 2;
                    }
                    \DB::table('public_review_user_timings')->where('id', $review->id)->update($data);
                }
            }            
        });
    }
}
