<?php

namespace App\Http\Controllers\Api\Survey;

use App\Collaborate\Review;
use Illuminate\Http\Request;
use App\Company;
use App\Events\Model\Subscriber\Create;

use App\Http\Controllers\Controller;
use App\Events\NewFeedable;
use App\Events\UpdateFeedable;
use App\Events\DeleteFeedable;
use App\Events\Actions\Like;
use App\Events\Actions\SurveyAnswered;
use App\Events\TransactionInit;
use App\Payment\PaymentDetails;
use App\Payment\PaymentLinks;
use App\PeopleLike;
use App\Profile;
use App\PublicReviewProduct;
use App\PublicReviewProduct\Review as PublicReviewProductReview;
use App\SurveyAnswers;
use App\surveyApplicants;
use App\Surveys;
use App\SurveysLike;
use App\SurveyQuestionsType;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tagtaste\Api\SendsJsonResponse;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use App\PaymentHelper;
use App\SurveyAttemptMapping;

class SurveyController extends Controller
{

    use SendsJsonResponse, FilterTraits;

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
        $getSurvey = Surveys::where("id", "=", $id)->where("is_active", "=", 1)->where('account_deactivated', 0)->first();

        $this->model = false;
        $this->messages = "Survey Doesn't Exists";
        if (empty($getSurvey)) {
            $this->errors = ["Survey Doesn't Exists"];
            return $this->sendResponse();
        }

