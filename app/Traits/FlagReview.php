<?php


namespace App\Traits;
use Carbon\CarbonInterval;
use Carbon\Carbon;
use App\FlagReason;
use App\ModelFlagReason;
use App\Profile\User;
use App\V2\Collaborate;
use App\Surveys;
use App\PublicReviewProduct;


trait FlagReview
{
    public function flagReview($start_review, $duration, $model_id, $model, $user_id, $created_model_id){

        $adminIds = $this->getAdminIds($model, $created_model_id);
        $start_time = $start_review->format('H:i:s');
        $flag = false;
        if(!empty(User::where('id',$user_id)->first()->email)){
            $email = explode('@', User::where('id',$user_id)->first()->email, 2);
        }
        
        // review flagging based on some conditions
        $flag_reasons = FlagReason::get();
        foreach($flag_reasons as $reason){
            $reason_conditions = $reason->conditions()->pluck('condition_value', 'condition_slug')->toArray();
            $flag_reason_data = ['model_id' => $model_id, 'flag_reason_id' => $reason->id, 'reason' => config("constant.FLAG_REASONS_TEXT.".$reason->slug), 'slug' => config("constant.FLAG_SLUG.SYSTEM"), 'model' => $model, 'profile_id' => $adminIds['profile_id'], 'company_id' => $adminIds['company_id']];
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

            if($reason->slug == 'tagtaste_employee'){
                // Check if use is tagtaste'employee or not
                if ((isset($email[1]) && $email[1] == $reason_conditions['email_domain'])) {
                    // Add flagging reason
                    $this->addModelFlagReasons($flag_reason_data);
                    $flag = true;
                }
            }
        }

        return $flag;
    }

    public function flagUnflag($flag_request, $flag_reason, $flag_status, $loggedInProfileId, $model_id, $model){
        if(empty($flag_reason) && $flag_reason == '' && $flag_request == 1){
            return "Reason is required to flag a review";
        } else if(empty($flag_reason) && $flag_reason == '' && $flag_request == 0) {
            return "Reason is required to unflag a review";
        }

        // check if it's already flagged or unflagged
        if(isset($flag_request) && $flag_request == 1 && $flag_request == $flag_status){
            return "It is already flagged, it cannot be flagged again.";
        } else if(isset($flag_request) && $flag_request == 0 && $flag_request == $flag_status) {
            return "It is already Unflagged, it cannot be Unflagged again.";
        }
        $data = ['model_id' => $model_id, 'reason' => $flag_reason, 'slug' => config("constant.FLAG_SLUG.MANUAL".$flag_request), 'model' => $model, 'profile_id' => $loggedInProfileId];
        return $this->addModelFlagReasons($data);
    }

    public function flagLog($model_id, $model, $modelFlagReasons, $profiles, $companies){
        $systemFlagReasons = $modelFlagReasons->where('model_id', $model_id)->where('slug', config("constant.FLAG_SLUG.SYSTEM"));
        $manualFlagReasons = $modelFlagReasons->where('model_id', $model_id)->where('slug','<>',config("constant.FLAG_SLUG.SYSTEM"))->sortByDesc('created_at');
        $flag_logs = [];
        // Manually flagged reviews    
        foreach($manualFlagReasons as $modelFlagReason){
            if($modelFlagReason->slug == config("constant.FLAG_SLUG.MANUAL0")){
                $log['title'] = 'UNFLAGGED';
                $log['color_code'] = config("constant.FLAG_COLORS.unflag_color");
                $log['line_color_code'] = config("constant.FLAG_COLORS.unflag_line_color");
            } else {
                $log['title'] = 'FLAGGED';
                $log['color_code'] = config("constant.FLAG_COLORS.flag_color");
                $log['line_color_code'] = config("constant.FLAG_COLORS.flag_line_color");
            }
            $log['flag_text'] = $modelFlagReason->reason;
            $log['created_at'] = Carbon::parse($modelFlagReason->created_at)->format('d M Y, h:i:s A');
            $log['profile'] = $profiles->where('id', $modelFlagReason->profile_id)->first()->toArray();
            $flag_logs[] = $log;
            $log = [];
        }

        // system flagged review's reason
        if(!$systemFlagReasons->isEmpty()){
            $systemFlagReasons = $systemFlagReasons->groupBy('model_id')[$model_id];
            $log['title'] = 'FLAGGED';
            $log['color_code'] = config("constant.FLAG_COLORS.flag_color");
            $log['line_color_code'] = config("constant.FLAG_COLORS.flag_line_color");
            $reasons = $systemFlagReasons->pluck('reason')->toArray();
            $total_reasons = count($reasons);
            $sec_last_index = $total_reasons - 2;
            $log['flag_text'] = 'Flagged for';
            $reason_texts = '';
            if($total_reasons > 1){
                for($i=0; $i < $sec_last_index; $i++){
                    $reason_texts = $reason_texts.$reasons[$i].', ';
                }
                $reason_texts = $reason_texts.$reasons[$sec_last_index].' ';
                $log['flag_text'] = $log['flag_text'].' '.$reason_texts.'and '.$reasons[$total_reasons - 1].' (System Generated)';
            } else {
                $log['flag_text'] = $log['flag_text'].' '.$reason_texts.$reasons[0].' (System Generated)';
            }
            $otherData = $systemFlagReasons->first();
            $log['created_at'] = Carbon::parse($otherData->created_at)->format('d M Y, h:i:s A');
            if(!empty($otherData->company_id)){
                $log['company'] = $companies->where('id', $otherData->company_id)->first()->toArray();
            } else {
                $log['profile'] = $profiles->where('id', $otherData->profile_id)->first()->toArray();
            }
            $flag_logs[] = $log;
        }

        return $flag_logs;
    }

    private function addModelFlagReasons($data){
        //check if already exists or not
        $model_flag_reasons = new ModelFlagReason;
        $exists = null;
        if(isset($data['flag_reason_id'])){
            $exists = $model_flag_reasons->where('model_id', $data['model_id'])->where('flag_reason_id', $data['flag_reason_id'])->where('model', $data['model'])->exists();
        }
        if(!$exists){
            return $model_flag_reasons->create($data);
        }
    }

    private function getAdminIds($model, $model_id){
        $ids = [];
        // find model id and get profile_id, company_id from the relevant tables
        switch ($model) {
            case 'BatchAssign':
                $collaborateData = Collaborate::select('profile_id', 'company_id')->find($model_id);
                $ids['profile_id'] = $collaborateData->profile_id;
                $ids['company_id'] = $collaborateData->company_id;
                break;
            case 'SurveyAttemptMapping':
                $surveyData = Surveys::select('profile_id', 'company_id')->find($model_id);
                $ids['profile_id'] = $surveyData->profile_id;
                $ids['company_id'] = $surveyData->company_id;
                break;
            case 'PublicReviewUserTiming':
                $publicReviewData = PublicReviewProduct::select('company_id')->find($model_id);
                $ids['profile_id'] = null;
                $ids['company_id'] = $publicReviewData->company_id;
                break;
        }
        return $ids;
    }
}