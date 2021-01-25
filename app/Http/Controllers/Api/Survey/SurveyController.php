<?php

namespace App\Http\Controllers\Api\Survey;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Survey;
use App\SurveyQuestionsType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Webpatser\Uuid\Uuid;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Survey $id)
    {
        return $id;
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
            'profile_id' => 'nullable|exists:profiles,id',
            'company_id' => 'nullable|exists:companies,id',
            'title' => 'required|max:191',
            'description' => 'required|max:5000 ',
            'image_meta' => 'nullable|json',
            'media_meta' => 'nullable|json',
            'form_json' => 'required|json|survey_question_form',
            'profile_updated_by' => 'nullable',
            'invited_profile_ids' => 'nullable',
            'expiry_date' => 'required',
            'state' => 'required|in:1,2'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'failed', 'errors' => $validator->messages(), 'result' => []], 400);
        }

        $return = ["status" => false, "message" => "Failed to create survery"];

        $prepData = $request->all();
        $prepData["id"] = (string) Uuid::generate(4);
        $prepData["is_active"] = 1;
        $prepData["profile_updated_by"] = null;
        if ($request->state == config("constant.SURVEY_STATES.PUBLISHED")) {
            $prepData["published_at"] = date("Y-m-d H:i:s");
        }

        $create = Survey::create($prepData);

        if (isset($create->id)) {
            $return = ["status" => true, "message" => "Survey Created", "id" => $create->id];
        }

        return response($return);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Survey $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:191',
            'description' => 'required|max:5000 ',
            'image_meta' => 'nullable|array',
            'media_meta' => 'nullable|array',
            'form_json' => 'required',
            'profile_updated_by' => 'nullable',
            'invited_profile_ids' => 'nullable',
            'expiry_date' => 'required',
            'state' => 'required|in:1,2'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'failed', 'errors' => $validator->messages(), 'result' => []], 400);
        }

        $prepData = (object)[];

        $create = Survey::where("id", "=", $id->id);

        if ($id->state != config("constant.SURVEY_STATES.PUBLISHED") && $request->state == config("constant.SURVEY_STATES.PUBLISHED")) {

            $prepData->published_at = date("Y-m-d H:i:s");
        }

        if ($request->has("image_meta")) {
            $prepData->state = $request->state;
        }
        $prepData->title = $request->title;
        $prepData->description = $request->description;
        if ($request->has("image_meta")) {
            $prepData->image_meta = $request->image_meta;
        }
        if ($request->has("media_meta")) {
            $prepData->image_meta = $request->image_meta;
        }
        $prepData->form_json = $request->form_json;
        if ($request->has("profile_updated_by")) {
            $prepData->profile_updated_by = $request->profile_updated_by;
        }
        if ($request->has("media_meta")) {
            $prepData->invited_profile_ids = $request->invited_profile_ids;
        }

        if ($request->has("expiry_date")) {
            $prepData->expiry_date = $request->expiry_date;
        }


        $create->update((array)$prepData);

        $return = ["status" => true, "message" => "Survey Updated"];

        return response($return);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Survey $id)
    {
        $return = ["status" => false, "message" => "Failed to delete survery"];
        $deleteSurvey = Survey::where("id", "=", $id->id)->update(["is_active" => 0, "deleted_at" => date("Y-m-d H:i:s")]);
        if ($deleteSurvey) {
            $return = ["status" => true, "message" => "Survey Deleted"];
        }
        return response($return);
    }

    public function question_list(){    
        $getListFromDb = SurveyQuestionsType::where("is_active","=",1)->get();
        return response($getListFromDb);
    }
}
