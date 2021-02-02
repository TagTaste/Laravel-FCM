<?php

namespace App\Http\Controllers\Api\Survey;

use Illuminate\Http\Request;
use App\Company;
use App\Events\Model\Subscriber\Create;

use App\Http\Controllers\Controller;
use App\Events\NewFeedable;


use App\SurveyAnswers;
use App\Surveys;
use App\SurveyQuestionsType;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tagtaste\Api\SendsJsonResponse;
use Webpatser\Uuid\Uuid;

class SurveyController extends Controller
{

    use SendsJsonResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $getSurvey = Surveys::where("id", "=", $id)->where("is_active","=",1)->first();

        $this->model = false;
        $this->messages = "Survey Doesn't Exists";
        if(empty($getSurvey)){
            $this->errors = ["Survey Doesn't Exists"];
            return $this->sendResponse();
        }
        $this->model = false;
        $this->messages = "Request successfull";
        $this->model[] = $id;
        return $this->sendResponse();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'nullable|exists:companies,id',
            'title' => 'required|max:191',
            'description' => 'required|max:5000 ',
            'image_meta' => 'nullable|json',
            'video_meta' => 'nullable|json',
            'form_json' => 'required|json|survey_question_form',
            'invited_profile_ids' => 'nullable',
            'expired_at' => 'required',
            'state' => 'required|in:1,2'
        ]);

        $this->model = false;
        $this->messages = "Survey Failed";
        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return $this->sendResponse();
        }

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if ($request->has('company_id')) {
            $companyId = $request->input('company_id');
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if(!$userBelongsToCompany){
                return $this->sendError("User does not belong to this company");
            }
        }
 
        $prepData["id"] = (string) Uuid::generate(4);
        $prepData["is_active"] = 1;
        $prepData["profile_id"] = $request->user()->id;
        $prepData["state"] = $request->state;
        $prepData["title"] = $request->title;
        $prepData["description"] = $request->description;
        $prepData["privacy_id"] = 1;

        if ($request->has("company_id")) {
            $prepData["company_id"] = $request->company_id;
        }
        if ($request->has("image_meta")) {
            $prepData["image_meta"] = $request->image_meta;
        }
        if ($request->has("video_meta")) {
            $prepData["video_meta"] = $request->video_meta;
        }
        if ($request->has("form_json")) {
            $prepData["form_json"] = $request->form_json;
        }
        if ($request->state == config("constant.SURVEY_STATES.PUBLISHED")) {
            $prepData["published_at"] = date("Y-m-d H:i:s");
        }
        if ($request->has("invited_profile_ids")) {
            $prepData["invited_profile_ids"] = $request->invited_profile_ids;
        }

        if ($request->has("expired_at")) {
            $prepData["expired_at"] = $request->expired_at;
        }

        $create = Surveys::create($prepData);

        $survey = Surveys::find($create->id);
        if (isset($create->id)) {
            $this->model = $create;
            $this->messages = "Survey Deleted Successfully";
        }

        if ($request->has('company_id')) {
            event(new NewFeedable($survey, $company));
        } else {
            event(new NewFeedable($survey, $request->user()->profile));
        }
        event(new Create($survey,$request->user()->profile));
        return $this->sendResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'nullable|exists:companies,id',
            'title' => 'required|max:191',
            'description' => 'required|max:5000 ',
            'image_meta' => 'nullable|json',
            'video_meta' => 'nullable|json',
            'form_json' => 'required|json|survey_question_form',
            'invited_profile_ids' => 'nullable',
            'expired_at' => 'required',
            'state' => 'required|in:1,2'
        ]);

        $this->model = false;
        $this->messages = "Survey Failed";
        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return $this->sendResponse();
        }

        $create = Surveys::where("id", "=", $id);
        $getSurvey = $create->first();
        
        if(empty($getSurvey)){
            $this->errors = ["Survey Id is Invalid"];
            return $this->sendResponse();
        }

        $checkIfResponsesReceived = SurveyAnswers::where("survey_id", "=", $id)->first();
        if (!empty($checkIfResponsesReceived)) {
            $this->errors = ["Cannot update survey once response is received"];
            return $this->sendResponse();
        }

        $prepData = (object)[];

        

        if ($getSurvey->state != config("constant.SURVEY_STATES.PUBLISHED") && $request->state == config("constant.SURVEY_STATES.PUBLISHED")) {
            $prepData->published_at = date("Y-m-d H:i:s");
        }
        $prepData->state = $request->state;
        $prepData->title = $request->title;
        $prepData->description = $request->description;

        if ($request->has("image_meta")) {
            $prepData->image_meta = $request->image_meta;
        }
        if ($request->has("video_meta")) {
            $prepData->video_meta = $request->video_meta;
        }
        $prepData->form_json = $request->form_json;
        if ($request->has("profile_updated_by")) {
            $prepData->profile_updated_by = $request->profile_updated_by;
        }
        if ($request->has("invited_profile_ids")) {
            $prepData->invited_profile_ids = $request->invited_profile_ids;
        }

        if ($request->has("expired_at")) {
            $prepData->expired_at = $request->expired_at;
        }


        $create->update((array)$prepData);

        $this->model = true;
        $this->messages = "Survey Updated Successfully";

        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Surveys $id)
    {
        $this->model = false;
        $this->messages = "Survey Delete Failed";
        $deleteSurvey = Surveys::where("id", "=", $id->id)->update(["is_active" => 0, "deleted_at" => date("Y-m-d H:i:s")]);
        if ($deleteSurvey) {
            $this->model = true;
            $this->messages = "Survey Deleted Successfully";
        }
        return $this->sendResponse();
    }

    public function question_list()
    {
        $getListFromDb = SurveyQuestionsType::where("is_active", "=", 1)->get();
        $this->model = $getListFromDb;
        return $this->sendResponse();
    }

    public function saveAnswers(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'survey_id' => 'required|exists:surveys,id',
                'current_status' => 'required|numeric',
                'answer_json' => 'required|json|survey_answer_scrutiny'
            ]);


            $this->model = false;
            $this->messages = "Answer Submission Failed";
            if ($validator->fails()) {
                $this->errors = $validator->messages();
                return $this->sendResponse();
            }

            $id = Surveys::where("id", "=", $request->id)->first();
            if (isset($id->profile_id) && $id->profile_id == $request->profile_id) {
                $this->errors = ["Admin Cannot Fill the Surveys"];
                return $this->sendResponse();
            }

            $checkIFAlreadyFilled = SurveyAnswers::where("survey_id", "=", $request->survey_id)->where('profile_id', "=", $request->user()->id)->first();
            
            if (!empty($checkIFAlreadyFilled) && $checkIFAlreadyFilled->current_status == config("constant.SURVEY_STATUS.COMPLETED")) {
                $this->errors = ["Survey is already completed"];
                return $this->sendResponse();
            }

            $optionArray = json_decode($request->answer_json, true);
            DB::beginTransaction();
            $commit = true;
            foreach ($optionArray as $values) {
                $answerArray = [];
                $answerArray["profile_id"] = $request->user()->id;
                $answerArray["survey_id"] = $request->survey_id;
                $answerArray["question_id"] = $values["question_id"];
                $answerArray["question_type"] = $values["question_type_id"];
                $answerArray["current_status"] = $request->current_status;
                foreach ($values["option"] as $optVal) {
                    $answerArray["option_id"] = $optVal["id"];
                    $answerArray["option_type"] = $optVal["option_type"];
                    $answerArray["answer_value"] = $optVal["value"];
                    $answerArray["is_active"] = 1;
                    if (isset($optVal["video_meta"]) && !empty($optVal["video_meta"])) {
                        $answerArray["image_meta"] = $optVal["image_meta"];
                    }
                    if (isset($optVal["video_meta"]) && !empty($optVal["video_meta"])) {
                        $answerArray["video_meta"] = $optVal["video_meta"];
                    }
                    if (isset($optVal["video_meta"]) && !empty($optVal["video_meta"])) {
                        $answerArray["document_meta"] = $optVal["document_meta"];
                    }
                    if (isset($optVal["video_meta"]) && !empty($optVal["video_meta"])) {
                        $answerArray["media_url"] = $optVal["media_url"];
                    }
                    $surveyAnswer = SurveyAnswers::create($answerArray);
                    if (!$surveyAnswer) {
                        $commit = false;
                    }
                }
            }
            if ($commit) {
                DB::commit();
                $this->model = true;
                $this->messages = "Answer Submitted succesfully";
            }

            return $this->sendResponse();
        } catch (Exception $ex) {
            echo $ex->getMessage() . " " . $ex->getLine();
            DB::rollback();
        }
    }
}
