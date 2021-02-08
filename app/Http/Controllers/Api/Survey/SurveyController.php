<?php

namespace App\Http\Controllers\Api\Survey;

use Illuminate\Http\Request;
use App\Company;
use App\Events\Model\Subscriber\Create;

use App\Http\Controllers\Controller;
use App\Events\NewFeedable;
use App\Events\UpdateFeedable;
use App\Events\DeleteFeedable;
use App\Events\Actions\Like;
use App\PeopleLike;
use App\SurveyAnswers;
use App\Surveys;
use App\SurveysLike;
use App\SurveyQuestionsType;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tagtaste\Api\SendsJsonResponse;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Redis;

class SurveyController extends Controller
{

    use SendsJsonResponse;

    protected $model;

    public function __construct(Surveys $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $getSurvey = Surveys::where("id", "=", $id)->where("is_active", "=", 1)->first();

        $this->model = false;
        $this->messages = "Survey Doesn't Exists";
        if (empty($getSurvey)) {
            $this->errors = ["Survey Doesn't Exists"];
            return $this->sendResponse();
        }
        $getSurvey["form_json"] = json_decode($getSurvey["form_json"], true);
        $this->messages = "Request successfull";
        $this->model = [
            "surveys" => $getSurvey,
            "meta" => $getSurvey->getMetaFor($request->user()->profile->id),
            "seoTags" => $getSurvey->getSeoTags(),
        ];
        return $this->sendResponse();
    }

