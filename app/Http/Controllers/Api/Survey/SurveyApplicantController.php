<?php

namespace App\Http\Controllers\Api\Survey;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Recipe\Profile;
use App\surveyApplicants;
use App\Surveys;
use Illuminate\Support\Facades\Redis;
use SurveyApplicants as GlobalSurveyApplicants;
use Tagtaste\Api\SendsJsonResponse;

class SurveyApplicantController extends Controller
{

    use SendsJsonResponse;
    public function __construct(Surveys $model)
    {
        $this->model = $model;
    }

    public function showInterest($id, Request $request)
    {

        $checkIFExists = $this->model->where("id", "=", $id)->first();
        if (empty($checkIFExists)) {
            return $this->sendError("Invalid Survey");
        }

        $checkIfAlreadyInterested = surveyApplicants::where("profile_id", $request->user()->profile->id)->where("survey_id", $id)->whereNull('deleted_at')->first();

        if (!empty($checkIfAlreadyInterested)) {
            return $this->sendError("Already Shown Interest");
        }

        if ($checkIFExists->profile_id && $request->user()->profile->id) {
            return $this->sendError("Admins Cannot Show Interest");
        }

        $profile = Profile::where('id', $request->user()->profile->id)->first();


        $data = [
            'profile_id' => $request->user()->profile->id, 'survey_id' => $id,
            'message' => ($request->message ?? null),
            'age_group' => $profile->ageRange ?? null, 'gender' => $profile->gender ?? null, 'hometown' => $profile->hometown ?? null, 'current_city' => $profile->city ?? null, "application_status" => (int)config("app.constant.SURVEY_APPLICANT_ANSWER_STATUS.INVITED"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")
        ];

        $create = surveyApplicants::create($data);

        if (isset($create->id)) {
            $profile = $request->user()->profile->id;
            // Redis::sAdd("surveys:$id:profile:$request->user()->profile_id:", $batch->id);
            Redis::set("application_status:$id:profile:$profile", 0);
            $this->model = true;
        } else {
            $this->model = false;
            $this->errors[] = "Failed to show interest in survey";
        }
        return $this->sendResponse();
    }
}
