<?php


namespace App\Traits;
use Carbon\CarbonInterval;


trait FlagReview
{
    public function flagReview($start_review, $duration){
        // review flagging based on some conditions
        $start_time = $start_review->format('H:i:s');

        // Min and Max time for conditional check
        $maxTime = '20:00:00'; // 8 PM
        $minTime = '10:00:00'; // 10 AM

        // Min and Max duration for conditional check
        $minDuration = CarbonInterval::minutes(3)->totalSeconds;
        $maxDuration = CarbonInterval::minutes(30)->totalSeconds;

        $flag = false;
        
        // Check if the time is greater than 8 PM OR less than 10 AM and duration is less than 3 mins OR greater than 30 mins
        if (($start_time >= $maxTime) || ($start_time < $minTime) || ($duration < $minDuration) || ($duration > $maxDuration)) {
            $flag = true;
        }
        return $flag;
    }
}