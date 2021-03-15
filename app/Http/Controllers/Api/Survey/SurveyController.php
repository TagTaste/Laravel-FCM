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
use App\Events\Actions\SurveyAnswered;
use App\PeopleLike;
use App\Profile;
use App\SurveyAnswers;
use App\Surveys;
use App\SurveysLike;
use App\SurveyQuestionsType;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tagtaste\Api\SendsJsonResponse;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\File;

class SurveyController extends Controller
{

    use SendsJsonResponse;

    protected $model;

    protected $colorCodeList = [
        "#F3C4CD", "#F1E6C7", "#D0DEEF", "#C1E2CF",
        "#C1E4E5", "#F2D9C6", "#C6ECF2", "#C6CEF2", "#DEC6F2", "#F2C6E1", "#CAD1D9", "#D9CAD9", "#D9CACC", "#E2D5C4", "#CBCBDE", "#DDDECB", "#E9D4E7", "#D7D4D5", "#ECE1D8", "#CBC3CD"
    ];

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
        $getSurvey["video_meta"] = json_decode($getSurvey["video_meta"], true);
        $getSurvey["image_meta"] = json_decode($getSurvey["image_meta"], true);

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
        $surveys = $this->model->where("is_active", "=", 1);
        if ($request->has('state') && !empty($request->input('state'))) {
            $surveys = $surveys->where("state", "=", $request->state);
        }

        $surveys = $surveys->orderBy('state', 'asc')->orderBy('created_at', 'desc');
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
            'title' => 'required',
            'description' => 'required',
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
            $userId = $request->user()->id;
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
        $survey = $this->model->where('id', $surveyId)->where("is_active", "=", 1)->first();
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
            'title' => 'required',
            'description' => 'required',
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

        $final_json = $this->validateSurveyFormJson($request, $id);

        if (!empty($this->errors)) {
            return $this->sendResponse();
        }

        $prepData = (object)[];

        if ($getSurvey->state != config("constant.SURVEY_STATES.PUBLISHED") && $request->state == config("constant.SURVEY_STATES.PUBLISHED")) {
            $prepData->published_at = date("Y-m-d H:i:s");
        }
        //  else if ($getSurvey->i != config("constant.SURVEY_STATES.DRAFT") && $request->state == config("constant.SURVEY_STATES.DRAFT")) {
        //     return $this->sendError("Cannot update survey back to draft once its published");
        // }

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

            $getSurvey->addToCache();
            event(new UpdateFeedable($getSurvey));
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

            if ($validator->fails()) {
                $this->errors = $validator->messages();
                return $this->sendResponse();
            }

            $id = $this->model->where("id", "=", $request->survey_id)->first();
            if (isset($id->profile_id) && $id->profile_id == $request->profile_id) {
                return $this->sendError("Admin Cannot Fill the Surveys");
            }

            $checkIFAlreadyFilled = SurveyAnswers::where("survey_id", "=", $request->survey_id)->where('profile_id', "=", $request->user()->profile->id)->first();

            // if (!empty($checkIFAlreadyFilled) && $checkIFAlreadyFilled->current_status == config("constant.SURVEY_STATUS.COMPLETED")) {
            //     return $this->sendError("Survey is already completed");
            // }

