<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PublicReviewUserTiming;
use App\PublicReviewProduct;
use App\PublicReviewEntryMapping;
use App\Traits\FlagReview;
use Carbon\Carbon;

class CreatePublicReviewMeta extends Command
{
    use FlagReview; 

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publicReview:meta {--products=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create current status, start and end review time for public reviews based on entries.';

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
        $jsonData = $this->option('products');
        $products = json_decode($jsonData, true);
        
        //update start review time, end review time and duration in seconds for missing users of product
        for($i=0; $i < count($products); $i++){
            //get profiles data who attempted for review but start entry not inserted into entry mapping table
            $reviewData = \DB::select("SELECT DISTINCT profile_id, MIN(created_at) as min, MAX(created_at) as max, current_status FROM `public_product_user_review` WHERE product_id = '$products[$i]' AND profile_id NOT IN (SELECT profile_id FROM `public_review_entry_mapping` WHERE product_id = '$products[$i]' AND activity = 'start') GROUP BY profile_id ORDER BY `public_product_user_review`.`current_status` ASC");
            
            //insert start entry and final_submit entry based on review data
            foreach($reviewData as $review){
                $speicificReviewEntry = PublicReviewEntryMapping::where('product_id', "$products[$i]")->where('profile_id', $review->profile_id)->where('activity', '<>', config('constant.REVIEW_ACTIVITY.SECTION_SUBMIT'))->get()->toArray();
                
                if(empty($speicificReviewEntry)){
                    $startActivityDate = Carbon::parse($review->min)->addSeconds(rand(5,15));
                    if($review->current_status == 1){
                        $entryData = ["profile_id" => $review->profile_id, "product_id" => $products[$i], "activity" => config('constant.REVIEW_ACTIVITY.START'), "created_at" => $startActivityDate, "updated_at" => $startActivityDate];
                    } else if($review->current_status == 2) {
                        $last_header_data = \DB::select("SELECT id,header_type FROM `public_review_question_headers` WHERE global_question_id = (SELECT global_question_id FROM public_review_products WHERE id = '$products[$i]') AND header_selection_type = 2");
                        $entryData = [
                            ["profile_id" => $review->profile_id, "product_id" => $products[$i], "activity" => config('constant.REVIEW_ACTIVITY.START'), "created_at" => $startActivityDate, "updated_at" => $startActivityDate],
                            ["profile_id" => $review->profile_id, "product_id" => $products[$i], "header_id" => $last_header_data[0]->id, "header_title" => $last_header_data[0]->header_type,"activity" => config('constant.REVIEW_ACTIVITY.END'), "created_at" => $review->max, "updated_at" => $review->max]
                        ];
                    } 
                    PublicReviewEntryMapping::insert($entryData);
                }
            }

            //already added users for this product
            $profile_ids = PublicReviewUserTiming::where('product_id', $products[$i])->pluck('profile_id')->toArray();
            $profileIdsString = empty($profile_ids) ? '-1' : implode(',', $profile_ids);

            $startActivity = config("constant.REVIEW_ACTIVITY.START");
            $endActivity = config("constant.REVIEW_ACTIVITY.END");
            $reviewEntryData = \DB::select("SELECT activity, MIN(created_at) as min, MAX(created_at) as max, profile_id FROM `public_review_entry_mapping` where product_id='$products[$i]' AND (activity = '$startActivity' OR activity = '$endActivity') AND deleted_at IS NULL AND profile_id NOT IN ($profileIdsString) GROUP BY profile_id, activity");
            $profileWiseData = collect($reviewEntryData)->groupBy('profile_id')->toArray();

            foreach($profileWiseData as $profile_id => $profile){
                for($j=0; $j< count($profile); $j++){
                    if($profile[$j]->activity == 'start'){
                        $startDate = $profile[$j]->min;
                        $created_at = $profile[$j]->min;
                        $updated_at = $profile[$j]->min;
                    } else if($profile[$j]->activity == 'final_submit'){
                        $endDate = $profile[$j]->max;
                        $updated_at = $profile[$j]->max;
                    }
                }

                $data = ["profile_id" => $profile_id, "product_id" => $products[$i], "start_review" => $startDate, "current_status" => 1, "created_at" => $created_at, "updated_at" => $updated_at];
                $create = PublicReviewUserTiming::create($data);
                if(isset($create) && isset($endDate)){
                    $duration = strtotime($endDate) - strtotime($startDate);
                    $flag = $this->flagReview(Carbon::parse($startDate), $duration, $create->id, 'PublicReviewUserTiming', $profile_id, $products[$i]);
                    PublicReviewUserTiming::where('id', $create->id)->update(['current_status' => 2, 'end_review' => $endDate, 'duration' => $duration,'is_flag' => $flag]);
                }
            }
        }
    }
}
