<?php


namespace App\Traits;
use App\Collaborate\BatchAssign;
use App\reviewLimit;
use App\PublicReviewUserTiming;
use Carbon\Carbon;


trait ReviewLimitations
{
   public function checkTimeInterval($profileId, $model){
        $timeInterval = reviewLimit::select('time_interval')->where('model', $model)->where('is_active', 1)->whereNull('deleted_at')->whereNull('review_count')->orderBy('created_at', 'desc')->first();
        if(isset($timeInterval) && !empty($timeInterval)){
            $profileLastReview = BatchAssign::select('end_review')->where('profile_id', $profileId)->where('current_status', 3)->orderBy('end_review', 'desc')->first();
            if(isset($profileLastReview) && !empty($profileLastReview)){
                $lastReviewDate = $profileLastReview->end_review;
                $lastReviewTimeInSeconds = (Carbon::parse($lastReviewDate))->timestamp;
                $currentTimeInSeconds = Carbon::now()->timestamp;
                $timeDifference = ($currentTimeInSeconds - $lastReviewTimeInSeconds) - 2;
                $interval = $timeInterval->time_interval;

                //check whether time interval is over or not
                if($timeDifference < $interval){
                    $data = [
                        "status" => false,
                        "block_type" => "review_timer",
                        "last_review_date" => $lastReviewDate,
                        "duration" => $interval,
                        "info" => config('constant.REVIEW_TIME_INTERVAL_POPUP')
                    ];
                    return $data;
                }
            }
        }
   }

   public function checkDailyReviewCount($profileId, $model){
        // get latest review count limit
        $reviewLimit = reviewLimit::select('review_count')->where('model', $model)->where('is_active', 1)->whereNull('deleted_at')->whereNull('time_interval')->orderBy('created_at', 'desc')->first();

        if(isset($reviewLimit) && !empty($reviewLimit)){
            $dbModel = ($model == 'collaborate') ? new BatchAssign() : new PublicReviewUserTiming();
            $completedStatus = ($model == 'collaborate') ? 3 : 2;
            $today = Carbon::now()->format('Y-m-d');
            $profileTodayReviews = $dbModel->where('profile_id', $profileId)->where('current_status', $completedStatus)->where('end_review','like','%'.$today.'%')->get()->count();
            // check whether user exceeds the daily review limit or not
            if($profileTodayReviews >= $reviewLimit->review_count){
                $data = [
                    "status" => false,
                    "block_type" => "daily_review_count",
                    "info" => config('constant.REVIEW_DAILY_LIMIT_POPUP')
                ];
                return $data;
            }
            dd("not entered");
        }  
   }
}