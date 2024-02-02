<?php


namespace App\Traits;
use Carbon\CarbonInterval;
use Carbon\Carbon;
use App\FlagReason;
use App\ModelFlagReason;


trait FlagReview
{
    public function flagReview($start_review, $duration, $model_id, $model){

        $start_time = $start_review->format('H:i:s');
        $flag = false;

        // review flagging based on some conditions
        $flag_reasons = FlagReason::get();
        foreach($flag_reasons as $reason){
            $reason_conditions = $reason->conditions()->pluck('condition_value', 'condition_slug')->toArray();
            $flag_reason_data = ['model_id' => $model_id, 'flag_reason_id' => $reason->id, 'model' => $model];
            if($reason->slug == 'review_duration'){
                // Check if the duration is less than 3 mins OR greater than 45 mins
                if (($duration < $reason_conditions['min_duration']) || ($duration > $reason_conditions['max_duration'])) {
                    // Add flagging reason
                    $this->addModelFlagReasons($flag_reason_data);
                    $flag = true;
                }
            }

            if($reason->slug == 'review_start_time'){
                // Check if the time is greater than 10 PM OR less than 8 AM 
                if (($start_time > $reason_conditions['max_start_time']) || ($start_time < $reason_conditions['min_start_time'])) {
                    // Add flagging reason
                    $this->addModelFlagReasons($flag_reason_data);
                    $flag = true;
                }
            }
        }

        return $flag;
    }

    private function addModelFlagReasons($data){
        ModelFlagReason::create($data);
    }
}