        $getSurvey["form_json"] = json_decode($getSurvey["form_json"], true);
        $getSurvey["video_meta"] = json_decode($getSurvey["video_meta"], true);
        $getSurvey["image_meta"] = json_decode($getSurvey["image_meta"], true);
        $getData = $getSurvey->toArray();
        $getData["mandatory_fields"] = $getSurvey->getMandatoryFields();
        $getData["closing_reason"] = $getSurvey->getClosingReason();
        // $count = \DB::table('survey_applicants')->where('survey_id', $id)->get()->count();
        $this->messages = "Request successfull";
        $this->model = [
            "surveys" => $getData,
            "meta" => $getSurvey->getMetaFor($request->user()->profile->id),
            "seoTags" => $getSurvey->getSeoTags()
        ];
        return $this->sendResponse();
    }

    public function getMySurvey(Request $request)
    {
        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $surveys = $this->model->where("is_active", "=", 1);
        if ($request->has('state') && !empty($request->input('state'))) {
            $states = [$request->state];
            if ($request->state == config("constant.SURVEY_STATES.PUBLISHED")) {
                $states = [config("constant.SURVEY_STATES.PUBLISHED"), config("constant.SURVEY_STATES.CLOSED"), config("constant.SURVEY_STATES.EXPIRED")];
            }
            $surveys = $surveys->whereIn("state", $states);
        }

        $surveys = $surveys->orderBy('state', 'asc')->orderBy('created_at', 'desc');
        $profileId = $request->user()->profile->id;
        $title = isset($request->title) ? $request->title : null;

        $this->model = [];
        $data = [];

        //Get compnaies of the logged in user.
        $companyIds = \DB::table('company_users')->where('profile_id', $profileId)->pluck('company_id');

        $surveys = $surveys->where(function ($q) use ($profileId, $companyIds) {
            $q->orWhere('profile_id', "=", $profileId);
            $q->orWhereIn('company_id', $companyIds);
        });

        if (!is_null($title)) {
            $surveys = $surveys->where('title', 'like', '%' . $title . '%');
        }
        $this->model['count'] = $surveys->count();

        $surveys = $surveys->skip($skip)->take($take)
            ->get();
        $surveyCount = 0;
        foreach ($surveys as $survey) {

            $survey->image_meta = json_decode($survey->image_meta);
            $survey->video_meta = json_decode($survey->video_meta);

            $data[] = [
                'survey' => $survey,
                'meta' => $survey->getMetaFor($profileId)
            ];
            // if ($survey->privacy_id == 0) unset($data[$surveyCount]["totalApplicants"]);
            $surveyCount++;
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
            'state' => 'required|in:1,2',
            'mandatory_field_ids' => 'array',
            'is_private' => 'boolean'
        ]);


        $this->model = false;
        $this->messages = "Survey Failed";
        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return $this->sendResponse();
        }

        if ($request->has("expired_at") && !empty($request->expired_at) && (strtotime($request->expired_at) > strtotime("+30 days   "))) {
            return $this->sendNewError("Expiry time exceeds a month");
        }
        if ($request->has("expired_at") && !empty($request->expired_at) && strtotime($request->expired_at) < time()) {
            return $this->sendNewError("Expiry time invalid");
        }


        $final_json = $this->validateSurveyFormJson($request);

        if (!empty($this->errors)) {
            return $this->sendResponse();
        }

        $is_section = false;   //for section 
        if (isset($final_json[0]["element_type"])  && $final_json[0]["element_type"] == "section") {
            $is_section = true;
        }



        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if ($request->has('company_id')) {
            $companyId = $request->input('company_id');
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendNewError("User does not belong to this company");
            }
            if (isset($request->is_private) && $request->is_private == 1 && $company->is_premium != 1) {
                return $this->sendNewError("Only premium companies can create private surveys");
                // return $next($request);
            }
        } else if (isset($request->is_private) && $request->is_private == 1 && $request->user()->profile->is_premium != 1) {
            return $this->sendNewError("Only premium users can create private surveys");
        }

        $prepData["id"] = (string) Uuid::generate(4);
        $prepData["is_active"] = 1;
        $prepData["profile_id"] = $request->user()->profile->id;
        $prepData["state"] = $request->state;
        $prepData["title"] = $request->title;
        $prepData["description"] = $request->description;
        $prepData["privacy_id"] = 1;
        $prepData["is_section"] = $is_section;
        $prepData["is_private"] = (isset($request->is_private) ? (int)$request->is_private :  null);
        $prepData["multi_submission"] = isset($request->multi_submission) ? $request->multi_submission : 0;

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

        if ($request->has("expired_at") && !empty($request->expired_at)) {
            $prepData["expired_at"] = date("Y-m-d", strtotime($request->expired_at));
        } else {
            $prepData["expired_at"] = date("Y-m-d", strtotime("+1 month"));
        }

        $create = Surveys::create($prepData);
        $create->image_meta = json_decode($create->image_meta);
        $create->video_meta = json_decode($create->video_meta);
        // $create->form_json = json_decode($create->final_json);
        $create->form_json = $final_json;
        if (isset($create->id)) {

            $this->storeMandatoryFields($request, $create->id);

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
        $survey = $this->model->where('id', $surveyId)->where('account_deactivated', 0)->where("is_active", "=", 1)->first();
        if ($survey == null) {
            $this->model = false;
            return $this->sendNewError("Invalid Survey Id");
        }

        $profileId = $request->user()->profile->id;
        $surveys = $this->model->where('state', 2)
            ->whereNull('deleted_at')->where("id", "<>", $surveyId)
            ->where('is_active', 1)
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


    public function statePermissionHandler($surveyDetails, $request)
    {
        if (!empty($surveyDetails)) {
            $profileId = $request->user()->profile->id;
            $getCompanyId = (!empty($surveyDetails->company_id) ? $surveyDetails->company_id : null);

            if (!empty($getCompanyId)) {
                $company = Company::find($getCompanyId);
                $userBelongsToCompany = $company->checkCompanyUser($request->user()->id);
                if ($userBelongsToCompany && $company->is_premium == 1) {
                    return true;
                    // return $next($request);
                }
            } else {
                if ($profileId == $surveyDetails->profile_id && $request->user()->profile->is_premium == 1) {
                    return true;
                    // return $next($request);
                }
            }
        }
        return false;
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
        $previousState = $getSurvey->state;
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
            'state' => 'required|in:1,2',
            'mandatory_field_ids' => 'array',
            'is_private' => 'boolean'
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return $this->sendResponse();
        }

        if (
            $getSurvey->state != config("constant.SURVEY_STATES.PUBLISHED") && $request->state == config("constant.SURVEY_STATES.PUBLISHED")
            &&
            ($getSurvey->is_private == null && ((int)$request->is_private == 1))
        ) {
            $verifyPermission = $this->statePermissionHandler($getSurvey, $request);
            if ($verifyPermission == false) {
                return $this->sendNewError("Permission Denied");
            }
        }


        if ($getSurvey->is_private !== null && ($request->has("is_private") && ((int)$request->is_private !== (int)$getSurvey->is_private))) {

            return $this->sendNewError("Survey status cannot be changed");
        }

        if ($request->has("expired_at") && !empty($request->expired_at) && (strtotime($request->expired_at) > strtotime("+30 days"))) {
            return $this->sendNewError("Expiry time exceeds a month");
        }
        if ($request->has("expired_at") && !empty($request->expired_at) && strtotime($request->expired_at) < time()) {
            return $this->sendNewError("Expiry time invalid");
        }

        $final_json = $this->validateSurveyFormJson($request, $id);
        if (!empty($this->errors)) {
            return $this->sendResponse();
        }
        $is_section = false; //for section
        if (isset($final_json[0]["element_type"])  && $final_json[0]["element_type"] == "section") {
            $is_section = true;
        }




        $prepData = (object)[];

        if ($getSurvey->state != config("constant.SURVEY_STATES.PUBLISHED") && $request->state == config("constant.SURVEY_STATES.PUBLISHED")) {

            $prepData->published_at = date("Y-m-d H:i:s");
        }
        //  else if ($getSurvey->i != config("constant.SURVEY_STATES.DRAFT") && $request->state == config("constant.SURVEY_STATES.DRAFT")) {
        //     return $this->sendNewError("Cannot update survey back to draft once its published");
        // }

        $prepData->state = $request->state;
        $prepData->title = $request->title;
        $prepData->description = $request->description;
        $prepData->is_section = $is_section;
        $prepData->multi_submission = isset($request->multi_submission) ? $request->multi_submission : 0;


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

        if ($request->has("expired_at") && !empty($request->expired_at)) {
            $prepData->expired_at = date("Y-m-d", strtotime($request->expired_at));
        }

        if ($request->has("is_private") && !empty($request->is_private)) {
            $prepData->is_private = (int)$request->is_private;
        }


        $create->update((array)$prepData);

        $this->model = true;
        $this->messages = "Survey Updated Successfully";


        $this->storeMandatoryFields($request, $id);



        if ($getSurvey->state == config("constant.SURVEY_STATES.DRAFT") && $request->state == config("constant.SURVEY_STATES.PUBLISHED")) {
            //create new cache
            $getSurvey = $create->first();
            if ($request->has('company_id')) {
                event(new NewFeedable($getSurvey, Company::find($request->company_id)));
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

        if (($previousState == config("constant.SURVEY_STATES.EXPIRED") || $previousState == config("constant.SURVEY_STATES.CLOSED"))
            && $request->state == config("constant.SURVEY_STATES.PUBLISHED")
        ) {
            $this->addSurveyGraph($getSurvey); //add node and edge to neo4j
        }

        $this->checkAndRemovePayment($getSurvey);

        return $this->sendResponse();
    }

    protected function checkAndRemovePayment($surveyObj)
    {
        if ($surveyObj->multi_submission == 1) {
            PaymentDetails::where('model_id', $surveyObj->id)
                ->where('model_type', 'Surveys')
                ->update(['is_active' => 0]);
        }
    }

    protected function addSurveyGraph($survey)
    {
        $surveyersIds = SurveyAnswers::where('survey_id', '=', $survey->id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->pluck('profile_id')->toArray();
        if (count($surveyersIds) > 0) {
            $survey->addToGraph();
            foreach ($surveyersIds as $profileId) {
                $survey->addParticipationEdge($profileId);
            }
        }
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
            $survey->removeFromGraph(); //Remove node and edge from neo4j
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
        $getListFromDb = SurveyQuestionsType::where("is_active", "=", 1)->orderBy('sort_id')->get();
        $this->model = $getListFromDb;
        return $this->sendResponse();
    }

    public function saveAnswers(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'survey_id' => 'required|exists:surveys,id',
                'current_status' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                $this->model = ["status" => false];
                $this->errors = $validator->messages();
                return $this->sendResponse();
            }

            $id = $this->model->where("id", "=", $request->survey_id)->first();

            $this->model = [];
            if (empty($id)) {
                $this->model = ["status" => false];
                return $this->sendNewError("Invalid Survey");
            }
            if ($id->state == config("constant.SURVEY_STATES.CLOSED")) {
                $this->model = ["status" => false];
                return $this->sendNewError("Survey is closed. Cannot submit answers");
            }

            if ($id->state == config("constant.SURVEY_STATES.EXPIRED")) {
                $this->model = ["status" => false];
                return $this->sendNewError("Survey is expired. Cannot submit answers");
            }

            if ($id->state == config("constant.SURVEY_STATES.DRAFT")) {
                $this->model = ["status" => false];
                return $this->sendNewError("Survey is in draft. Cannot submit answers");
            }

            if (isset($id->profile_id) && $id->profile_id == $request->profile_id) {
                $this->model = ["status" => false];
                return $this->sendNewError("Admin Cannot Fill the Surveys");
            }



            $checkApplicant = \DB::table("survey_applicants")->where('survey_id', $request->survey_id)->where('profile_id', $request->user()->profile->id)->whereNull('deleted_at')->first();

            if (empty($checkApplicant) && (is_null($id->is_private) || !$id->is_private)) {

                $this->saveApplicants($id, $request);
            } elseif (empty($checkApplicant)) {
                $this->messages = $id->profile->user->name . " accepted your survey participation request by mistake and it has been reversed.";
                return $this->sendNewError("Something went wrong");
            }

            if (!($id->multi_submission) && !empty($checkApplicant) && $checkApplicant->application_status == config("constant.SURVEY_APPLICANT_ANSWER_STATUS.COMPLETED")) {
                $this->model = ["status" => false];
                return $this->sendNewError("Already Answered");
            }


            if (
                !empty($checkApplicant) && $checkApplicant->application_status == config("constant.SURVEY_APPLICANT_ANSWER_STATUS.TO_BE_NOTIFIED")

            ) {
                $this->messages = $id->profile->user->name . " accepted your survey participation request by mistake and it has been reversed.";
                $this->model = ["status" => false];
                return $this->sendNewError("Something went wrong");
            }
            $optionArray = (!is_array($request->answer_json) ? json_decode($request->answer_json, true) : $request->answer_json);

            $answerQuestionIds = [];  //answer ques array
            $answerQuestionIds =  array_map(function ($vi) {
                return  $vi["question_id"];
            }, $optionArray);

            $prepareQuestionJson = [];
            $last_attempt = SurveyAttemptMapping::where("survey_id", $request->survey_id)->where("profile_id", $request->user()->profile->id)
                ->orderBy("updated_at", "desc")->whereNull("deleted_at")->first();

            if ($id->is_section) {    //check if section and make preparequestionjson accdng to that
                $questionsWithoutLast = [];
                $sectionJson = json_decode($id->form_json);

                if ($request->current_status ==  config("constant.SURVEY_APPLICANT_ANSWER_STATUS.COMPLETED")) { //check if last section ,all mandate questions to be filled
                    $sectionWithoutLast =  array_pop($sectionJson);   //remove last section ques
                    $questionsWithoutLast = array_column($sectionWithoutLast, "questions"); //storing questions except last section ques
                    $questionsWithoutLast = $this->prepQuestionJson($questionsWithoutLast);

                    $mandateQuestions =  array_map(function ($v) {
                        if (isset($v["is_mandatory"]) && $v["is_mandatory"] == true) {
                            return  $v["id"];
                        }
                    }, $questionsWithoutLast);
                    $questionsAnswered =  SurveyAnswers::where("survey_id", $request->survey_id)->where("profile_id", $request->user()->profile->id)->where("attempt", $last_attempt)->whereNull("deleted_at")->pluck("question_id")->toArray();
                    if (!empty(array_diff($mandateQuestions, $questionsAnswered))) {
                        return $this->sendNewError("Please fill previous section mandatory questions!");
                    }
                }
              

                foreach ($sectionJson as $section) {
                    $quesFromSection = array_column($section->questions, "id");

                    if (isset($section->questions) && !empty($answerQuestionIds) && in_array($answerQuestionIds[0], $quesFromSection)) {

                        $prepareQuestionJson = $this->prepQuestionJson(json_encode($section->questions));
                        break;
                    }
                }
            } else {
                $prepareQuestionJson = $this->prepQuestionJson($id->form_json);
            }
            
            $answerQuestionIds =  array_map(function ($vi) {  //question ids from answer json where answer option id exists
                return  (isset($vi["options"]) && !empty($vi["options"]))?$vi["question_id"]:null;
            }, $optionArray);
            $answerQuestionIds = array_filter($answerQuestionIds);

            $mandateQuestions = [];
            $mandateQuestions =  array_map(function ($v) {
                if (isset($v["is_mandatory"]) && $v["is_mandatory"] == true) {
                    return  $v["id"];
                }
            }, $prepareQuestionJson);
           
            $mandateQuestions = array_values(array_filter($mandateQuestions));
            
            if (!empty(array_diff($mandateQuestions, $answerQuestionIds))) {
                return $this->sendNewError("Mandatory Questions Cannot Be Blank");
            }

            $answerAttempt = [];
            $answerAttempt["profile_id"] = $request->user()->profile->id;
            $answerAttempt["survey_id"] = $request->survey_id;


            if (empty($last_attempt)) {   //WHEN ITS FIRST ATTEMPT
                $last_attempt = 1;
                $answerAttempt["attempt"] = $last_attempt;
                SurveyAttemptMapping::create($answerAttempt);  //entry on first hit
            } else {    //when its not first attempt
                $last_attempt = $last_attempt->attempt;
                if ($id->multi_submission && $checkApplicant->application_status == config("constant.SURVEY_APPLICANT_ANSWER_STATUS.COMPLETED")) {
                    $last_attempt += 1;
                    $answerAttempt["attempt"] = $last_attempt;
                    SurveyAttemptMapping::create($answerAttempt);    //when new attempt of same user first entry
                }
            }

            DB::beginTransaction();
            $commit = true;
            foreach ($optionArray as $values) {

                if (!isset($values["options"]) || empty($values["options"])) {
                    // DB::rollback();
                    // $this->model = ["status" => false];
                    // return $this->sendNewError("Options not found");
                    continue;
                }
                $answerArray = [];
                $answerArray["profile_id"] = $request->user()->profile->id;
                $answerArray["survey_id"] = $request->survey_id;
                $answerArray["question_id"] = $values["question_id"];
                $answerArray["question_type"] = $values["question_type_id"];
                $answerArray["current_status"] = $request->current_status;

                //checking if answer exists for this ques ,then delete and save new ones
                $answerExists = SurveyAnswers::where('survey_id', $request->survey_id)
                    ->where("profile_id", $request->user()->profile->id)->where("question_id", $values["question_id"])
                    ->where("attempt", $last_attempt)->whereNull("deleted_at")
                    ->first();
                if (!empty($answerExists)) {
                    SurveyAnswers::where('survey_id', $request->survey_id)
                        ->where("profile_id", $request->user()->profile->id)->where("question_id", $values["question_id"])->where("attempt", $last_attempt)->update(["deleted_at" => date("Y-m-d H:i:s"),"is_active" => 0]);
                }

                if (isset($values["options"]) && !empty($values["options"])) {

                    foreach ($values["options"] as $optVal) {
                        $answerArray["answer_value"] = $optVal["value"];
                        if (is_array($answerArray["answer_value"])) {
                            foreach ($answerArray["answer_value"] as $value) {
                                $answerArray["option_id"] = $optVal["id"];
                                $answerArray["option_type"] = $optVal["option_type"];
                                $answerArray["answer_value"] = trim($value, " ");
                                $answerArray["is_active"] = 1;
                                $answerArray["attempt"] = $last_attempt;
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
                            $answerArray["option_id"] = $optVal["id"];
                            $answerArray["option_type"] = $optVal["option_type"];
                            $answerArray["answer_value"] = trim($optVal["value"], " ");
                            $answerArray["is_active"] = 1;
                            $answerArray["attempt"] = $last_attempt;
                            $answerArray["image_meta"] = ((isset($optVal["image_meta"])  && is_array($optVal["image_meta"])) ? json_encode($optVal["image_meta"]) : json_encode([]));
                            $answerArray["video_meta"] = ((isset($optVal["video_meta"])  && is_array($optVal["video_meta"])) ? json_encode($optVal["video_meta"]) : json_encode([]));
                            $answerArray["document_meta"] = ((isset($optVal["document_meta"])  && is_array($optVal["document_meta"])) ? json_encode($optVal["document_meta"]) : json_encode([]));
                            $answerArray["media_url"] = ((isset($optVal["media_url"])  && is_array($optVal["media_url"])) ? json_encode($optVal["media_url"]) : json_encode([]));
                            $surveyAnswer = SurveyAnswers::create($answerArray);
                            if (!$surveyAnswer) {
                                $commit = false;
                            }
                        }
                    }
                }
            }
            $user = $request->user()->profile;
            $responseData = [];

            if ($commit) {
                DB::commit();

                $this->model = true;
                $responseData = ["status" => true];
                $this->messages = "Answer Submitted Successfully";
                $completion_date = isset($checkApplicant)?$checkApplicant->completion_date:null;
                //when completed update completion_date in mapping table
                if ($request->current_status == config("constant.SURVEY_APPLICANT_ANSWER_STATUS.COMPLETED")) {
                    $completion_date = date("Y-m-d H:i:s");
                    SurveyAttemptMapping::where("survey_id", $request->survey_id)->where("profile_id", $request->user()->profile->id)
                        ->where("attempt", $last_attempt)->update(["completion_date" => $completion_date]);
                }

                $checkApplicant = \DB::table("survey_applicants")->where('survey_id', $request->survey_id)->where('profile_id', $request->user()->profile->id)->update(["application_status" => $request->current_status, "completion_date" => $completion_date]);
                $user = $request->user()->profile->id;
                Redis::set("surveys:application_status:$request->survey_id:profile:$user", $request->current_status);
            } else {
                $responseData = ["status" => false];
            }

            //NOTE: Check for all the details according to flow and create txn and push txn to queue for further process.
            if ($this->model == true && $request->current_status == config("constant.SURVEY_APPLICANT_ANSWER_STATUS.COMPLETED")) {
                if(!($id->multi_submission)){
                $responseData = $this->paidProcessing($request);
                }
                $id->addToGraph();
                $id->addParticipationEdge($request->user()->profile->id); //Add edge to neo4j
            }

            return $this->sendResponse($responseData);
        } catch (Exception $ex) {
            DB::rollback();
            $this->model = ["status" => false];
            return $this->sendNewError("Error Saving Answers " . $ex->getMessage() . " " . $ex->getFile() . " " . $ex->getLine());
        }
    }

    public function paidProcessing(Request $request)
    {
        $responseData = $flag = [];
        $requestPaid = $request->is_paid ?? false;
        $responseData["status"] = true;

        $paymnetExist = PaymentDetails::where('model_id', $request->survey_id)->where('is_active', 1)->first();
        if ($paymnetExist != null || $requestPaid) {


            $responseData["is_paid"] = true;

            if ($requestPaid) {
                $flag = ["status" => false, "reason" => "paid"];
            }

            if ($paymnetExist != null) {
                //check for excluded flag for profiles 
                $exp = (!empty($paymnetExist->excluded_profiles) ? $paymnetExist->excluded_profiles : null);
                if ($exp != null) {
                    $separate = explode(",", $exp);
                    if (in_array($request->user()->profile->id, $separate)) {
                        //excluded profile error to be updated
                        $responseData["is_paid"] = false;
                        return $responseData;
                    }
                }
                $flag = $this->verifyPayment($paymnetExist, $request);
            }

            //NOTE: Response types
            //profile - not a paid taster
            //paid taster - Rewarded
            //phone not updated
            //paid taster - No Rewarded

            if ($flag["status"] == true) {
                $responseData["get_paid"] = true;
                $responseData["title"] = "Congratulations!";
                $responseData["subTitle"] = "You have successfully completed the survey.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/congratulation.png";
                $responseData["helper"] = "We appreciate your effort and have sent you a reward link to your registered email and phone number, redeem it and enjoy.";
            } else if ($flag["status"] == false && isset($flag["reason"]) && $flag["reason"] == "phone") {
                $responseData["get_paid"] = true;
                $responseData["title"] = "Congratulations!";
                $responseData["subTitle"] = "You have successfully completed the survey.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/congratulation.png";
                $responseData["helper"] = "We appreciate your effort, but unfortunately you don't have your phone number updated in your profile. Please update phone number and contact us at payment@tagtaste.com to redeem the reward.";
            } else if ($flag["status"] == false && isset($flag["reason"]) && $flag["reason"] == "paid") {
                $responseData["get_paid"] = false;
                $responseData["title"] = "Uh Oh!";
                $responseData["subTitle"] = "You have successfully completed the survey.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/congratulation.png";
                $responseData["helper"] = "We appreciate your effort but unfortunately you missed the reward. Please contact us at payment@tagtaste.com for any further help.";
            } else if ($flag["status"] == false && isset($flag["reason"]) && $flag["reason"] == "not_paid") {
                $responseData["is_paid"] = false;
            } else {
                $responseData["get_paid"] = false;
                $responseData["title"] = "Uh Oh!";
                $responseData["subTitle"] = "You have successfully completed the survey.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/failed.png";
                $responseData["helper"] = "We appreciate your effort but unfortunately you missed the reward. Please contact us at payment@tagtaste.com for any further help.";
            }
        } else {
            $responseData["is_paid"] = false;
        }

        return $responseData;
    }


    public function verifyPayment($paymentDetails, Request $request)
    {
        $count = PaymentLinks::where("payment_id", $paymentDetails->id)->where("status_id", "<>", config("constant.PAYMENT_CANCELLED_STATUS_ID"))->get();
        if ($count->count() < (int)$paymentDetails->user_count) {
            $getAmount = json_decode($paymentDetails->amount_json, true);

            $amount = 0;
            if ($paymentDetails->review_type == config("payment.PAYMENT_REVIEW_TYPE.REVIEW_COUNT")) {


                $amount = ((isset($getAmount["current"]['taster'][0]["amount"])) ? $getAmount["current"]['taster'][0]["amount"] : 0);
            } else if ($paymentDetails->review_type == config("payment.PAYMENT_REVIEW_TYPE.USER_TYPE")) {

                $getCount = PaymentHelper::getDispatchedPaymentUserTypes($paymentDetails);

                if ($request->user()->profile->is_expert) {
                    $key = "expert";
                } else {
                    $key = "consumer";
                }

                if ($getCount[$key] >= $getAmount["current"][$key][0]["user_count"]) {
                    //error message for different user type counts exceeded
                    return ["status" => false, "reason" => "not_paid"];
                }

                $amount = ((isset($getAmount["current"][$key][0]["amount"])) ? $getAmount["current"][$key][0]["amount"] : 0);
            }


            $data = ["amount" => $amount, "model_type" => "Survey", "model_id" => $request->survey_id, "payment_id" => $paymentDetails->id];

            if (isset($paymentDetails->comment) && !empty($paymentDetails->comment)) {
                $data["comment"] = $paymentDetails->comment;
            }

            $createPaymentTxn = event(new TransactionInit($data));
            $paymentcount = (int)$count->count();
            if ((int)$paymentDetails->user_count == ++$paymentcount) {
                PaymentDetails::where('id', $paymentDetails->id)->update(['is_active' => 0]);
            }
            if ($createPaymentTxn) {
                return $createPaymentTxn[0];
            } else {
                Log::info("Payment Returned False" . " " . json_encode($data));
            }
        } else {
            PaymentDetails::where('id', $paymentDetails->id)->update(['is_active' => 0]);
            if ($request->has("is_paid") && $request->is_paid == true) {
                return ["status" => false, "reason" => "paid"];
            }
        }

        return ["status" => false];
    }
    public function reports($id, Request $request)
    {

        $checkIFExists = $this->model->where("id", "=", $id)->first();

        $colorCodeList = $this->colorCodeList;


        if (empty($checkIFExists)) {
            $this->model = false;
            return $this->sendNewError("Invalid Survey");
        }
        if ($request->has('filters') && !empty($request->filters)) {
            $getFiteredProfileIds = $this->getProfileIdOfFilter($checkIFExists, $request);
            $profileIds = $getFiteredProfileIds['profile_id'];
            $type = $getFiteredProfileIds['type'];
        }

        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                $this->model = false;
                return $this->sendNewError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            //($checkIFExists->profile_id);
            $this->model = false;
            return $this->sendNewError("Only Survey Admin can view this report");
        }

        $applicants =  SurveyAttemptMapping::where("survey_id", "=", $id)->whereNotNull("completion_date")->where("deleted_at", "=", null);

        $resumeArray = SurveyAttemptMapping::select("profile_id", "attempt")->where("survey_id", "=", $id)->whereNull("deleted_at")->whereNull("completion_date")->get();
        $idsAttemptMapping = [];

        foreach ($resumeArray as $resume) {
            $idsAttemptMapping[$resume->profile_id][] = $resume->attempt;
        }

        if ($request->has('filters') && !empty($request->filters)) {
            $applicants->whereIn('profile_id', $profileIds, 'and', $type);
        }



        $getCount = $applicants->get();

        $prepareNode = ["answer_count" => $getCount->count(), "reports" => []];

        $pluck = $getCount->pluck("profile_id")->toArray();

        $getJsonQues = [];
        $getJson = json_decode($checkIFExists["form_json"], true);
        $sectionQuesCount = [];
        $sectionKeys = [];

        if ($checkIFExists["is_section"]) {         //for section type form_json

            foreach ($getJson as $key => $section) {
                if (isset($section["questions"]) && count($section["questions"])) {
                    $sectionQuesCount[$key] = count($section["questions"]);
                    $sectionKeys[] = $key;
                    $getJsonQues = array_merge($getJsonQues, $section["questions"]);
                    $getJson[$key]["questions"] = [];
                }
            }
        } else {
            $getJsonQues = $getJson;
        }

        $counter = 0;
        $sectionKey = 0;

        foreach ($getJsonQues as $values) {
            shuffle($colorCodeList);

            $answers = SurveyAnswers::where("survey_id", "=", $id)->where("question_type", "=", $values["question_type"])->where("question_id", "=", $values["id"])->whereNull("deleted_at")->whereIn("profile_id", $pluck)->get()->filter(function ($ans) use ($idsAttemptMapping) {

                return isset($idsAttemptMapping[$ans->profile_id]) ? !in_array($ans->attempt, $idsAttemptMapping[$ans->profile_id]) : true;
            });

            $ans = $answers->pluck("option_id")->toArray();
            $ar = array_values(array_filter($ans));
            $getAvg = (count($ar) ? $this->array_avg($ar, count($ar)) : 0);

            $prepareNode["reports"][$counter]["question_id"] = $values["id"];
            $prepareNode["reports"][$counter]["title"] = $values["title"];
            $prepareNode["reports"][$counter]["question_type"] = $values["question_type"];
            $prepareNode["reports"][$counter]["image_meta"] = (!is_array($values["image_meta"]) ?  json_decode($values["image_meta"], true) : $values["image_meta"]);
            $prepareNode["reports"][$counter]["video_meta"] = (!is_array($values["video_meta"]) ?  json_decode($values["video_meta"], true) : $values["video_meta"]);

            if (isset($values["max"])) {
                $prepareNode["reports"][$counter]["max"] = $values["max"];
            }
            if (isset($values["min"])) {
                $prepareNode["reports"][$counter]["min"] = $values["min"];
            }
            if (isset($values["minLabel"])) {
                $prepareNode["reports"][$counter]["minLabel"] = $values["minLabel"];
            }
            if (isset($values["maxLabel"])) {
                $prepareNode["reports"][$counter]["maxLabel"] = $values["maxLabel"];
            }

            if ($values['question_type'] == config("constant.SURVEY_QUESTION_TYPES.RANGE")) {
                $ans = $answers->pluck("answer_value")->toArray();
                $ar = array_values(array_filter($ans, function ($value) {
                    return ($value !== null && $value !== false && $value !== '');
                }));
                $getAvg = (count($ar) ? $this->array_avg($ar, count($ar)) : 0);
                $count = 0;
                for ($min = $values["min"]; $min <= $values['max']; $min++) {
                    $prepareNode["reports"][$counter]["options"][$count]["value"] = $min;
                    $prepareNode["reports"][$counter]["options"][$count]["answer_count"] = (isset($getAvg[$min]) ? $getAvg[$min]["count"] : 0);
                    $prepareNode["reports"][$counter]["options"][$count]["answer_percentage"] = (isset($getAvg[$min]) ? $getAvg[$min]["avg"] : 0);
                    $prepareNode["reports"][$counter]["options"][$count]["color_code"] = (isset($colorCodeList[$min]) ? $colorCodeList[$min] : "#fcda02");
                    $prepareNode["reports"][$counter]["options"][$count]["option_type"] = 0;
                    $count++;
                }
            } elseif (isset($values["multiOptions"])) {
                foreach ($values["multiOptions"]['row'] as $row) {
                    if ($values['question_type'] == config("constant.SURVEY_QUESTION_TYPES.MULTI_SELECT_RADIO")) {
                        $answers = SurveyAnswers::where("survey_id", "=", $id)->where("question_type", "=", $values["question_type"])->where("question_id", "=", $values["id"])->where('answer_value', $row['id'])->whereNull("deleted_at")->whereIn("profile_id", $pluck)->get()->filter(function ($ans) use ($idsAttemptMapping) {

                            return isset($idsAttemptMapping[$ans->profile_id]) ? !in_array($ans->attempt, $idsAttemptMapping[$ans->profile_id]) : true;
                        });

                        $ans = $answers->pluck("option_id")->toArray();
                    } else {
                        $answers = SurveyAnswers::where("survey_id", "=", $id)->where("question_type", "=", $values["question_type"])->where("question_id", "=", $values["id"])->where('option_id', $row['id'])->whereNull("deleted_at")->whereIn("profile_id", $pluck)->get()->filter(function ($ans) use ($idsAttemptMapping) {

                            return isset($idsAttemptMapping[$ans->profile_id]) ? !in_array($ans->attempt, $idsAttemptMapping[$ans->profile_id]) : true;
                        });

                        $ans = $answers->pluck("answer_value")->toArray();
                    }

                    $ar = array_values(array_filter($ans));
                    $getAvg = (count($ar) ? $this->array_avg($ar, count($ar)) : 0);
                    $prepareNode["reports"][$counter]["options"][$row['id'] - 1]["id"] = $row['id'];
                    $prepareNode["reports"][$counter]["options"][$row['id'] - 1]["value"] = $row["title"];
                    foreach ($values["multiOptions"]['column'] as $column) {
                        $prepareNode["reports"][$counter]["options"][$row['id'] - 1]["column"][$column['id'] - 1]["id"] = $column['id'];
                        $prepareNode["reports"][$counter]["options"][$row['id'] - 1]["column"][$column['id'] - 1]["value"] = $column['title'];
                        $prepareNode["reports"][$counter]["options"][$row['id'] - 1]["column"][$column['id'] - 1]["option_type"] = 0;
                        $prepareNode["reports"][$counter]["options"][$row['id'] - 1]["column"][$column['id'] - 1]["color_code"] = (isset($colorCodeList[$row["id"]]) ? $colorCodeList[$row["id"]] : "#fcda02");;
                        $prepareNode["reports"][$counter]["options"][$row['id'] - 1]["column"][$column['id'] - 1]["answer_count"] = (isset($getAvg[$column['id']]) ? $getAvg[$column['id']]["count"] : 0);
                        $prepareNode["reports"][$counter]["options"][$row['id'] - 1]["column"][$column['id'] - 1]["answer_percentage"] = (isset($getAvg[$column['id']]) ? $getAvg[$column['id']]["avg"] : 0);
                    }
                }
            } else {
                $optCounter = 0;

                foreach ($values["options"] as $optVal) {
                    $prepareNode["reports"][$counter]["options"][$optCounter]["id"] = $optVal["id"];
                    $prepareNode["reports"][$counter]["options"][$optCounter]["value"] = $optVal["title"];
                    $prepareNode["reports"][$counter]["options"][$optCounter]["option_type"] = $optVal["option_type"];
                    $prepareNode["reports"][$counter]["options"][$optCounter]["image_meta"] = (!is_array($optVal["image_meta"]) ? json_decode($optVal["image_meta"], true) : $optVal["image_meta"]);
                    $prepareNode["reports"][$counter]["options"][$optCounter]["video_meta"] = (!is_array($optVal["video_meta"]) ? json_decode($optVal["video_meta"], true) : $optVal["video_meta"]);
                    $prepareNode["reports"][$counter]["options"][$optCounter]["color_code"] = (isset($colorCodeList[$optCounter]) ? $colorCodeList[$optCounter] : "#fcda02");
                    $countOptions = 0;
                    $sum = 0;
                    $countOfApplicants = 0;

                    if ($values['question_type'] == config("constant.SURVEY_QUESTION_TYPES.RANK")) {
                        $answers = SurveyAnswers::where("survey_id", "=", $id)->where("question_type", "=", $values["question_type"])->where("question_id", "=", $values["id"])->where('answer_value', $optVal['id'])->whereNull("deleted_at")->whereIn("profile_id", $pluck)->get()->filter(function ($ans) use ($idsAttemptMapping) {

                            return isset($idsAttemptMapping[$ans->profile_id]) ? !in_array($ans->attempt, $idsAttemptMapping[$ans->profile_id]) : true;
                        });


                        $ans = $answers->pluck("option_id")->toArray();

                        $ar = array_values(array_filter($ans));
                        $getAvg = (count($ar) ? $this->array_avg($ar, count($ar)) : 0);
                        $countOptions = count($ar);

                        for ($min = 1; $min <= $values['max']; $min++) {
                            if (isset($getAvg[$min])) {

                                $sum = $sum + (($getAvg[$min]["count"]) * $min);
                                $countOfApplicants += $getAvg[$min]["count"];
                            }
                        }
                        $sum += ((count($ar)) - $countOfApplicants) * (count($values["options"]));
                    }
                    if ($values["question_type"] != config("constant.MEDIA_SURVEY_QUESTION_TYPE")) {
                        if ($values['question_type'] == config("constant.SURVEY_QUESTION_TYPES.RANK")) {
                            $prepareNode["reports"][$counter]["options"][$optCounter]["answer_count"] = $countOptions;
                            $prepareNode["reports"][$counter]["options"][$optCounter]["answer_percentage"] = count($ar) ? ($sum / count($ar)) : 0;
                            $prepareNode["reports"][$counter]["options"][$optCounter]["option_type"] = 0;
                        } else {
                            $prepareNode["reports"][$counter]["options"][$optCounter]["answer_count"] = (isset($getAvg[$optVal["id"]]) ? $getAvg[$optVal["id"]]["count"] : 0);
                            $prepareNode["reports"][$counter]["options"][$optCounter]["answer_percentage"] = (isset($getAvg[$optVal["id"]]) ? $getAvg[$optVal["id"]]["avg"] : 0);
                            $prepareNode["reports"][$counter]["options"][$optCounter]["color_code"] = (isset($colorCodeList[$optCounter]) ? $colorCodeList[$optCounter] : "#fcda02");
                        }
                    }
                    //doubt
                    else {
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
                                                $mediaUrl[] = ["data" => $value, "meta" => $meta];
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
            }
            if ($prepareNode["reports"][$counter]["question_type"] <= 5) {
                uasort($prepareNode["reports"][$counter]["options"], function ($a, $b) {
                    if (isset($a['answer_percentage']) && isset($b['answer_percentage'])) {
                        if ($a['answer_percentage'] == $b['answer_percentage']) {
                            return 0;
                        }
                        return ($a['answer_percentage'] < $b['answer_percentage']) ? 1 : -1;
                    }
                });
            }
            if ($prepareNode["reports"][$counter]["question_type"] == config("constant.SURVEY_QUESTION_TYPES.RANK")) {
                uasort($prepareNode["reports"][$counter]["options"], function ($a, $b) {
                    if ($a['answer_percentage'] == $b['answer_percentage']) {
                        return 0;
                    }
                    return ($a['answer_percentage'] < $b['answer_percentage']) ? -1 : 1;
                });
                foreach ($prepareNode["reports"][$counter]["options"] as $key => $option) {
                    if ($option["answer_percentage"] == 0)
                        unset($prepareNode["reports"][$counter]["options"][$key]);
                }
            }


            $prepareNode["reports"][$counter]["options"] = array_values($prepareNode["reports"][$counter]["options"]);

            //for sections having questions 
            if ($checkIFExists["is_section"] && count($sectionKeys)) {

                if (count($getJson[$sectionKeys[$sectionKey]]["questions"]) < $sectionQuesCount[$sectionKeys[$sectionKey]]) {
                    $getJson[$sectionKeys[$sectionKey]]["questions"][] = $prepareNode["reports"][$counter];
                } else {
                    $sectionKey++;
                    $getJson[$sectionKeys[$sectionKey]]["questions"][] = $prepareNode["reports"][$counter];
                }
            }
            //

            $answers = [];
            $counter++;
        }

        if (!$checkIFExists["is_section"]) {  //for normal survey
            $this->model = $prepareNode;
        } else {
            $finalRespnse = [];
            $finalRespnse["reports"] = $getJson;
            $finalRespnse["answer_count"] = $prepareNode["answer_count"];
            $this->model = $finalRespnse;
        }

        $this->messages = "Report Successful";
        return $this->sendResponse();
    }

    function array_avg($array, $respCount = 0)
    {
        if (is_array($array) && count($array)) {
            $num = $respCount;
            return array_map(
                function ($val) use ($num) {

                    return array('count' => $val, 'avg' =>  (float)bcdiv((float)($val / $num * 100), 1, 2));
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

            $sectionNodeChecker = ["title", "image_meta", "video_meta", "element_type", "description", "id"];
            //required node for questions    
            $requiredNode = ["question_type", "title", "image_meta", "video_meta", "description", "id", "is_mandatory", "options"];
            //required option nodes
            $optionNodeChecker = ["id", "option_type", "image_meta", "video_meta", "title"];
            $questionWithoutOption = [
                config("constant.SURVEY_QUESTION_TYPES.RANGE"),
                config("constant.SURVEY_QUESTION_TYPES.MULTI_SELECT_RADIO"),
                config("constant.SURVEY_QUESTION_TYPES.MULTI_SELECT_CHECK")
            ];
            //getTypeOfQuestions  
            $getListOfFormQuestions = SurveyQuestionsType::where("is_active", "=", 1)->get()->pluck("question_type_id")->toArray();

            $colorCodeList = $this->colorCodeList;
            $section = false; //flag to know is section
            $sectionQuesArray = []; //question json array from form_json
            $sectionWiseCount = [];  //section wise question count
            $sectionJson = $decodeJson;    //form_json response  of section
            $sectionKeyArray = [];  //keys of sections that is having questions

            //for sectioning validation
            foreach ($sectionJson as $key => $values) {
                if (isset($values["element_type"])) {
                    if ($values["element_type"] == "section") {

                        $diff = array_diff($sectionNodeChecker, array_keys($values));

                        if (!$key) {   //first time initialization of section type
                            $section = true;
                        }

                        if (!empty($diff)) {
                            $this->errors["form_json"] = "Not a Valid section json";
                        } else if ($key && !$section) { //section type false after n no. of iteration 
                            $section = true;
                            $this->errors["form_json"] = "Invalid form Json";
                        } else {     //if no error
                            $sectionJson[$key]["id"] = $key + 1;   //assigning ids to sections
                            if (isset($values["questions"])) {
                                $sectionKeyArray[] = $key;
                                $sectionWiseCount[$key] = count($values["questions"]);
                                $sectionQuesArray = array_merge($sectionQuesArray, $values["questions"]);
                                $sectionJson[$key]["questions"] = [];
                            }
                        }
                    } else if (($values["element_type"] == "question" && $section)
                        || ($values["element_type"] == "question" && isset($values["questions"]))
                    ) {
                        //if section is true and current iteration element type is question
                        $this->errors["form_json"] = "Invalid form Json";
                    }
                } else if (isset($values["questions"])) {
                    $this->errors["form_json"] = "Element type neccessary for section type";
                    break;
                }
            }

            if (!empty($this->errors)) {
                return;
            }

            //max ques id calculation
            $getOldJson = Surveys::where("id", "=", $isUpdation)->select("form_json", "is_section")->first()->toArray();

            $maxQueId = 1;
            if ($isUpdation) {
                if (isset($getOldJson["is_section"]) && $getOldJson["is_section"]) {
                    $oldJsonArray = [];
                    $getOldJson = $this->prepQuestionJson($getOldJson["form_json"]); //old section array
                    foreach ($getOldJson as $value) {
                        if (isset($value["questions"])) {
                            $maxQueId += count($value["questions"]);
                            $oldJsonArray = array_merge($oldJsonArray, $value["questions"]);
                        }
                    }
                    $oldJsonArray = $this->prepQuestionJson(json_encode($oldJsonArray)); //old questionarray

                } else {
                    $oldJsonArray = $this->prepQuestionJson($getOldJson["form_json"]);
                    $listOfQuestionIds = array_keys($oldJsonArray);

                    $maxQueId = max($listOfQuestionIds);
                    $maxQueId++;
                }
            }

            if ($section) {
                $decodeJson = $sectionQuesArray;
            } //if sectioning exists


            $sectionKey = 0;  //key value of section
            foreach ($decodeJson as $key => &$values) {
                $values["id"] = (int)$values["id"];
                // if (isset($values["is_mandatory"])) {
                //     $values["is_mandatory"] = (int)$values["is_mandatory"];
                // } else {
                //     $values["is_mandatory"] = 0;
                // }

                if (isset($values["question_type"]) && in_array($values["question_type"], $getListOfFormQuestions)) {
                    $diff = array_diff($requiredNode, array_keys($values));
                    // echo (isset($values['id']));

                    if (!$isUpdation || !isset($values['id']) || empty($values['id'])) {
                        $values['id'] = (int) $maxQueId;
                        $maxQueId++;
                    }
                    if (isset($values["multiOptions"])) {
                        $rowId = 1;
                        $columnId = 1;
                        if ($isUpdation) {
                            if (isset($oldJsonArray[$values["id"]]["multiOptions"])) {
                                $allOptsRows = array_column($oldJsonArray[$values["id"]]["multiOptions"]["row"], "id");
                                $allOptsColumns = array_column($oldJsonArray[$values["id"]]["multiOptions"]["column"], "id");
                                $rowId = (is_array($allOptsRows) && !empty($allOptsRows) ? max($allOptsRows) : max(array_column($values["multiOptions"]["row"], 'id')));
                                $columnId = (is_array($allOptsColumns) && !empty($allOptsColumns) ? max($allOptsColumns) : max(array_column($values["multiOptions"]["column"], 'id')));
                            } else {
                                $rowId = max(array_column($values["multiOptions"]["row"], 'id'));
                                $columnId = max(array_column($values["multiOptions"]["column"], 'id'));
                            }
                            $rowId++;
                            $columnId++;
                        }

                        foreach ($values["multiOptions"]["row"] as &$row) {
                            if (!$isUpdation || !isset($row['id']) || empty($row['id'])) {
                                $row['id'] = (string)$rowId;
                                $rowId++;
                            }
                        }
                        foreach ($values["multiOptions"]["column"] as &$column) {
                            if (!$isUpdation || !isset($column['id']) || empty($column['id'])) {
                                $column['id'] = (string)$columnId;
                                $columnId++;
                            }
                        }
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
                                $opt['id'] = (string)$maxOptionId;
                                if ($values["question_type"] == config("constant.SURVEY_QUESTION_TYPES.RANK")) {
                                    $opt['color_code'] = (isset($colorCodeList[$maxOptionId]) ? $colorCodeList[$maxOptionId] : "#fcda02");
                                }
                                $maxOptionId++;
                            }
                            if ($values["question_type"] == config("constant.SURVEY_QUESTION_TYPES.RANK")) {
                                if (count($values["options"]) < $values["max"]) {
                                    $this->errors["form_json"] = "Rank cannot be greater than count of options";
                                }
                            }

                            $diffOptions = array_diff($optionNodeChecker, array_keys($opt));
                            if (!empty($diffOptions)) {
                                $this->errors["form_json"] = "Option Nodes Missing " . implode(",", $diffOptions);
                            }
                        }
                    } else if (!in_array($values["question_type"], $questionWithoutOption)) {
                        $this->errors["form_json"] = "Question Nodes Missing " . implode(",", $diff);
                    }
                } else if (!in_array($values["question_type"], $questionWithoutOption)) {
                    $this->errors["form_json"] = "Invalid Question Type " . $values["question_type"];
                }
                //assigning modified question value to section response
                if ($section && count($sectionKeyArray)) {
                    if (count($sectionJson[$sectionKeyArray[$sectionKey]]["questions"]) < $sectionWiseCount[$sectionKeyArray[$sectionKey]]) {
                        $sectionJson[$sectionKeyArray[$sectionKey]]["questions"][] = $values;
                    } else {
                        $sectionKey++;
                        $sectionJson[$sectionKeyArray[$sectionKey]]["questions"][] = $values;
                    }
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
        if ($section) {
            $decodeJson = $sectionJson;
        }
        return $decodeJson;
    }

    public function surveyRespondents($id, Request $request)
    {
        $checkIFExists = $this->model->where("id", "=", $id)->first();
        if (empty($checkIFExists)) {
            $this->model = false;
            return $this->sendNewError("Invalid Survey");
        }


        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                $this->model = false;
                return $this->sendNewError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            $this->model = false;
            return $this->sendNewError("Only Survey Admin can view this report");
        }

        if ($request->has('filters') && !empty($request->filters)) {
            $getFiteredProfileIds = $this->getProfileIdOfFilter($checkIFExists, $request);
            $profileIds = $getFiteredProfileIds['profile_id'];
            $type = $getFiteredProfileIds['type'];
        }


        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $count = SurveyAttemptMapping::selectRaw('max(attempt) as max_attempt,max(completion_date) as max_submission,profile_id')->where("survey_id", "=", $id)->where("deleted_at", "=", null)->groupBy("profile_id")->whereNotNull("completion_date")->orderBy('completion_date', 'desc');

        if ($request->has('filters') && !empty($request->filters)) {
            $count->whereIn('profile_id', $profileIds, 'and', $type);
        }

        $this->model = [];
        $countInt = count($count->get());

        $data = ["answer_count" => $countInt];

        $respondent = $count->skip($skip)->take($take)
            ->get();
        foreach ($respondent as $profile) {

            $profileCopy = $profile->profile->toArray();
            $profileCopy["submission_count"] = $profile->max_attempt;
            $profileCopy["last_submission"] = $profile->max_submission;
            $data['report'][] = $profileCopy;
        }

        $this->model = $data;
        return $this->sendResponse();
    }

    public function inputAnswers($id, $question_id, $option_id, Request $request)
    {

        $checkIFExists = $this->model->where("id", "=", $id)->first();
        if (empty($checkIFExists)) {
            $this->model = false;
            return $this->sendNewError("Invalid Survey");
        }

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                $this->model = false;
                return $this->sendNewError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            $this->model = false;
            return $this->sendNewError("Only Survey Admin can view this report");
        }

        if ($request->has('filters') && !empty($request->filters)) {
            $getFiteredProfileIds = $this->getProfileIdOfFilter($checkIFExists, $request);
            $profileIds = $getFiteredProfileIds['profile_id'];
            $type = $getFiteredProfileIds['type'];
        }



        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $idsAttemptMapping = [];
        $resumeArray = SurveyAttemptMapping::select("profile_id", "attempt")->where("survey_id", "=", $id)->whereNull("deleted_at")->whereNull("completion_date")->get();
        foreach ($resumeArray as $resume) {
            $idsAttemptMapping[$resume->profile_id][] = $resume->attempt;
        }
        $answers = SurveyAnswers::where("survey_id", "=", $id)->where("question_id", "=", $question_id)->where("option_id", "=", $option_id)->whereNull("deleted_at")->orderBy('created_at', 'desc');
        
        if ($request->has('filters') && !empty($request->filters)) {
            $answers->whereIn('profile_id', $profileIds, 'and', $type);
        }


        $this->model = [];
        $answersCompleted = $answers->get()->filter(function ($ans) use ($idsAttemptMapping) {

            return isset($idsAttemptMapping[$ans->profile_id]) ? !in_array($ans->attempt, $idsAttemptMapping[$ans->profile_id]) : true;
        });
        $data = ["answer_count" => $answersCompleted->count(), "report" => []];

        $respondent = $answers->skip($skip)->take($take)
            ->get()->filter(function ($ans) use ($idsAttemptMapping) {

                return isset($idsAttemptMapping[$ans->profile_id]) ? !in_array($ans->attempt, $idsAttemptMapping[$ans->profile_id]) : true;
            });

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
            $this->model = false;
            return $this->sendNewError("Invalid Survey");
        }

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        // if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
        //     $companyId = $checkIFExists->company_id;
        //     $userId = $request->user()->id;
        //     $company = Company::find($companyId);
        //     $userBelongsToCompany = $company->checkCompanyUser($userId);
        //     if (!$userBelongsToCompany) {
        //         $this->model = false;
        //         return $this->sendNewError("User does not belong to this company");
        //     }
        // }
        // else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
        //     $this->model = false;
        //     return $this->sendNewError("Only Survey Admin can view this report");
        // }

        $colorCodeList = $this->colorCodeList;

        $prepareNode = ["reports" => []];
        $rankMapping = [];
        $optionValues = [];

        $getJsonQues = [];
        $getJson = json_decode($checkIFExists["form_json"], true);
        $sectionQuesCount = [];
        $sectionKeys = [];
        $sectionKey = 0;
        $attempt = isset($request->submission) ? $request->submission : 1;

        if ($checkIFExists["is_section"]) {         //for section type form_json

            foreach ($getJson as $key => $section) {
                if (isset($section["questions"]) && count($section["questions"])) {
                    $sectionQuesCount[$key] = count($section["questions"]);
                    $sectionKeys[] = $key;
                    $getJsonQues = array_merge($getJsonQues, $section["questions"]);
                    $getJson[$key]["questions"] = [];
                }
            }
        } else {
            $getJsonQues = $getJson;
        }

        $counter = 0;

        foreach ($getJsonQues as $values) {
            shuffle($colorCodeList);
            $answers = SurveyAnswers::where("survey_id", "=", $id)->where("question_type", "=", $values["question_type"])->where("question_id", "=", $values["id"])->where("profile_id", "=", $profile_id)->whereNull("deleted_at")->where("attempt", $attempt)->get();

            $pluckOpId = $answers->pluck("option_id")->toArray();

            $prepareNode["reports"][$counter]["question_id"] = $values["id"];
            $prepareNode["reports"][$counter]["title"] = $values["title"];
            $prepareNode["reports"][$counter]["description"] = $values["description"];
            $prepareNode["reports"][$counter]["question_type"] = $values["question_type"];
            $prepareNode["reports"][$counter]["image_meta"] = (!is_array($values["image_meta"]) ? json_decode($values["image_meta"]) : $values["image_meta"]);
            $prepareNode["reports"][$counter]["video_meta"] = (!is_array($values["video_meta"]) ? json_decode($values["video_meta"]) : $values["video_meta"]);
            $prepareNode["reports"][$counter]["is_answered"] = false;

            if (isset($values["max"])) {
                $prepareNode["reports"][$counter]["max"] = $values["max"];
            }
            if (isset($values["min"])) {
                $prepareNode["reports"][$counter]["min"] = $values["min"];
            }
            if (isset($values["minLabel"])) {
                $prepareNode["reports"][$counter]["minLabel"] = $values["minLabel"];
            }
            if (isset($values["maxLabel"])) {
                $prepareNode["reports"][$counter]["maxLabel"] = $values["maxLabel"];
            }

            if ($values['question_type'] == config("constant.SURVEY_QUESTION_TYPES.RANGE")) {
                $count = 0;
                $pluckOpId = $answers->pluck("answer_value")->toArray();

                for ($min = $values["min"]; $min <= $values['max']; $min++) {
                    $prepareNode["reports"][$counter]["options"][$count]["value"] = $min;
                    if (in_array($min, $pluckOpId))
                        $prepareNode["reports"][$counter]["options"][$count]["is_answered"] = true;
                    else
                        $prepareNode["reports"][$counter]["options"][$count]["is_answered"] = false;
                    $prepareNode["reports"][$counter]["options"][$count]["color_code"] = (isset($colorCodeList[$min]) ? $colorCodeList[$min] : "#fcda02");
                    $prepareNode["reports"][$counter]["options"][$count]["option_type"] = 0;
                    $count++;
                }
            }
            if ($values['question_type'] == config("constant.SURVEY_QUESTION_TYPES.RANK")) {
                $optionValues = $answers->pluck("answer_value")->toArray();

                foreach ($values["options"] as $option) {
                    $rankMapping[$option["id"]] = $option["title"];
                }
            }
            if ($answers->count()) {
                $optCounter = 0;
                $answers = $answers->toArray();
                $prepareNode["reports"][$counter]["is_answered"] = true;

                if (isset($values["multiOptions"])) {
                    foreach ($values["multiOptions"]["row"] as $row) {
                        $columnCounter = 0;
                        if ($values['question_type'] == config("constant.SURVEY_QUESTION_TYPES.MULTI_SELECT_RADIO")) {
                            $answerValue = SurveyAnswers::where("survey_id", "=", $id)->where("question_type", "=", $values["question_type"])->where("question_id", "=", $values["id"])->where("answer_value", $row["id"])->where("profile_id", "=", $profile_id)->whereNull("deleted_at")->where("attempt", $attempt)->get();
                            $answerValues = $answerValue->pluck("option_id")->toArray();
                        } else {
                            $answerValue = SurveyAnswers::where("survey_id", "=", $id)->where("question_type", "=", $values["question_type"])->where("question_id", "=", $values["id"])->where("option_id", $row["id"])->where("profile_id", "=", $profile_id)->whereNull("deleted_at")->where("attempt", $attempt)->get();
                            $answerValues = $answerValue->pluck("answer_value")->toArray();
                        }
                        $flip = array_flip($answerValues);

                        foreach ($values["multiOptions"]["column"] as $column) {
                            $pos = (isset($flip[$column["id"]]) ? true : false);

                            $prepareNode["reports"][$counter]["options"][$optCounter]["id"] = $row["id"];
                            $prepareNode["reports"][$counter]["options"][$optCounter]["value"] = $row["title"];
                            $prepareNode["reports"][$counter]["options"][$optCounter]["column"][$columnCounter]["id"] = $column["id"];
                            $prepareNode["reports"][$counter]["options"][$optCounter]["column"][$columnCounter]["value"] = $column["title"];
                            $prepareNode["reports"][$counter]["options"][$optCounter]["column"][$columnCounter]["option_type"] = 0;
                            $prepareNode["reports"][$counter]["options"][$optCounter]["column"][$columnCounter]["is_answered"] = $pos;


                            if ($values["question_type"] != config("constant.MEDIA_SURVEY_QUESTION_TYPE")) {
                                $prepareNode["reports"][$counter]["options"][$optCounter]["column"][$columnCounter]["color_code"] = (isset($colorCodeList[$optCounter]) ? $colorCodeList[$optCounter] : "#fcda02");
                            } else {
                                $prepareNode["reports"][$counter]["options"][$optCounter]["allowed_media"] = (isset($column["allowed_media"]) ? $column["allowed_media"] : []);
                                // $imageMeta = $answers->pluck("image_meta")->toArray();
                                $prepareNode["reports"][$counter]["options"][$optCounter]["files"]["image_meta"] = (!is_array($answers[$pos]["image_meta"]) ? json_decode($answers[$pos]["image_meta"], true) : $answers[$pos]["image_meta"]);

                                // $videoMeta = $answers->pluck("video_meta")->toArray();
                                $prepareNode["reports"][$counter]["options"][$optCounter]["files"]["video_meta"] = (!is_array($answers[$pos]["video_meta"]) ? json_decode($answers[$pos]["video_meta"], true) : $answers[$pos]["video_meta"]);

                                // $documentMeta = $answers->pluck("document_meta")->toArray();
                                $prepareNode["reports"][$counter]["options"][$optCounter]["files"]["document_meta"] = (!is_array($answers[$pos]["document_meta"]) ? json_decode($answers[$pos]["document_meta"], true) : $answers[$pos]["document_meta"]);

                                // $mediaUrl = $answers->pluck("media_url")->toArray();
                                $prepareNode["reports"][$counter]["options"][$optCounter]["files"]["media_url"] = (!is_array($answers[$pos]["media_url"]) ? json_decode($answers[$pos]["media_url"], true) : $answers[$pos]["media_url"]);
                            }
                            $columnCounter++;
                        }
                        $optCounter++;
                    }
                } elseif (isset($values["options"])) {

                    foreach ($values["options"] as $optVal) {
                        if (in_array($optVal["id"], $pluckOpId) || (isset($values["max"]) && in_array($optVal["id"], $optionValues))) {

                            if ($values['question_type'] == config("constant.SURVEY_QUESTION_TYPES.RANK")) {
                                $flip = array_flip($optionValues);
                            } else {
                                $flip = array_flip($pluckOpId);
                            }

                            $pos = (isset($flip[$optVal["id"]]) ? $flip[$optVal["id"]] : false);
                            if ($pos === false) {
                                continue;
                            }

                            $prepareNode["reports"][$counter]["is_answered"] = (($answers[$pos]["option_id"] == null) ? false : true);

                            if ($values['question_type'] == config("constant.SURVEY_QUESTION_TYPES.RANK")) {
                                $prepareNode["reports"][$counter]["options"][$optCounter]["id"] = $answers[$pos]["option_id"];
                                $prepareNode["reports"][$counter]["options"][$optCounter]["value"] = $rankMapping[$answers[$pos]["answer_value"]];
                                $prepareNode["reports"][$counter]["options"][$optCounter]["option_type"] = 0;
                                $prepareNode["reports"][$counter]["options"][$optCounter]["image_meta"] = (!is_array($answers[$pos]["image_meta"]) ? json_decode($answers[$pos]["image_meta"], true) : $answers[$pos]["image_meta"]);
                                $prepareNode["reports"][$counter]["options"][$optCounter]["video_meta"] = (!is_array($answers[$pos]["video_meta"]) ? json_decode($answers[$pos]["video_meta"], true) : $answers[$pos]["video_meta"]);
                            } else {
                                $prepareNode["reports"][$counter]["options"][$optCounter]["id"] = $optVal["id"];
                                $prepareNode["reports"][$counter]["options"][$optCounter]["value"] = $answers[$pos]["answer_value"];
                                $prepareNode["reports"][$counter]["options"][$optCounter]["option_type"] = $optVal["option_type"];
                                $prepareNode["reports"][$counter]["options"][$optCounter]["image_meta"] = (!is_array($optVal["image_meta"]) ? json_decode($optVal["image_meta"], true) : $optVal["image_meta"]);
                                $prepareNode["reports"][$counter]["options"][$optCounter]["video_meta"] = (!is_array($optVal["video_meta"]) ? json_decode($optVal["video_meta"], true) : $optVal["video_meta"]);
                            }


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
                }
            }
            if (isset($prepareNode["reports"][$counter]["options"])) {
                if ($values['question_type'] == config("constant.SURVEY_QUESTION_TYPES.RANK")) {
                    uasort($prepareNode["reports"][$counter]["options"], function ($a, $b) {

                        return ($a['id'] < $b['id']) ? -1 : 1;
                    });
                    $prepareNode["reports"][$counter]["options"] = array_values($prepareNode["reports"][$counter]["options"]);
                }
            }

            //for sections having questions 
            if ($checkIFExists["is_section"] && count($sectionKeys)) {

                if (count($getJson[$sectionKeys[$sectionKey]]["questions"]) < $sectionQuesCount[$sectionKeys[$sectionKey]]) {
                    $getJson[$sectionKeys[$sectionKey]]["questions"][] = $prepareNode["reports"][$counter];
                } else {
                    $sectionKey++;
                    $getJson[$sectionKeys[$sectionKey]]["questions"][] = $prepareNode["reports"][$counter];
                }
            }
            //

            $answers = [];
            $counter++;
        }

        //multi submission new attributes added
        $survey = [];
        $survey["id"] = $id;
        $survey["title"] = $checkIFExists->title;
        $submission_count = 0;
        $completion_date = NULL;
        $applicantInfo = SurveyAttemptMapping::where("survey_id", $id)->where("profile_id", $profile_id)->whereNotNull("completion_date");
        if (!empty($applicantInfo->first())) {
            $submission_count =  $applicantInfo->orderBy("completion_date", "desc")->first()->attempt;
            $completion_date = $applicantInfo->where("attempt", $attempt)->first()->completion_date;
        }

        $prepareNode["survey"] = $survey;

        $result = [];
        $result["survey"] = $survey;
        $result["submission_count"] = $submission_count;
        $result["completion_date"] = $completion_date;
        if (!$checkIFExists["is_section"]) {  //for normal survey
            $result["reports"] = $prepareNode["reports"];
            $this->model = $result;
        } else {
            $result["reports"] = $getJson;
            $this->model = $result;
        }

        $this->messages = "Report Successful";
        return $this->sendResponse();
    }

    public function mediaList($id, $question_id, $media_type, Request $request)
    {

        if (!in_array($media_type, ["image_meta", "video_meta", "document_meta", "media_url"])) {
            $this->model = false;
            return $this->sendNewError("Invalid Media Type");
        }

        $checkIFExists = $this->model->where("id", "=", $id)->first();
        if (empty($checkIFExists)) {
            $this->model = false;
            return $this->sendNewError("Invalid Survey");
        }

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                $this->model = false;
                return $this->sendNewError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            $this->model = false;
            return $this->sendNewError("Only Survey Admin can view this report");
        }


        if ($request->has('filters') && !empty($request->filters)) {
            $getFiteredProfileIds = $this->getProfileIdOfFilter($checkIFExists, $request);
            $profileIds = $getFiteredProfileIds['profile_id'];
            $type = $getFiteredProfileIds['type'];
        }


        $retrieveMediaAnswers = SurveyAnswers::where("is_active", "=", 1)->where("question_id", "=", $question_id)->where("question_type", "=", config("constant.MEDIA_SURVEY_QUESTION_TYPE"))->where("survey_id", "=", $id)->whereNull("deleted_at");

        if ($request->has('filters') && !empty($request->filters)) {
            $retrieveMediaAnswers->whereIn('profile_id', $profileIds, 'and', $type);
        }

        $retrieveAnswers = $retrieveMediaAnswers->get();
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
            $this->model = false;
            return $this->sendNewError("Invalid Survey");
        }

        if ($request->has('filters') && !empty($request->filters)) {
            $getFiteredProfileIds = $this->getProfileIdOfFilter($checkIFExists, $request);
            $profileIds = $getFiteredProfileIds['profile_id'];
            $type = $getFiteredProfileIds['type'];
        }

        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                $this->model = false;
                return $this->sendNewError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            // return $this->sendNewError("Only Survey Admin can view this report");
        }

        $headers = [];
        $getJson = json_decode($checkIFExists["form_json"], true);
        $questionIdMapping = [];
        $rankMapping = [];
        $rankOptionMapping = [];
        $rankWeightage = [];
        $rankExists = 0;


        $getJsonQues = [];

        if ($checkIFExists["is_section"]) {         //for section type form_json

            foreach ($getJson as $key => $section) {
                if (isset($section["questions"]) && count($section["questions"])) {
                    $getJsonQues = array_merge($getJsonQues, $section["questions"]);
                }
            }
        } else {
            $getJsonQues = $getJson;
        }

        foreach ($getJsonQues as $values) {

            $questionIdMapping[$values["id"]] = html_entity_decode($values["title"]);

            if ($values['question_type'] == config("constant.SURVEY_QUESTION_TYPES.RANK")) {
                for ($i = 1; $i <= $values["max"]; $i++) {
                    $rankMapping[$values["id"]][$i] = "[Rank$i]";
                }
                foreach ($values["options"] as $option) {
                    $optionTitle = html_entity_decode($option['title']);
                    $rankOptionMapping[$values["id"]][$option['id']] = $optionTitle;
                    $rankWeightage[$optionTitle]['count'] = 0;
                    $rankWeightage[$optionTitle]["sum"] = 0;
                }
            } elseif ($values['question_type'] == config("constant.SURVEY_QUESTION_TYPES.MULTI_SELECT_RADIO")) {
                foreach ($values["multiOptions"]["row"] as $row) {
                    $multiChoiceRadioRow[$values["id"]][$row["id"]] = "[" . html_entity_decode($row["title"]) . "]";
                }
                foreach ($values["multiOptions"]["column"] as $column) {
                    $multiChoiceRadioColumn[$values["id"]]["column"][$column["id"]] = html_entity_decode($column['title']);
                }
            } elseif ($values['question_type'] == config("constant.SURVEY_QUESTION_TYPES.MULTI_SELECT_CHECK")) {
                foreach ($values["multiOptions"]["row"] as $row) {
                    $multiChoiceCheckRow[$values["id"]][$row["id"]] = "[" . html_entity_decode($row["title"]) . "]";
                }
                foreach ($values["multiOptions"]["column"] as $column) {
                    $multiChoiceCheckColumn[$values["id"]]["column"][$column["id"]] = html_entity_decode($column['title']);
                }
            }
        }
        // dd($questionIdMapping);
        $applicants = SurveyAttemptMapping::where("survey_id", "=", $id)->whereNotNull("completion_date")->groupBy("profile_id")->where("deleted_at", "=", null);



        if ($request->has('filters') && !empty($request->filters)) {
            $applicants->whereIn('profile_id', $profileIds, 'and', $type);
        }

        $getCount = $applicants->get();


        $pluck = $getCount->pluck("profile_id")->toArray();

        $getSurveyAnswers = SurveyAnswers::where("survey_id", "=", $id)->whereNull("deleted_at");
        //
        $resumeArray = SurveyAttemptMapping::select("profile_id", "attempt")->where("survey_id", "=", $id)->whereNull("deleted_at")->whereNull("completion_date")->get();
        $idsAttemptMapping = [];

        foreach ($resumeArray as $resume) {
            $idsAttemptMapping[$resume->profile_id][] = $resume->attempt;
        }

        if ($request->has("profile_ids") && !empty($request->input("profile_ids"))) {
            $getSurveyAnswers = $getSurveyAnswers->whereIn("profile_id", $request->profile_ids);
        } else if ($request->has('filters') && !empty($request->filters)) {
            $getSurveyAnswers = $getSurveyAnswers->whereIn("profile_id", $pluck);
        }

        $getSurveyAnswers = $getSurveyAnswers->get()->filter(function ($ans) use ($idsAttemptMapping) {

            return isset($idsAttemptMapping[$ans->profile_id]) ? !in_array($ans->attempt, $idsAttemptMapping[$ans->profile_id]) : true;
        });
        $counter = 0;
        foreach ($getSurveyAnswers as $answers) {
            if (!isset($headers[$answers->profile_id][$answers->attempt])) {
                $counter++;
                $headers[$answers->profile_id][$answers->attempt] =  ["Sr no" => $counter, "Name" => null, "Email" => null, "Age" => null, "Phone" => null, "City" => null, "Hometown" => null, "Profile Url" => null, "Timestamp" => null];
                foreach ($questionIdMapping as $key => $value) {

                    if (isset($rankMapping[$key])) {
                        foreach ($rankMapping[$key] as  $v) {
                            $headers[$answers->profile_id][$answers->attempt][$value . $v . "_(" . $key . ")_"] = null;
                        }
                    } elseif (isset($multiChoiceRadioRow[$key])) {
                        foreach ($multiChoiceRadioRow[$key] as $v) {
                            $headers[$answers->profile_id][$answers->attempt][$value . $v . "_(" . $key . ")_"] = null;
                        }
                    } elseif (isset($multiChoiceCheckRow[$key])) {
                        foreach ($multiChoiceCheckRow[$key] as $v) {
                            $headers[$answers->profile_id][$answers->attempt][$value . $v . "_(" . $key . ")_"] = null;
                        }
                    } else {

                        $headers[$answers->profile_id][$answers->attempt][$value . "_(" . $key . ")_"] = null;
                    }
                }
            }
            $image = (!is_array($answers->image_meta) ? json_decode($answers->image_meta, true) : $answers->image_meta);
            $video = (!is_array($answers->video_meta) ? json_decode($answers->image_meta, true) : $answers->video_meta);
            $doc = (!is_array($answers->document_meta) ? json_decode($answers->document_meta, true) : $answers->document_meta);
            $url = (!is_array($answers->media_url) ? json_decode($answers->media_url, true) : $answers->media_url);
            if (isset($questionIdMapping[$answers->question_id])) {
                // if (!isset($headers[$answers->profile_id])) {
                //     ;
                // }
                // $headers[$answers->profile_id]["Sr no"] = $counter;
                $headers[$answers->profile_id][$answers->attempt]["Name"] = html_entity_decode($answers->profile->name);
                $headers[$answers->profile_id][$answers->attempt]["Email"] = html_entity_decode($answers->profile->email);
                $headers[$answers->profile_id][$answers->attempt]["Age"] = floor((time() - strtotime($answers->profile->dob)) / 31556926);
                $headers[$answers->profile_id][$answers->attempt]["Phone"] = \DB::Table("profiles")->where("id", "=", $answers->profile->id)->first()->phone;
                $headers[$answers->profile_id][$answers->attempt]["City"] = html_entity_decode($answers->profile->city);
                $headers[$answers->profile_id][$answers->attempt]["Hometown"] = html_entity_decode($answers->profile->hometown);
                $headers[$answers->profile_id][$answers->attempt]["Profile Url"] = env('APP_URL') . "/@" . html_entity_decode($answers->profile->handle);
                $headers[$answers->profile_id][$answers->attempt]["Timestamp"] = date("Y-m-d H:i:s", strtotime($answers->created_at)) . " GMT +5.30";

                $ans = "";

                if ($answers->question_type == config("constant.SURVEY_QUESTION_TYPES.RANK") && isset($rankMapping[$answers->question_id][$answers->option_id])) {
                    $rankExists++;
                    if (isset($headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . $rankMapping[$answers->question_id][$answers->option_id] . "_(" . $answers->question_id . ")_"]) && !empty($headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . $rankMapping[$answers->question_id][$answers->option_id] . "_(" . $answers->question_id . ")_"]) && !empty($answers->answer_value)) {
                        $ans .= $headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . $rankMapping[$answers->question_id][$answers->option_id] . "_(" . $answers->question_id . ")_"] . ";";
                    }
                    $ans .= html_entity_decode($rankOptionMapping[$answers->question_id][$answers->answer_value]);
                    $rankWeightage[$rankOptionMapping[$answers->question_id][$answers->answer_value]]['sum'] += (int)$answers->option_id;
                    $rankWeightage[$rankOptionMapping[$answers->question_id][$answers->answer_value]]['count']++;
                } elseif ($answers->question_type == config("constant.SURVEY_QUESTION_TYPES.MULTI_SELECT_RADIO") && isset($multiChoiceRadioRow[$answers->question_id][$answers->answer_value])) {

                    if (isset($headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . $multiChoiceRadioRow[$answers->question_id][$answers->answer_value] . "_(" . $answers->question_id . ")_"]) && !empty($headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . $multiChoiceRadioRow[$answers->question_id][$answers->answer_value] . "_(" . $answers->question_id . ")_"]) && !empty($answers->option_id)) {
                        $ans .= $headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . $multiChoiceRadioRow[$answers->question_id][$answers->answer_value] . "_(" . $answers->question_id . ")_"] . ";";
                    }
                    $ans .= html_entity_decode($multiChoiceRadioColumn[$answers->question_id]["column"][$answers->option_id]);
                } elseif ($answers->question_type == config("constant.SURVEY_QUESTION_TYPES.MULTI_SELECT_CHECK") && isset($multiChoiceCheckRow[$answers->question_id][$answers->option_id])) {
                    if (isset($headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . $multiChoiceCheckRow[$answers->question_id][$answers->option_id] . "_(" . $answers->question_id . ")_"]) && !empty($headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . $multiChoiceCheckRow[$answers->question_id][$answers->option_id] . "_(" . $answers->question_id . ")_"]) && !empty($answers->answer_value)) {
                        $ans .= $headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . $multiChoiceCheckRow[$answers->question_id][$answers->option_id] . "_(" . $answers->question_id . ")_"] . ";";
                    }
                    $ans .= html_entity_decode($multiChoiceCheckColumn[$answers->question_id]["column"][$answers->answer_value]);
                } elseif ($answers->question_type <= config("constant.SURVEY_QUESTION_TYPES.RANGE")) {

                    if (isset($headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . "_(" . $answers->question_id . ")_"]) && !empty($headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . "_(" . $answers->question_id . ")_"]) && !empty($answers->answer_value)) {
                        $ans .= $headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . "_(" . $answers->question_id . ")_"] . ";";
                    }
                    $ans .= html_entity_decode($answers->answer_value);
                }




                $ans = trim($ans, " ");
                $p = false;
                if (!empty($image) && is_array($image)) {
                    if (!empty($answers->answer_value) && !empty(array_column($image, "original_photo"))) {
                        $ans .= ";";
                    }
                    $ans .= implode(";", array_column($image, "original_photo"));
                    $p = true;
                }

                if (!empty($video) && is_array($video)) {
                    if ($p && !empty(array_column($video, "video_url"))) {
                        $ans .= ";";
                    }
                    $ans .= implode(";", array_column($video, "video_url"));
                }

                if (!empty($doc) && is_array($doc)) {
                    if ($p && !empty(array_column($doc, "document_url"))) {
                        $ans .= ";";
                    }
                    $ans .= implode(";", array_column($doc, "document_url"));
                }

                if (!empty($url) && is_array($url)) {
                    if ($p && !empty(array_column($url, "url"))) {
                        $ans .= ";";
                    }
                    $ans .=   implode(";", array_column($url, "url"));
                }
                if ($answers->question_type == config("constant.SURVEY_QUESTION_TYPES.RANK") && isset($rankMapping[$answers->question_id][$answers->option_id])) {
                    $headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . $rankMapping[$answers->question_id][$answers->option_id] . "_(" . $answers->question_id . ")_"] = $ans;
                } elseif ($answers->question_type == config("constant.SURVEY_QUESTION_TYPES.MULTI_SELECT_RADIO") && isset($multiChoiceRadioRow[$answers->question_id][$answers->answer_value])) {
                    $headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . $multiChoiceRadioRow[$answers->question_id][$answers->answer_value] . "_(" . $answers->question_id . ")_"] = $ans;
                } elseif ($answers->question_type == config("constant.SURVEY_QUESTION_TYPES.MULTI_SELECT_CHECK") && isset($multiChoiceCheckRow[$answers->question_id][$answers->option_id])) {
                    $headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . $multiChoiceCheckRow[$answers->question_id][$answers->option_id] . "_(" . $answers->question_id . ")_"] = $ans;
                } else {
                    $headers[$answers->profile_id][$answers->attempt][$questionIdMapping[$answers->question_id] . "_(" . $answers->question_id . ")_"] = $ans;
                }
            }
        }

        $finalData = array_values($headers);
        $result = [];
        foreach ($finalData as $value) {
            foreach ($value as $row) {
                $result[] = $row;
            }
        }

        $relativePath = "reports/surveysAnsweredExcel";
        $name = "surveys-" . $id . "-" . uniqid();

        $excel = Excel::create($name, function ($excel) use ($name, $result) {
            // Set the title
            $excel->setTitle($name);

            // Chain the setters
            $excel->setCreator('Tagtaste')
                ->setCompany('Tagtaste');

            // Call them separately
            $excel->setDescription('Survey Response List');

            $excel->sheet('Sheetname', function ($sheet) use ($result) {
                $sheet->fromArray($result, null, 'A1', true, true);
                // ->getFont()->setBold(true);

                foreach ($sheet->getColumnIterator() as $row) {
                    $cellcount = 0;
                    foreach ($row->getCellIterator() as $cell) {

                        if (!is_null($cell->getValue()) && str_contains($cell->getValue(), '/@')) {
                            $cell_link = $cell->getValue();
                            $cell->getHyperlink()
                                ->setUrl($cell_link)
                                ->setTooltip('Click here to access profile');
                        }
                        if ($cellcount == 0 && str_contains($cell->getValue(), '_(')) $cell->setValueExplicit(substr($cell->getValue(), 0, strpos($cell->getValue(), "_(")));
                        $cellcount++;
                    }
                }
            })->store('xlsx', false, true);
        });
        $excel->getActiveSheet()->getStyle("1:1")->getFont()->setBold(true);
        $excel_save_path = storage_path("exports/" . $excel->filename . ".xlsx");
        $s3 = \Storage::disk('s3');
        $resp = $s3->putFile($relativePath, new File($excel_save_path), ['visibility' => 'public']);
        $this->model = \Storage::url($resp);
        unlink($excel_save_path);

        return $this->sendResponse();
    }


    public function closeSurveys($id, Request $request)
    {

        $survey = \DB::table("surveys")->where("id", "=", trim($id))->first();

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if (isset($survey->company_id) && !empty($survey->company_id)) {
            $companyId = $survey->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                $this->model = false;
                return $this->sendNewError("User does not belong to this company");
            }
        } else if (isset($survey->profile_id) &&  $survey->profile_id != $request->user()->profile->id) {
            $this->model = false;
            return $this->sendNewError("Only Admin can close the survey");
        }

        if ($survey->state == config("constant.SURVEY_STATES.CLOSED")) {
            $this->model = false;
            return $this->sendNewError("Survey Already Closed");
        }

        // if ($survey->state == config("constant.SURVEY_STATES.EXPIRED")) {
        //     return $this->sendNewError("Survey Already Expired");
        // }



        $reasonId = $request->input('reason_id');

        $getReason = $this->surveyCloseReason();
        $reasonList = $getReason->original["data"];

        $ar = [];
        foreach ($reasonList as $v) {
            $ar[$v["id"]] = $v;
        }

        $this->model = false;
        if (isset($ar[$reasonId])) {
            $reason = $ar[$reasonId]["reason"];
            $description = $request->input('description');

            $data = ['survey_id' => $survey->id, 'reason' => $reason, 'other_reason' => $description];
        } else {
            return $this->sendNewError("Please select valid reason");
        }

        $close = Surveys::where("id", "=", $survey->id);
        $survey = $close->update(["state" => config("constant.SURVEY_STATES.CLOSED")]);
        $get = $close->first();
        PaymentDetails::where("model_id", $id)->update(["is_active" => 0]);
        $this->messages = "Survey Close Failed";
        if ($survey) {
            $this->model = \DB::table('surveys_close_reasons')->insert($data);;
            $this->messages = "Survey Closed Successfully";
            $get->removeFromGraph(); // remove node and edge from neo4j
            event(new DeleteFeedable($get));
        }
        return $this->sendResponse();
    }

    public function surveyCloseReason()
    {
        $data[] = ['id' => 1, 'reason' => 'Completed'];
        $data[] = ['id' => 2, 'reason' => 'Not enough responses'];
        $data[] = ['id' => 3, 'reason' => 'Other'];
        $this->model = $data;
        return $this->sendResponse();
    }

    public function dynamicMandatoryFields(Request $request)
    {
        $type = $request->has('type') ? $request->type : [];
        $this->model = [];
        $fields = \DB::table('surveys_mandatory_fields')->get();
        foreach ($fields as $field) {
            $is_selected = 0;
            $data = [];
            foreach ($type as $t) {
                if ($field->field == 'document_meta' && $t == 'document_required') {
                    $is_selected = 1;
                } else if ($field->field == 'address' && $t == 'hut') {
                    $is_selected = 1;
                }
            }
            $data['id'] = $field->id;
            $data['is_selected'] = $is_selected;
            $data['name'] = $field->name;
            $data['field'] = $field->field;
            $data['is_mandatory'] = $field->is_mandatory;
            $data = (object)$data;
            $this->model[] = $data;
        }

        return $this->sendResponse();
    }

    public function surveyMandatoryFields(Surveys $id, Request $request)
    {
        unset($this->model);
        $this->model = [];
        $fields = \DB::table('surveys_mandatory_fields')
            ->join('surveys_mandatory_fields_mapping', 'surveys_mandatory_fields.id', '=', 'surveys_mandatory_fields_mapping.mandatory_field_id')
            ->where('surveys_mandatory_fields_mapping.survey_id', $id->id)
            ->pluck('surveys_mandatory_fields.field');
        $this->model['mandatory_fields'] = $fields;
        $this->model['remaining_mandatory_fields'] = $request->user()->profile->getProfileCompletionAttribute($fields);
        return $this->sendResponse();
    }

    public function storeMandatoryFields($request, $surveyId)
    {

        \DB::table('surveys_mandatory_fields_mapping')->where('survey_id', $surveyId)->delete();
        $get = \DB::table('surveys_mandatory_fields')->where("is_mandatory", "=", 1)->get();
        $pluckData = $get->pluck("id")->toArray();
        if (isset($request->mandatory_field_ids) && !empty($request->mandatory_field_ids)) {
            $pluckData = array_merge($pluckData, $request->mandatory_field_ids);
        }
        $pluckData = array_unique($pluckData);
        if (!empty($pluckData)) {
            $insertData = [];
            foreach ($pluckData as $fieldId) {
                $insertData[] = ['mandatory_field_id' => $fieldId, 'survey_id' => $surveyId];
            }
            \DB::table('surveys_mandatory_fields_mapping')->insert($insertData);
        }
    }

    public function saveApplicants(Surveys $id, Request $request)
    {

        $loggedInprofileId = $request->user()->profile->id;

        $isInvited = 0;

        $loggedInprofileId = $request->user()->profile->id;
        $checkApplicant = \DB::table("survey_applicants")->where('survey_id', $id->id)->where('profile_id', $loggedInprofileId)->whereNull('deleted_at')->first();
        if (!empty($checkApplicant) && $checkApplicant->application_status == config("constant.SURVEY_APPLICANT_ANSWER_STATUS.COMPLETED") && !($id->multi_submission)) {
            $this->model = false;
            return $this->sendNewError("Already Applied");
        }


        if ($request->has('applier_address')) {
            $applierAddress = $request->input('applier_address');
            $address = json_decode($applierAddress, true);
            $city = (isset($address['survey_city'])) ? $address['survey_city'] : null;
        } else {
            $city = null;
            $applierAddress = null;
        }



        $profile = $request->user()->profile;
        if (empty($checkApplicant)) {
            $inputs = [
                'is_invited' => $isInvited, 'profile_id' => $loggedInprofileId, 'survey_id' => $id->id,
                'message' => $request->input('message'), 'address' => $applierAddress,
                'city' => $city, 'age_group' => $this->calcDobRange(date("Y", strtotime($profile->dob))), 'gender' => $profile->gender, 'hometown' => $profile->hometown, 'current_city' => $profile->city, "completion_date" => null, "created_at" => date("Y-m-d H:i:s")
            ];


            $ins = \DB::table('survey_applicants')->insert($inputs);
        } else {
            $update = [];
            if (empty($checkApplicant->address)) {
                $update['address'] = $applierAddress;
            }
            if (empty($checkApplicant->city)) {
                $update['city'] = $city;
            }


            if (empty($checkApplicant->age_group)) {
                $update['age_group'] = $this->calcDobRange(date("Y", strtotime($profile->dob)));
            }

            if ($checkApplicant->is_invited) {
                $hometown = $request->input('hometown');
                $current_city = $request->input('current_city');
                if (empty($checkApplicant->hometown)) {
                    $update['hometown'] = $hometown;
                }
                if (empty($checkApplicant->current_city)) {
                    $update['current_city'] = $current_city;
                }
            }

            if (!empty($update)) {
                $ins = \DB::table('survey_applicants')->where("id", "=", $checkApplicant->id)->update($update);
            }
        }
        $this->model = true;
        return $this->sendResponse();
    }

    public function calcDobRange($year)
    {

        if ($year > 2000) {
            return "gen-z";
        } else if ($year >= 1981 && $year <= 2000) {
            return "millenials";
        } else if ($year >= 1961 && $year <= 1980) {
            return "gen-x";
        } else {
            return "yold";
        }
    }

    public function getFilters($surveyId, Request $request)
    {
        return $this->getFilterParameters($surveyId, $request);
    }

    public function getAnswers($surveyId)
    {
        $getSurvey = Surveys::where("id", "=", $surveyId)->where("is_active", "=", 1)->first();

        $this->model = false;
        if (empty($getSurvey)) {
            $this->errors = ["Survey Doesn't Exists"];
            return $this->sendResponse();
        }

        $inProgress = surveyApplicants::where("survey_id", $surveyId)->where("profile_id", request()->user()->profile->id)->where("application_status", config("constant.SURVEY_APPLICANT_ANSWER_STATUS.INPROGRESS"))->whereNull("deleted_at")->first();

        $survey =  Surveys::where("id", $surveyId)->select("form_json", "is_section")->first();
        $form_json = json_decode($survey->form_json);
        if ($survey->is_section && !empty($inProgress)) {
            $attempt = SurveyAttemptMapping::where("survey_id", $surveyId)->where("profile_id", request()->user()->profile->id)->whereNull("completion_date")->whereNull("deleted_at")->first();
            foreach ($form_json as &$section) {
                if (isset($section->questions)) {
                    foreach ($section->questions as &$question) {
                        $answers =  SurveyAnswers::select("option_id", "document_meta", "media_url", "image_meta", "video_meta", "option_type", "answer_value as value")
                            ->where("survey_id", $surveyId)
                            ->where("question_id", $question->id)
                            ->where("attempt", $attempt->attempt)  //in progress attempt is total submission till now + 1
                            ->where("profile_id", request()->user()->profile->id)->whereNull("deleted_at")->get()->toArray();


                        if (!count($answers) && $question->is_mandatory) {
                            break 2;
                        } elseif (count($answers)) {
                            foreach ($answers as &$answer) {
                                $answer["image_meta"] = json_decode($answer["image_meta"]);
                                $answer["video_meta"] = json_decode($answer["video_meta"]);
                                $answer["document_meta"] = json_decode($answer["document_meta"]);
                                $answer["media_url"] = json_decode($answer["media_url"]);
                            }
                            $question->answer_value = $answers;
                        }
                    }
                }
            }
        }

        $this->messages = "Request successfull";
        $this->model = $form_json;
        return $this->sendResponse();
    }



    public function copy(Request $request, $id)
    {
        $survey = Surveys::where("id", "=", $id)->whereNull("deleted_at")->first();

        $this->model = [];
        if (empty($survey)) {
            $this->model = ["status" => false];
            return $this->sendNewError("Invalid Survey");
        }


        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if ($request->has('company_id')) {
            $companyId = $request->input('company_id');
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendNewError("User does not belong to this company");
            }
        }

        $prepData["id"] = (string) Uuid::generate(4);
        $prepData["is_active"] = $survey->is_active;
        $prepData["profile_id"] = $request->user()->profile->id;
        $prepData["state"] = config("constant.SURVEY_STATES.DRAFT");
        $prepData["title"] = mb_substr("Copied - " . $survey->title, 0, 150);
        $prepData["description"] = $survey->description;
        $prepData["privacy_id"] = 1;
        $prepData["is_section"] = $survey->is_section;
        $prepData["multi_submission"] = $survey->multi_submission;
        $prepData["video_meta"] = $survey->video_meta;
        $prepData["image_meta"] = $survey->image_meta;
        $prepData["form_json"] = $survey->form_json;


        if ($request->has("company_id")) {
            $prepData["company_id"] = $request->company_id;
        }

        $prepData["expired_at"] = date("Y-m-d", strtotime("+30 days"));

        $data = [];


        $create = Surveys::create($prepData);
        if (isset($create->id)) {
            $this->model = ["status" => true];
            $data = [
                "id" => $create->id,
                "message" => "Your survey has been copied and saved to my survey",
                "button_text" => "VIEW"
            ];
        }

        return $this->sendNewResponse($data);
    }
}