    public function getMySurvey(Request $request)
    {
        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $surveys = $this->model->orderBy('state', 'asc')->orderBy('created_at', 'desc');
        $profileId = $request->user()->profile->id;
        $title = isset($request->title) ? $request->title : null;

        $this->model = [];
        $data = [];

        //Get compnaies of the logged in user.
        $companyIds = \DB::table('company_users')->where('profile_id', $profileId)->pluck('company_id');

        $surveys = $surveys->where('profile_id', $profileId)
            ->orWhereIn('company_id', $companyIds);

        if (!is_null($title)) {
            $surveys = $surveys->where('title', 'like', '%' . $title . '%');
        }
        $this->model['count'] = $surveys->count();

        $surveys = $surveys->skip($skip)->take($take)
            ->get();
        foreach ($surveys as $survey) {
            $survey->image_meta = json_decode($survey->image_meta);
            $survey->video_meta = json_decode($survey->video_meta);
            $data[] = [
                'survey' => $survey,
                'meta' => $survey->getMetaFor($profileId)
            ];
        }
        $this->model['surveys'] = $data;
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
            'image_meta' => 'array',
            'video_meta' => 'array',
            'form_json' => 'required',
            'invited_profile_ids' => 'nullable|array',
            'expired_at' => 'date_format:Y-m-d',
            'state' => 'required|in:1,2'
        ]);


        $this->model = false;
        $this->messages = "Survey Failed";
        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return $this->sendResponse();
        }


        $final_json = $this->validateSurveyFormJson($request);

        if (!empty($this->errors)) {
            return $this->sendResponse();
        }

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if ($request->has('company_id')) {
            $companyId = $request->input('company_id');
            $userId = $request->user()->profile->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendError("User does not belong to this company");
            }
        }

        $prepData["id"] = (string) Uuid::generate(4);
        $prepData["is_active"] = 1;
        $prepData["profile_id"] = $request->user()->profile->id;
        $prepData["state"] = $request->state;
        $prepData["title"] = $request->title;
        $prepData["description"] = $request->description;
        $prepData["privacy_id"] = 1;

        if ($request->has("company_id")) {
            $prepData["company_id"] = $request->company_id;
        }
        if ($request->has("image_meta")) {
            $prepData["image_meta"] = (is_array($request->image_meta) ? json_encode($request->image_meta) : $request->image_meta);
        }
        if ($request->has("video_meta")) {
            $prepData["video_meta"] = (is_array($request->video_meta) ? json_encode($request->video_meta) : $request->video_meta);
        }

        if ($request->has("form_json")) {
            // $prepData["form_json"] = (is_array($request->form_json) ? json_encode($request->form_json) : $request->form_json);
            $prepData["form_json"] = json_encode($final_json);
        }

        if ($request->state == config("constant.SURVEY_STATES.PUBLISHED")) {
            $prepData["published_at"] = date("Y-m-d H:i:s");
        }
        if ($request->has("invited_profile_ids")) {
            $prepData["invited_profile_ids"] = (is_array($request->invited_profile_ids) ? json_encode($request->invited_profile_ids) : $request->invited_profile_ids);
        }

        if ($request->has("expired_at")) {
            $prepData["expired_at"] = date("Y-m-d", strtotime($request->expired_at));
        }

        $create = Surveys::create($prepData);
        $create->image_meta = json_decode($create->image_meta);
        $create->video_meta = json_decode($create->video_meta);
        // $create->form_json = json_decode($create->final_json);
        $create->form_json = $final_json;

        if (isset($create->id)) {
            $survey = Surveys::find($create->id);
            $this->model = $create;
            $this->messages = "Survey Created Successfully";
            if ($survey->state == '2') {
                if ($request->has('company_id')) {
                    event(new NewFeedable($survey, $company));
                } else {
                    event(new NewFeedable($survey, $request->user()->profile));
                }
                event(new Create($survey, $request->user()->profile));

                $survey->addToCache();
                event(new UpdateFeedable($survey));
            }
        }
        return $this->sendResponse();
    }


    public function similarSurveys(Request $request, $surveyId)
    {
        $survey = $this->model->where('id', $surveyId)->first();
        if ($survey == null) {
            return $this->sendError("Invalid Survey Id");
        }

        $profileId = $request->user()->profile->id;
        $surveys = $this->model->where('state', 2)
            ->whereNull('deleted_at')
            ->inRandomOrder()
            ->take(3)->get();

        $this->model = [];
        foreach ($surveys as $survey) {
            $meta = $survey->getMetaFor($profileId);
            $survey->image_meta = json_decode($survey->image_meta);
            $survey->video_meta = json_decode($survey->video_meta);
            $this->model[] = ['surveys' => $survey, 'meta' => $meta];
        }
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

        $this->model = false;
        $this->messages = "Survey Failed";

        $create = Surveys::where("id", "=", $id);
        $getSurvey = $create->first();

        if (empty($getSurvey)) {
            $this->errors = ["Survey Id is Invalid"];
            return $this->sendResponse();
        }

        $validator = Validator::make($request->all(), [
            'company_id' => 'nullable|exists:companies,id',
            'title' => 'required|max:191',
            'description' => 'required|max:5000 ',
            'image_meta' => 'array',
            'video_meta' => 'array',
            'form_json' => 'required|array',
            'invited_profile_ids' => 'nullable',
            'expired_at' => 'date_format:Y-m-d',
            'state' => 'required|in:1,2'
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return $this->sendResponse();
        }

        $final_json = $this->validateSurveyFormJson($request, true);

        if (!empty($this->errors)) {
            return $this->sendResponse();
        }

        $checkIfResponsesReceived = SurveyAnswers::where("survey_id", "=", $id)->first();
        if (!empty($checkIfResponsesReceived)) {
            $this->errors = ["Cannot update survey once response is received"];
            return $this->sendResponse();
        }

        if ($getSurvey->state == config("constant.SURVEY_STATES.PUBLISHED") && $request->state == config("constant.SURVEY_STATES.DRAFT")) {
            $this->errors = ["Survey Once Published cannot be changed to draft"];
            return $this->sendResponse();
        }

        $prepData = (object)[];

        $oldState = $getSurvey->state;
        $newReqState = $request->state || null;

        if ($getSurvey->state != config("constant.SURVEY_STATES.PUBLISHED") && $request->state == config("constant.SURVEY_STATES.PUBLISHED")) {
            $prepData->published_at = date("Y-m-d H:i:s");
        } else if ($getSurvey->state != config("constant.SURVEY_STATES.DRAFT") && $request->state == config("constant.SURVEY_STATES.DRAFT")) {
            $this->errors = ["Cannot update survey back to draft once its published"];
            return $this->sendResponse();
        }

        $prepData->state = $request->state;
        $prepData->title = $request->title;
        $prepData->description = $request->description;

        if ($request->has("image_meta")) {
            $prepData->image_meta = (is_array($request->image_meta) ? json_encode($request->image_meta) : $request->image_meta);
        }

        if ($request->has("video_meta")) {
            $prepData->video_meta = (is_array($request->video_meta) ? json_encode($request->video_meta) : $request->video_meta);
        }

        if ($request->has("form_json")) {
            $prepData->form_json = json_encode($final_json);
            // $prepData->form_json = (is_array($request->form_json) ? json_encode($request->form_json) : $request->form_json);
        }

        if ($request->has("profile_updated_by")) {
            $prepData->profile_updated_by = $request->user()->profile->id;
        }
        if ($request->has("invited_profile_ids")) {
            $prepData->invited_profile_ids = (is_array($request->invited_profile_ids) ? json_encode($request->invited_profile_ids) : $request->invited_profile_ids);
        }

        if ($request->has("expired_at")) {
            $prepData->expired_at = date("Y-m-d", strtotime($request->expired_at));
        }


        $create->update((array)$prepData);

        $this->model = true;
        $this->messages = "Survey Updated Successfully";

        if ($getSurvey->state == config("constant.SURVEY_STATES.DRAFT") && $request->state == config("constant.SURVEY_STATES.PUBLISHED")) {
            //create new cache
            $getSurvey = $create->first();
            if ($request->has('company_id')) {
                event(new NewFeedable($getSurvey, $request->company_id));
            } else {
                event(new NewFeedable($getSurvey, $request->user()->profile));
            }
            event(new Create($getSurvey, $request->user()->profile));
        } else if ($getSurvey->state == config("constant.SURVEY_STATES.PUBLISHED")) {
            //update cache
            $getSurvey = $create->first();
            $getSurvey->addToCache();
            event(new UpdateFeedable($getSurvey));
        }
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
        $delete = Surveys::where("id", "=", $id->id);
        $survey = $delete->first();

        $this->messages = "Survey Delete Failed";
        $deleteSurvey = Surveys::where("id", "=", $id->id)->update(["is_active" => 0, "deleted_at" => date("Y-m-d H:i:s")]);
        if ($deleteSurvey) {
            $this->model = true;
            $this->messages = "Survey Deleted Successfully";
            event(new DeleteFeedable($survey));
            $survey->removeFromCache();
        }
        return $this->sendResponse();
    }

    public function like(Request $request, $surveyId)
    {
        $profileId = $request->user()->profile->id;
        $key = "meta:surveys:likes:" . $surveyId;
        $surveyLike = Redis::sIsMember($key, $profileId);
        $this->model = [];

        if ($surveyLike) {
            SurveysLike::where('profile_id', $profileId)->where('surveys_id', $surveyId)->delete();
            Redis::sRem($key, $profileId);
            $this->model['liked'] = false;
        } else {
            SurveysLike::insert(['profile_id' => $profileId, 'surveys_id' => $surveyId]);
            Redis::sAdd($key, $profileId);
            $this->model['liked'] = true;
            $recipe = Surveys::find($surveyId);
            event(new Like($recipe, $request->user()->profile));
        }
        $this->model['likeCount'] = Redis::sCard($key);

        $peopleLike = new PeopleLike();
        $this->model['peopleLiked'] = $peopleLike->peopleLike($surveyId, "surveys", request()->user()->profile->id);

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
                'answer_json' => 'required|array|survey_answer_scrutiny'
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

            $checkIFAlreadyFilled = SurveyAnswers::where("survey_id", "=", $request->survey_id)->where('profile_id', "=", $request->user()->profile->id)->first();

            if (!empty($checkIFAlreadyFilled) && $checkIFAlreadyFilled->current_status == config("constant.SURVEY_STATUS.COMPLETED")) {
                $this->errors = ["Survey is already completed"];
                return $this->sendResponse();
            }

            $optionArray = (is_array($request->answer_json) ? json_decode($request->answer_json, true) : $request->answer_json);
            DB::beginTransaction();
            $commit = true;
            foreach ($optionArray as $values) {
                $answerArray = [];
                $answerArray["profile_id"] = $request->user()->profile->id;
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


    public function reports($id, Request $request)
    {

        $checkIFExists = $this->model->where("id", "=", $id)->first();

        $colorCodeList = ["#fcba03", "#fcda02", "#fcpa0g", "#fcfa12", "#acaaf3", "#fcba03", "#faac11"];


        if (empty($checkIFExists)) {
            return $this->sendError("Invalid Survey");
        }

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $request->input('company_id');
            $userId = $request->user()->profile->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            return $this->sendError("Only Survey Admin can view this report");
        }

        $getSurveyAnswers = SurveyAnswers::where("survey_id", "=", $id);

        $getCount = $getSurveyAnswers->groupBy("profile_id")->get();

        $prepareNode = ["answer_count" => $getCount->count(), "reports" => []];

        $getJson = json_decode($checkIFExists["form_json"], true);
        $counter = 0;

        foreach ($getJson as $values) {
            shuffle($colorCodeList);
            $answers = SurveyAnswers::where("survey_id", "=", $id)->where("question_type", "=", $values["question_type"])->where("question_id", "=", $values["id"])->groupBy("profile_id")->get();
            $getAvg = $this->array_avg($answers->pluck("option_id")->toArray());
            $prepareNode["reports"][$counter]["question_id"] = $values["id"];
            $prepareNode["reports"][$counter]["title"] = $values["title"];
            $prepareNode["reports"][$counter]["question_type"] = $values["question_type"];


            $optCounter = 0;
            foreach ($values["options"] as $optVal) {
                // $prepareNode["reports"][$counter]["option"][$optCounter] = $optVal;
                $prepareNode["reports"][$counter]["option"][$optCounter]["id"] = $optVal["id"];
                $prepareNode["reports"][$counter]["option"][$optCounter]["value"] = $optVal["title"];
                $prepareNode["reports"][$counter]["option"][$optCounter]["option_type"] = $optVal["option_type"];
                if ($values["question_type"] != 5) {
                    $prepareNode["reports"][$counter]["option"][$optCounter]["answer_count"] = (isset($getAvg[$optVal["id"]]) ? $getAvg[$optVal["id"]]["count"] : 0);
                    $prepareNode["reports"][$counter]["option"][$optCounter]["answer_percentage"] = (isset($getAvg[$optVal["id"]]) ? $getAvg[$optVal["id"]]["avg"] : 0);
                    $prepareNode["reports"][$counter]["option"][$optCounter]["color_code"] = (isset($colorCodeList[$optCounter]) ? $colorCodeList[$optCounter] : "#fcda02");
                } else {
                    if ($answers->count() == 0) {
                        $prepareNode["reports"][$counter]["option"][$optCounter]["answer_count"] = 0;
                    } else {
                        $imageMeta = $videoMeta = $documentMeta = $mediaUrl = [];
                        foreach ($answers as $ansVal) {

                            if (count($imageMeta) < 10) {
                                $decodeImg = (!is_array($ansVal->image_meta) ?  json_decode($ansVal->image_meta, true) : $ansVal->image_meta);
                                if (is_array($decodeImg) && !empty($decodeImg)) {
                                    array_map(function ($value) use ($ansVal, &$imageMeta) {
                                        $imageMeta[] = ["url" => $value["tiny_photo"], "author" => $ansVal->profile->name];
                                    }, $decodeImg);
                                }
                            }

                            if (count($videoMeta) < 10) {
                                $decodeVid = (!is_array($ansVal->video_meta) ?  json_decode($ansVal->video_meta, true) : $ansVal->video_meta);
                                if (is_array($decodeVid) && !empty($decodeVid)) {
                                    array_map(function ($value) use ($ansVal, &$videoMeta) {
                                        $videoMeta[] = ["url" => $value["tiny_photo"], "author" => $ansVal->profile->name];
                                    }, $decodeVid);
                                }
                            }

                            if (count($documentMeta) < 10) {
                                $decodeDoc = (!is_array($ansVal->document_meta) ?  json_decode($ansVal->document_meta, true) : $ansVal->document_meta);
                                if (is_array($decodeDoc) && !empty($decodeDoc)) {
                                    array_map(function ($value) use ($ansVal, &$documentMeta) {
                                        $documentMeta[] = ["url" => $value["tiny_photo"], "author" => $ansVal->profile->name];
                                    }, $decodeDoc);
                                }
                            }

                            if (count($mediaUrl) < 10) {
                                $decodeUrl = (!is_array($ansVal->media_url) ?  json_decode($ansVal->media_url, true) : $ansVal->media_url);
                                if (is_array($decodeUrl) && !empty($decodeUrl)) {
                                    array_map(function ($value) use ($ansVal, &$mediaUrl) {
                                        $mediaUrl[] = ["url" => $value["tiny_photo"], "author" => $ansVal->profile->name];
                                    }, $decodeUrl);
                                }
                            }
                        }
                        // $imageMeta = $answers->pluck("image_meta")->toArray();
                        $prepareNode["reports"][$counter]["option"][$optCounter]["files"]["image_meta"] = $imageMeta;

                        // $videoMeta = $answers->pluck("video_meta")->toArray();
                        $prepareNode["reports"][$counter]["option"][$optCounter]["files"]["video_meta"] = $videoMeta;
                        // $documentMeta = $answers->pluck("document_meta")->toArray();
                        $prepareNode["reports"][$counter]["option"][$optCounter]["files"]["document_meta"] = $documentMeta;
                        // $mediaUrl = $answers->pluck("media_url")->toArray();
                        $prepareNode["reports"][$counter]["option"][$optCounter]["files"]["media_url"] = $mediaUrl;
                    }
                }
                $optCounter++;
            }

            $answers = [];
            $counter++;
        }
        $this->messages = "Report Successful";
        $this->model = $prepareNode;
        return $this->sendResponse();
    }

    function array_avg($array, $round = 1)
    {
        $num = count($array);
        return array_map(
            function ($val) use ($num, $round) {
                return array('count' => $val, 'avg' => round($val / $num * 100, $round));
            },
            array_count_values($array)
        );
    }

    private function validateSurveyFormJson($request, $isUpdation = false)
    {
        //FORM JSON Validation;
        $decodeJson = (is_array($request->form_json) ? $request->form_json : json_decode($request->form_json, true));
        if (!empty($decodeJson)) {
            //required node for questions    
            $requiredNode = ["question_type", "title", "image_meta", "video_meta", "description", "id", "is_mandatory", "options"];
            //required option nodes
            $optionNodeChecker = ["id", "option_type", "image_meta", "video_meta", "title"];
            //getTypeOfQuestions
            $getListOfFormQuestions = SurveyQuestionsType::where("is_active", "=", 1)->get()->pluck("question_type_id")->toArray();
            $maxQueId = 1;
            if ($isUpdation) {
                $maxQueId = max(array_column($decodeJson, 'id'));
                $maxQueId++;
            }

            foreach ($decodeJson as &$values) {
                if (isset($values["question_type"]) && in_array($values["question_type"], $getListOfFormQuestions)) {
                    $diff = array_diff($requiredNode, array_keys($values));
                    // echo (isset($values['id']));
                    if (!$isUpdation || !isset($values['id']) || empty($values['id'])) {
                        $values['id'] = $maxQueId;
                        $maxQueId++;
                        // echo "cehcking que";
                        // echo $maxQueId;
                    }

                    if (empty($diff) && isset($values["options"])) {
                        $maxOptionId = 1;
                        if ($isUpdation) {
                            $maxOptionId = max(array_column($values["options"], 'id'));
                            $maxOptionId++;
                        }

                        foreach ($values["options"] as &$opt) {
                            if (!$isUpdation || !isset($opt['id']) || empty($opt['id'])) {
                                $opt['id'] = $maxOptionId;
                                $maxOptionId++;
                            }
                            $diffOptions = array_diff($optionNodeChecker, array_keys($opt));
                            if (!empty($diffOptions)) {
                                $this->errors["form_json"] = "Option Nodes Missing " . implode(",", $diffOptions);
                            }
                        }
                    } else {
                        $this->errors["form_json"] = "Question Nodes Missing " . implode(",", $diff);
                    }
                } else {
                    $this->errors["form_json"] = "Invalid Question Type " . $values["question_type"];
                }
            }
            // echo '<pre>'; print_r($decodeJson); echo '</pre>';


        } else {
            $this->errors["image_meta"] = "Invalid Form Json";
        }
        if (!is_array($request->image_meta)) {
            $this->errors["image_meta"] = "The image meta must be an array.";
        }

        if (!is_array($request->video_meta)) {
            $this->errors["video_meta"] = "The image meta must be an array.";
        }
        return $decodeJson;
    }

    public function surveyRespondents($id, Request $request)
    {
        $checkIFExists = $this->model->where("id", "=", $id)->first();
        if (empty($checkIFExists)) {
            return $this->sendError("Invalid Survey");
        }

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $request->input('company_id');
            $userId = $request->user()->profile->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            return $this->sendError("Only Survey Admin can view this report");
        }

        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $answers = SurveyAnswers::where("survey_id", "=", $id)->where("is_active", "=", 1)->orderBy('created_at', 'desc');
        $profileId = $request->user()->profile->id;

        $this->model = [];
        $data = ["answer_count" => $answers->get()->count()];

        $this->model['count'] = $answers->count();

        $respondent = $answers->skip($skip)->take($take)
            ->get();
        foreach ($respondent as $profile) {
            $data['report'][] = $profile->profile;
        }
        $this->model = $data;
        return $this->sendResponse();
    }

    public function inputAnswers($id, $question_id, $option_id, Request $request)
    {

        $checkIFExists = $this->model->where("id", "=", $id)->first();
        if (empty($checkIFExists)) {
            return $this->sendError("Invalid Survey");
        }

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $request->input('company_id');
            $userId = $request->user()->profile->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            return $this->sendError("Only Survey Admin can view this report");
        }


        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $answers = SurveyAnswers::where("survey_id", "=", $id)->where("question_id", "=", $question_id)->where("option_id", "=", $option_id)->where("is_active", "=", 1)->orderBy('created_at', 'desc');

        $this->model = [];
        $data = ["answer_count" => $answers->get()->count()];

        $this->model['count'] = $answers->count();

        $respondent = $answers->skip($skip)->take($take)
            ->get();

        foreach ($respondent as $profile) {
            $data["report"][] = ["profile" => $profile->profile, "answer" => $profile->answer_value];
        }

        $this->model = $data;
        return $this->sendResponse();
    }

    public function userReport($id, $profile_id, Request $request)
    {

        $checkIFExists = $this->model->where("id", "=", $id)->first();
        if (empty($checkIFExists)) {
            return $this->sendError("Invalid Survey");
        }

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $request->input('company_id');
            $userId = $request->user()->profile->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            return $this->sendError("Only Survey Admin can view this report");
        }

        $colorCodeList = ["#fcba03", "#fcda02", "#fcpa0g", "#fcfa12", "#acaaf3", "#fcba03", "#faac11"];

        $prepareNode = ["reports" => []];

        $getJson = json_decode($checkIFExists["form_json"], true);
        $counter = 0;

        foreach ($getJson as $values) {
            shuffle($colorCodeList);
            $answers = SurveyAnswers::where("survey_id", "=", $id)->where("question_type", "=", $values["question_type"])->where("question_id", "=", $values["id"])->where("profile_id", "=", $profile_id)->first();

            if (!empty($answers)) {
                $prepareNode["reports"][$counter]["question_id"] = $values["id"];
                $prepareNode["reports"][$counter]["title"] = $values["title"];
                $prepareNode["reports"][$counter]["question_type"] = $values["question_type"];
                $prepareNode["reports"][$counter]["image_meta"] = (!is_array($values["image_meta"]) ? json_decode($values["image_meta"]) : $values["image_meta"]);
                $prepareNode["reports"][$counter]["video_meta"] = (!is_array($values["video_meta"]) ? json_decode($values["video_meta"]) : $values["video_meta"]);
                $optCounter = 0;
                foreach ($values["options"] as $optVal) {
                    $prepareNode["reports"][$counter]["option"][$optCounter]["id"] = $optVal["id"];
                    $prepareNode["reports"][$counter]["option"][$optCounter]["option_type"] = $optVal["option_type"];

                    $prepareNode["reports"][$counter]["option"][$optCounter]["value"] = $answers->answer_value;

                    if ($values["question_type"] != 5) {
                        $prepareNode["reports"][$counter]["option"][$optCounter]["color_code"] = (isset($colorCodeList[$optCounter]) ? $colorCodeList[$optCounter] : "#fcda02");
                    } else {
                        // $imageMeta = $answers->pluck("image_meta")->toArray();
                        $prepareNode["reports"][$counter]["option"][$optCounter]["files"]["image_meta"] = (!is_array($answers->image_meta) ? json_decode($answers->image_meta, true) : $answers->image_meta);

                        // $videoMeta = $answers->pluck("video_meta")->toArray();
                        $prepareNode["reports"][$counter]["option"][$optCounter]["files"]["video_meta"] = (!is_array($answers->video_meta) ? json_decode($answers->video_meta, true) : $answers->video_meta);

                        // $documentMeta = $answers->pluck("document_meta")->toArray();
                        $prepareNode["reports"][$counter]["option"][$optCounter]["files"]["document_meta"] = (!is_array($answers->document_meta) ? json_decode($answers->document_meta, true) : $answers->document_meta);

                        // $mediaUrl = $answers->pluck("media_url")->toArray();
                        $prepareNode["reports"][$counter]["option"][$optCounter]["files"]["media_url"] = (!is_array($answers->media_url) ? json_decode($answers->media_url, true) : $answers->media_url);
                    }
                }
                $optCounter++;

                $answers = [];

                $counter++;
            }
        }
        $this->messages = "Report Successful";
        $this->model = $prepareNode;
        return $this->sendResponse();
    }
}