            $prepareQuestionJson = $this->prepQuestionJson($id->form_json);
            $optionArray = (!is_array($request->answer_json) ? json_decode($request->answer_json, true) : $request->answer_json);
            DB::beginTransaction();
            $commit = true;
            foreach ($optionArray as $values) {

                if (isset($prepareQuestionJson[$values["question_id"]]["is_mandatory"]) && $prepareQuestionJson[$values["question_id"]]["is_mandatory"] == true && (!isset($values["options"]) || empty($values["options"]))) {
                    DB::rollback();
                    return $this->sendError("Mandatory Questions Cannot Be Blank");
                }
                $answerArray = [];
                $answerArray["profile_id"] = $request->user()->profile->id;
                $answerArray["survey_id"] = $request->survey_id;
                $answerArray["question_id"] = $values["question_id"];
                $answerArray["question_type"] = $values["question_type_id"];
                $answerArray["current_status"] = $request->current_status;
                if (isset($values["options"]) && !empty($values["options"])) {
                    foreach ($values["options"] as $optVal) {
                        $answerArray["option_id"] = $optVal["id"];
                        $answerArray["option_type"] = $optVal["option_type"];
                        $answerArray["answer_value"] = $optVal["value"];
                        $answerArray["is_active"] = 1;
                        $answerArray["image_meta"] = ((isset($optVal["image_meta"])  && is_array($optVal["image_meta"])) ? json_encode($optVal["image_meta"]) : json_encode([]));

                        $answerArray["video_meta"] = ((isset($optVal["video_meta"])  && is_array($optVal["video_meta"])) ? json_encode($optVal["video_meta"]) : json_encode([]));

                        $answerArray["document_meta"] = ((isset($optVal["document_meta"])  && is_array($optVal["document_meta"])) ? json_encode($optVal["document_meta"]) : json_encode([]));

                        $answerArray["media_url"] = ((isset($optVal["media_url"])  && is_array($optVal["media_url"])) ? json_encode($optVal["media_url"]) : json_encode([]));


                        $surveyAnswer = SurveyAnswers::create($answerArray);

                        if (!$surveyAnswer) {
                            $commit = false;
                        }
                    }
                } else {
                    $answerArray["image_meta"] = $answerArray["video_meta"] = $answerArray["document_meta"] = $answerArray["media_url"] = json_encode([]);
                    $answerArray["is_active"] = 1;
                    $surveyAnswer = SurveyAnswers::create($answerArray);

                    if (!$surveyAnswer) {
                        $commit = false;
                    }
                }
            }
            $user = $request->user()->profile;
            if ($commit) {
                DB::commit();
                if (is_null($id->company_id)) {
                    event(new SurveyAnswered($id, $user, null, null, null, null));
                } else {
                    event(new SurveyAnswered($id, null, null, null, null, Company::where("id", "=", $id->company_id)));
                }
                $this->model = true;
                $this->messages = "Answer Submitted Successfully";
            }

