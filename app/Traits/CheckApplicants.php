<?php


namespace App\Traits;

use App\V1\Chat;
use App\Collaborate\Applicant;
use App\surveyApplicants;


trait CheckApplicants
{
    public function checkApplicants($chat_id, $profileIds, $loggedInProfileId){
        $model_data = Chat::select('model_name', 'model_id')->where('id', $chat_id)->first();
        $model_id = $model_data->model_id;
        $model_name = $model_data->model_name;
        if($model_name == config("constant.CHAT_MODEL_SUPPORT.COLLABORATE")){
            $applicants = Applicant::where('collaborate_id', $model_id)->whereNotNull('shortlisted_at')->whereNull('rejected_at')->where('profile_id', '<>', $loggedInProfileId)->pluck('profile_id')->toArray();
            $error = 'Something went wrong! Please ensure you can only add applicants of this tasting';
        } else if($model_name == config("constant.CHAT_MODEL_SUPPORT.SURVEY")) {
            $applicants = surveyApplicants::where('survey_id', $model_id)->whereNull('deleted_at')->whereNull('rejected_at')->where('profile_id', '<>', $loggedInProfileId)->pluck('profile_id')->toArray();
            $error = 'Something went wrong! Please ensure you can only add applicants of this survey';
        }

        return array_diff($profileIds, $applicants) ? [true, $error] : [false, ''];
        
    }
}