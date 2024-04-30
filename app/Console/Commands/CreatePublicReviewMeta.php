<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PublicReviewUserTiming;
use App\PublicReviewProduct;
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
            //already added users for this product
            $profile_ids = PublicReviewUserTiming::where('product_id', $products[$i])->pluck('profile_id')->toArray();
            $profileIdsString = implode(',', $profile_ids);

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
                if(isset($endDate)){
                    $data["end_review"] = $endDate;
                    $data["current_status"] = 2;
                    $data["duration"] = strtotime($endDate) - strtotime($startDate);
                }

                $create = PublicReviewUserTiming::create($data);
                $flag = $this->flagReview(Carbon::parse($startDate), $create->duration, $create->id, 'PublicReviewUserTiming', $profile_id, $products[$i]);
                PublicReviewUserTiming::where('id', $create->id)->update(['is_flag' => $flag]);
            }
        }
    }
}