            return $this->sendResponse();
        } catch (Exception $ex) {
            DB::rollback();
            return $this->sendError("Error Saving Answers " . $ex->getMessage() . " " . $ex->getFile() . " " . $ex->getLine());
        }
    }


    public function reports($id, Request $request)
    {

        $checkIFExists = $this->model->where("id", "=", $id)->first();

        $colorCodeList = $this->colorCodeList;


        if (empty($checkIFExists)) {
            return $this->sendError("Invalid Survey");
        }

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
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
            $answers = SurveyAnswers::where("survey_id", "=", $id)->where("question_type", "=", $values["question_type"])->where("question_id", "=", $values["id"])->get();
            $ans = $answers->pluck("option_id")->toArray();

            $ar = array_values(array_filter($ans));
            $getAvg = (count($ar) ? $this->array_avg($ar,$getCount->count()) : 0);
            $prepareNode["reports"][$counter]["question_id"] = $values["id"];
            $prepareNode["reports"][$counter]["title"] = $values["title"];
            $prepareNode["reports"][$counter]["question_type"] = $values["question_type"];
            $prepareNode["reports"][$counter]["image_meta"] = (!is_array($values["image_meta"]) ?  json_decode($values["image_meta"], true) : $values["image_meta"]);
            $prepareNode["reports"][$counter]["video_meta"] = (!is_array($values["video_meta"]) ?  json_decode($values["video_meta"], true) : $values["video_meta"]);

            $optCounter = 0;
            foreach ($values["options"] as $optVal) {
                // $prepareNode["reports"][$counter]["option"][$optCounter] = $optVal;
                $prepareNode["reports"][$counter]["options"][$optCounter]["id"] = $optVal["id"];
                $prepareNode["reports"][$counter]["options"][$optCounter]["value"] = $optVal["title"];
                $prepareNode["reports"][$counter]["options"][$optCounter]["option_type"] = $optVal["option_type"];

                $prepareNode["reports"][$counter]["options"][$optCounter]["image_meta"] = (!is_array($optVal["image_meta"]) ? json_decode($optVal["image_meta"], true) : $optVal["image_meta"]);

                $prepareNode["reports"][$counter]["options"][$optCounter]["video_meta"] = (!is_array($optVal["video_meta"]) ? json_decode($optVal["video_meta"], true) : $optVal["video_meta"]);

                if ($values["question_type"] != config("constant.MEDIA_SURVEY_QUESTION_TYPE")) {
                    $prepareNode["reports"][$counter]["options"][$optCounter]["answer_count"] = (isset($getAvg[$optVal["id"]]) ? $getAvg[$optVal["id"]]["count"] : 0);
                    $prepareNode["reports"][$counter]["options"][$optCounter]["answer_percentage"] = (isset($getAvg[$optVal["id"]]) ? $getAvg[$optVal["id"]]["avg"] : 0);
                    $prepareNode["reports"][$counter]["options"][$optCounter]["color_code"] = (isset($colorCodeList[$optCounter]) ? $colorCodeList[$optCounter] : "#fcda02");
                } else {
                    $prepareNode["reports"][$counter]["options"][$optCounter]["allowed_media"] = (isset($optVal["allowed_media"]) ? $optVal["allowed_media"] : []);
                    if ($answers->count() == 0) {
                        $prepareNode["reports"][$counter]["options"][$optCounter]["answer_count"] = 0;
                    } else {
                        $imageMeta = $videoMeta = $documentMeta = $mediaUrl = [];
                        foreach ($answers as $ansVal) {

                            if (count($imageMeta) < 10) {
                                $decodeImg = (!is_array($ansVal->image_meta) ?  json_decode($ansVal->image_meta, true) : $ansVal->image_meta);
                                if (is_array($decodeImg) && !empty($decodeImg)) {
                                    array_map(function ($value) use ($ansVal, &$imageMeta) {
                                        if (!empty($value)) {
                                            $meta = ["profile_id" => $ansVal->profile->id, "name" => $ansVal->profile->name, "handle" => $ansVal->profile->handle];
                                            $imageMeta[] = ["data" => $value, "meta" => $meta];
                                        }
                                    }, $decodeImg);
                                }
                            }

                            if (count($videoMeta) < 10) {
                                $decodeVid = (!is_array($ansVal->video_meta) ?  json_decode($ansVal->video_meta, true) : $ansVal->video_meta);
                                if (is_array($decodeVid) && !empty($decodeVid)) {
                                    array_map(function ($value) use ($ansVal, &$videoMeta) {
                                        if (!empty($value)) {
                                            $meta = ["profile_id" => $ansVal->profile->id, "name" => $ansVal->profile->name, "handle" => $ansVal->profile->handle];
                                            $videoMeta[] = ["data" => $value, "meta" => $meta];
                                        }
                                    }, $decodeVid);
                                }
                            }

                            if (count($documentMeta) < 10) {
                                $decodeDoc = (!is_array($ansVal->document_meta) ?  json_decode($ansVal->document_meta, true) : $ansVal->document_meta);
                                if (is_array($decodeDoc) && !empty($decodeDoc)) {

                                    array_map(function ($value) use ($ansVal, &$documentMeta) {
                                        if (!empty($value)) {
                                            $meta = ["profile_id" => $ansVal->profile->id, "name" => $ansVal->profile->name, "handle" => $ansVal->profile->handle];
                                            $documentMeta[] = ["data" => $value, "meta" => $meta];
                                        }
                                    }, $decodeDoc);
                                }
                            }

                            if (count($mediaUrl) < 10) {
                                $decodeUrl = (!is_array($ansVal->media_url) ?  json_decode($ansVal->media_url, true) : $ansVal->media_url);
                                if (is_array($decodeUrl) && !empty($decodeUrl)) {
                                    
                                    array_map(function ($value) use ($ansVal, &$mediaUrl) {
                                        if (!empty($value)) {
                                            $meta = ["profile_id" => $ansVal->profile->id, "name" => $ansVal->profile->name, "handle" => $ansVal->profile->handle];
                                            $mediaUrl[] = ["data" => $value, "meta" => $meta];;
                                        }
                                    }, $decodeUrl);
                                }
                            }
                        }
                        // $imageMeta = $answers->pluck("image_meta")->toArray();
                        $prepareNode["reports"][$counter]["options"][$optCounter]["files"]["image_meta"] = $imageMeta;

                        // $videoMeta = $answers->pluck("video_meta")->toArray();
                        $prepareNode["reports"][$counter]["options"][$optCounter]["files"]["video_meta"] = $videoMeta;
                        // $documentMeta = $answers->pluck("document_meta")->toArray();
                        $prepareNode["reports"][$counter]["options"][$optCounter]["files"]["document_meta"] = $documentMeta;
                        // $mediaUrl = $answers->pluck("media_url")->toArray();
                        $prepareNode["reports"][$counter]["options"][$optCounter]["files"]["media_url"] = $mediaUrl;
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

    function array_avg($array,$respCount = 0)
    {
        if (is_array($array) && count($array)) {
                $num = $respCount;
                return array_map(
                    function ($val) use ($num) {

                        return array('count' => $val, 'avg' =>  number_format((float)($val / $num * 100), 2, '.', ''));
                    },
                    array_count_values($array)
                );
        }       

        return false;
    }

    private function validateSurveyFormJson($request, $isUpdation = false)
    {
        //FORM JSON Validation;
        $decodeJson = (is_array($request->form_json) ? $request->form_json : json_decode($request->form_json, true));
        if (!empty($decodeJson)) {
            if ($isUpdation) {
                $getOldJson = Surveys::where("id", "=", $isUpdation)->select("form_json")->first()->toArray();
                $oldJsonArray = $this->prepQuestionJson($getOldJson["form_json"]);
                $listOfQuestionIds = array_keys($oldJsonArray);
            }
            //required node for questions    
            $requiredNode = ["question_type", "title", "image_meta", "video_meta", "description", "id", "is_mandatory", "options"];
            //required option nodes
            $optionNodeChecker = ["id", "option_type", "image_meta", "video_meta", "title"];
            //getTypeOfQuestions
            $getListOfFormQuestions = SurveyQuestionsType::where("is_active", "=", 1)->get()->pluck("question_type_id")->toArray();
            $maxQueId = 1;
            if ($isUpdation) {
                $maxQueId = max($listOfQuestionIds);
                $maxQueId++;
            }

            foreach ($decodeJson as &$values) {
                if (isset($values["question_type"]) && in_array($values["question_type"], $getListOfFormQuestions)) {
                    $diff = array_diff($requiredNode, array_keys($values));
                    // echo (isset($values['id']));

                    if (!$isUpdation || !isset($values['id']) || empty($values['id'])) {
                        $values['id'] = (int) $maxQueId;
                        $maxQueId++;
                    }


                    if (empty($diff) && isset($values["options"])) {
                        $maxOptionId = 1;
                        if ($isUpdation) {
                            if (isset($oldJsonArray[$values["id"]]["options"])) {
                                $allOpts = array_column($oldJsonArray[$values["id"]]["options"], "id");
                                $maxOptionId = (is_array($allOpts) && !empty($allOpts) ? max($allOpts) : max(array_column($values["options"], 'id')));
                            } else {
                                $maxOptionId = max(array_column($values["options"], 'id'));
                            }
                            $maxOptionId++;
                        }


                        foreach ($values["options"] as &$opt) {
                            if (!$isUpdation || !isset($opt['id']) || empty($opt['id'])) {
                                $opt['id'] = (int)$maxOptionId;
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
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
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
        $answers = SurveyAnswers::where("survey_id", "=", $id)->where("is_active", "=", 1)->orderBy('created_at', 'desc')->groupBy("profile_id");
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
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
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
        $data = ["answer_count" => $answers->get()->count(), "report" => []];

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
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            return $this->sendError("Only Survey Admin can view this report");
        }

        $colorCodeList = $this->colorCodeList;

        $prepareNode = ["reports" => []];

        $getJson = json_decode($checkIFExists["form_json"], true);
        $counter = 0;

        foreach ($getJson as $values) {
            shuffle($colorCodeList);
            $answers = SurveyAnswers::where("survey_id", "=", $id)->where("question_type", "=", $values["question_type"])->where("question_id", "=", $values["id"])->where("profile_id", "=", $profile_id)->get();

            $pluckOpId = $answers->pluck("option_id")->toArray();

            if ($answers->count()) {
                $prepareNode["reports"][$counter]["question_id"] = $values["id"];
                $prepareNode["reports"][$counter]["title"] = $values["title"];
                $prepareNode["reports"][$counter]["description"] = $values["description"];
                $prepareNode["reports"][$counter]["question_type"] = $values["question_type"];
                $prepareNode["reports"][$counter]["image_meta"] = (!is_array($values["image_meta"]) ? json_decode($values["image_meta"]) : $values["image_meta"]);
                $prepareNode["reports"][$counter]["video_meta"] = (!is_array($values["video_meta"]) ? json_decode($values["video_meta"]) : $values["video_meta"]);

                $optCounter = 0;
                $answers = $answers->toArray();

                foreach ($values["options"] as $optVal) {
                    if (in_array($optVal["id"], $pluckOpId)) {

                        $flip = array_flip($pluckOpId);

                        $pos = (isset($flip[$optVal["id"]]) ? $flip[$optVal["id"]] : false);

                        if ($pos === false) {
                            continue;
                        }

                        $prepareNode["reports"][$counter]["is_answered"] = (($answers[$pos]["option_id"] == null) ? false : true);
                        $prepareNode["reports"][$counter]["options"][$optCounter]["id"] = $optVal["id"];
                        $prepareNode["reports"][$counter]["options"][$optCounter]["option_type"] = $optVal["option_type"];

                        $prepareNode["reports"][$counter]["options"][$optCounter]["value"] = $answers[$pos]["answer_value"];

                        $prepareNode["reports"][$counter]["options"][$optCounter]["image_meta"] = (!is_array($optVal["image_meta"]) ? json_decode($optVal["image_meta"], true) : $optVal["image_meta"]);

                        $prepareNode["reports"][$counter]["options"][$optCounter]["video_meta"] = (!is_array($optVal["video_meta"]) ? json_decode($optVal["video_meta"], true) : $optVal["video_meta"]);

                        if ($values["question_type"] != config("constant.MEDIA_SURVEY_QUESTION_TYPE")) {
                            $prepareNode["reports"][$counter]["options"][$optCounter]["color_code"] = (isset($colorCodeList[$optCounter]) ? $colorCodeList[$optCounter] : "#fcda02");
                        } else {
                            $prepareNode["reports"][$counter]["options"][$optCounter]["allowed_media"] = (isset($optVal["allowed_media"]) ? $optVal["allowed_media"] : []);
                            // $imageMeta = $answers->pluck("image_meta")->toArray();
                            $prepareNode["reports"][$counter]["options"][$optCounter]["files"]["image_meta"] = (!is_array($answers[$pos]["image_meta"]) ? json_decode($answers[$pos]["image_meta"], true) : $answers[$pos]["image_meta"]);

                            // $videoMeta = $answers->pluck("video_meta")->toArray();
                            $prepareNode["reports"][$counter]["options"][$optCounter]["files"]["video_meta"] = (!is_array($answers[$pos]["video_meta"]) ? json_decode($answers[$pos]["video_meta"], true) : $answers[$pos]["video_meta"]);

                            // $documentMeta = $answers->pluck("document_meta")->toArray();
                            $prepareNode["reports"][$counter]["options"][$optCounter]["files"]["document_meta"] = (!is_array($answers[$pos]["document_meta"]) ? json_decode($answers[$pos]["document_meta"], true) : $answers[$pos]["document_meta"]);

                            // $mediaUrl = $answers->pluck("media_url")->toArray();
                            $prepareNode["reports"][$counter]["options"][$optCounter]["files"]["media_url"] = (!is_array($answers[$pos]["media_url"]) ? json_decode($answers[$pos]["media_url"], true) : $answers[$pos]["media_url"]);
                        }
                        $optCounter++;
                    } else {
                        $prepareNode["reports"][$counter]["is_answered"] = (($answers[0]["option_id"] == null) ? false : true);
                    }
                }


                $answers = [];

                $counter++;
            }
        }

        $this->messages = "Report Successful";
        $this->model = $prepareNode;
        return $this->sendResponse();
    }

    public function mediaList($id, $question_id, $media_type, Request $request)
    {

        if (!in_array($media_type, ["image_meta", "video_meta", "document_meta", "media_url"])) {
            return $this->sendError("Invalid Media Type");
        }

        $checkIFExists = $this->model->where("id", "=", $id)->first();
        if (empty($checkIFExists)) {
            return $this->sendError("Invalid Survey");
        }

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            return $this->sendError("Only Survey Admin can view this report");
        }

        $retrieveAnswers = SurveyAnswers::where("is_active", "=", 1)->where("question_id", "=", $question_id)->where("question_type", "=", config("constant.MEDIA_SURVEY_QUESTION_TYPE"))->where("survey_id", "=", $id)->get();


        $page = $request->input('page');

        list($skip, $take) = \App\Strategies\Paginator::paginate($page);

        $elements = [];

        foreach ($retrieveAnswers as $answers) {
            if (isset($answers->$media_type)) {

                $decode = (!is_null($answers->$media_type) ? json_decode($answers->$media_type, true) : []);

                if (is_array($decode) && count($decode)) {

                    foreach ($decode as $value) {
                        $meta = ["profile_id" => $answers->profile->id, "name" => $answers->profile->name, "handle" => $answers->profile->handle];
                        $elements[] = ["meta" => $meta,  "data" => $value];
                    }
                }
            }
        }

        $this->model = [];
        $data = ["answer_count" => $retrieveAnswers->count(), "total_files" => count($elements)];

        $data["media"] = array_slice($elements, $skip, $take);
        $this->messages = "Media List Successful";
        $this->model = $data;
        return $this->sendResponse();
    }

    public function prepQuestionJson($json): array
    {
        $decode = json_decode($json, true);
        if (is_array($decode)) {

            $Ar = [];
            foreach ($decode as $values) {
                $Ar[$values["id"]] = $values;
            }
            return $Ar;
        }
        return [];
    }


    public function excelReport($id, Request $request)
    {
        $checkIFExists = $this->model->where("id", "=", $id)->first();

        if (empty($checkIFExists)) {
            return $this->sendError("Invalid Survey");
        }

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            return $this->sendError("Only Survey Admin can view this report");
        }

        $headers = [];
        $getJson = json_decode($checkIFExists["form_json"], true);
        $questionIdMapping = [];

        foreach ($getJson as $values) {
            $questionIdMapping[$values["id"]] = $values["title"];
        }


        $getSurveyAnswers = SurveyAnswers::where("survey_id", "=", $id);

        if ($request->has("profile_ids") && !empty($request->input("profile_ids"))) {
            $getSurveyAnswers = $getSurveyAnswers->whereIn("profile_id", $request->profile_ids);
        }

        $getSurveyAnswers = $getSurveyAnswers->get();
        $counter = 0;
        foreach ($getSurveyAnswers as $answers) {
            
            $image = (!is_array($answers->image_meta) ? json_decode($answers->image_meta, true) : $answers->image_meta); 
            $video = (!is_array($answers->video_meta) ? json_decode($answers->image_meta, true) : $answers->video_meta); 
            $doc = (!is_array($answers->document_meta) ? json_decode($answers->document_meta, true) : $answers->document_meta); 
            $url = (!is_array($answers->media_url) ? json_decode($answers->media_url, true) : $answers->media_url); 
            if(isset($questionIdMapping[$answers->question_id])){
                $headers[$answers->profile_id]["Timestamp"] = date("Y-m-d H:i:s",strtotime($answers->created_at))." GMT +5.30";
                $headers[$answers->profile_id]["Username"] = $answers->profile->email;
                $headers[$answers->profile_id][$questionIdMapping[$answers->question_id]] = $answers->answer_value.
                    ((!empty($image) && is_array($image)) ? "\n\n Image : ".implode(array_column($image,"original_photo")) ?? "" : "").
                    ((!empty($video) && is_array($video)) ? "\n\n Video : ".implode(array_column($video,"video_url")) ?? "" : "").
                    ((!empty($doc) && is_array($doc)) ? "\n\n Document : ".implode(array_column($doc,"document_url")) ?? "" : "").
                    ((!empty($url) && is_array($url)) ? "\n\n Media Url : ".implode(array_column($url,"url")) ??"" : "");
                
            }

            
        }
        
        $finalData = array_values($headers);
        $relativePath = "images/surveysAnsweredExcel/$id";
        $name = "surveys-".$id."-".uniqid();
        
        $excel = Excel::create($name, function($excel) use ($name, $finalData)  {
                // Set the title
                $excel->setTitle($name);

                // Chain the setters
                $excel->setCreator('Tagtaste')
                      ->setCompany('Tagtaste');

                // Call them separately
                $excel->setDescription('Survey Response List');

                $excel->sheet('Sheetname', function($sheet) use($finalData) {
                    $sheet->fromArray($finalData);
                    foreach ($sheet->getColumnIterator() as $row) {
                        foreach ($row->getCellIterator() as $cell) {
                            if (!is_null($cell->getValue()) && str_contains($cell->getValue(), '/@')) {
                                $cell_link = $cell->getValue();
                                $cell->getHyperlink()
                                    ->setUrl($cell_link)
                                    ->setTooltip('Click here to access profile');
                            }
                        }
                    }
                })->store('xlsx', false, true);
            });
        $excel_save_path = storage_path("exports/".$excel->filename.".xlsx");
        $s3 = \Storage::disk('s3');
        $resp = $s3->putFile($relativePath, new File($excel_save_path), ['visibility'=>'public']);
        $this->model = $resp;
        unlink($excel_save_path);

        return $this->sendResponse();
    }
}
