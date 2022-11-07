<?php

namespace App\Http\Controllers\Api\Quiz;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Quiz;
use Illuminate\Support\Facades\Validator;
use App\Company;
use App\QuizLike;
use Webpatser\Uuid\Uuid;
use App\Events\NewFeedable;
use App\Recipe\Profile;
use App\Events\Actions\Like;
use App\Events\Model\Subscriber\Create;
use App\Events\UpdateFeedable;
use Tagtaste\Api\SendsJsonResponse;
use App\Events\DeleteFeedable;
use App\Payment\PaymentDetails;
use Illuminate\Support\Facades\Redis;
use Exception;
use App\PeopleLike;
use DB;
use App\QuizAnswers;
use App\Payment\PaymentLinks;
use App\PaymentHelper;
use App\Events\TransactionInit;
use App\Http\Controllers\Api\Quiz\FilterTraits;
use App\QuizApplicants;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\File;

class QuizController extends Controller
{
    use SendsJsonResponse, FilterTraits;

 
    public function __construct(Quiz $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'image_meta' => 'array|nullable',
            'form_json' => 'required|array',
            'expired_at' => 'date_format:Y-m-d',
            'replay' => 'required'
        ]);


        $this->model = false;
        $this->messages = "Quiz Creation Failed";
        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return $this->sendResponse();
        }

        if ($request->has("expired_at") && !empty($request->expired_at) && (strtotime($request->expired_at) > strtotime("+30 days   "))) {
            return $this->sendError("Expiry time exceeds a month");
        }
        if ($request->has("expired_at") && !empty($request->expired_at) && strtotime($request->expired_at) < time()) {
            return $this->sendError("Expiry time invalid");
        }


        $final_json = $this->validateQuizFormJson($request);

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
        $prepData["profile_id"] = $request->user()->profile->id;
        $prepData["title"] = $request->title;
        $prepData["description"] = $request->description;
        $prepData["replay"] = $request->replay;
        $prepData["privacy_id"] = 1;
        $prepData["state"] = $request->state;


        if ($request->has("company_id")) {
            $prepData["company_id"] = $request->company_id;
        }
        if ($request->has("image_meta")) {
            $prepData["image_meta"] =  json_encode($request->image_meta);
        }

        if ($request->has("form_json")) {
            $prepData["form_json"] = json_encode($final_json);
        }


        if ($request->has("expired_at") && !empty($request->expired_at)) {
            $prepData["expired_at"] = date("Y-m-d", strtotime($request->expired_at));
        } else {
            $prepData["expired_at"] = date("Y-m-d", strtotime("+1 month"));
        }

        $create = Quiz::create($prepData);
        $create->form_json = $final_json;
        $create->image_meta = json_decode( $create->image_meta);

        if (isset($create->id)) {


            $quiz = Quiz::find($create->id);
            $this->model = $create;
            $this->messages = "Quiz Created Successfully";
            if ($quiz->state == config("constant.QUIZ_STATES.PUBLISHED")) {
                if ($request->has('company_id')) {
                    event(new NewFeedable($quiz, $company));
                } else {
                    event(new NewFeedable($quiz, $request->user()->profile));
                }
               event(new Create($quiz, $request->user()->profile));
    
                $quiz->addToCache();
                event(new UpdateFeedable($quiz));
            }
        }
        return $this->sendResponse();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getQuiz = Quiz::where("id", "=", $id)->first();

        $this->model = false;
        $this->messages = "Quiz Doesn't Exists";
        if (empty($getQuiz)) {
            $this->errors = ["Quiz Doesn't Exists"];
            return $this->sendResponse();
        }

        $getQuiz["form_json"] = json_decode($getQuiz["form_json"], true);
        $getQuiz["image_meta"] = json_decode($getQuiz["image_meta"], true);
        $getData = $getQuiz->toArray();
        $getData["closing_reason"] = $getQuiz->getClosingReason();
        $this->messages = "Request successfull";
        $this->model = [
            "quiz" => $getData,
            "meta" => $getQuiz->getMetaFor(request()->user()->profile->id)
        ];
        return $this->sendResponse();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->model = false;
        $this->messages = "Quiz Failed";

        $create = Quiz::where("id", "=", $id);
        $getQuiz = $create->first();
        $previousState = $getQuiz->state;
        if (empty($getQuiz)) {
            $this->errors = ["Quiz Id is Invalid"];
            return $this->sendResponse();
        }

        $validator = Validator::make($request->all(), [
            'company_id' => 'nullable|exists:companies,id',
            'title' => 'required',
            'description' => 'required',
            'image_meta' => 'array|nullable',
            'form_json' => 'required|array',
            'expired_at' => 'date_format:Y-m-d',
            'replay' => 'required'
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->messages();
            return $this->sendResponse();
        }


        if ($request->has("expired_at") && !empty($request->expired_at) && (strtotime($request->expired_at) > strtotime("+30 days"))) {
            return $this->sendError("Expiry time exceeds a month");
        }
        if ($request->has("expired_at") && !empty($request->expired_at) && strtotime($request->expired_at) < time()) {
            return $this->sendError("Expiry time invalid");
        }

        $final_json = $this->validateQuizFormJson($request, $id);

        if (!empty($this->errors)) {
            return $this->sendResponse();
        }


        $prepData = (object)[];


        $prepData->state = isset($request->state) ? $request->state : config("constant.QUIZ_STATES.PUBLISHED");
        $prepData->title = $request->title;
        $prepData->replay = $request->replay;
        $prepData->description = $request->description;

        if ($request->has("image_meta")) {
            $prepData->image_meta = (is_array($request->image_meta) ? json_encode($request->image_meta) : $request->image_meta);
        }


        if ($request->has("form_json")) {
            $prepData->form_json = json_encode($final_json);
        }

        if ($request->has("updated_by")) {
            $prepData->updated_by = $request->user()->profile->id;
        }


        if ($request->has("expired_at") && !empty($request->expired_at)) {
            $prepData->expired_at = date("Y-m-d", strtotime($request->expired_at));
        }


        $create->update((array)$prepData);

        $this->model = true;
        $this->messages = "Quiz Updated Successfully";


        //draft code
        if ($getQuiz->state == config("constant.QUIZ_STATES.DRAFT") && $request->state == config("constant.QUIZ_STATES.PUBLISHED")) {
            //create new cache
            $getQuiz = $create->first();
            if ($request->has('company_id')) {
                event(new NewFeedable($getQuiz, Company::find($request->company_id)));
            } else {
                event(new NewFeedable($getQuiz, $request->user()->profile));
            }
            event(new Create($getQuiz, $request->user()->profile));

            $getQuiz->addToCache();
            event(new UpdateFeedable($getQuiz));
        } else if ($getQuiz->state == config("constant.QUIZ_STATES.PUBLISHED")) {
            //update cache
            $getQuiz = $create->first();

            $getQuiz->addToCache();
            event(new UpdateFeedable($getQuiz));
        }

        if (($previousState == config("constant.QUIZ_STATES.EXPIRED") || $previousState == config("constant.QUIZ_STATES.CLOSED"))
            && $request->state == config("constant.QUIZ_STATES.PUBLISHED")
        ) {
            $this->addQuizGraph($getQuiz); //add node and edge to neo4j
        }

        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->model = false;
        $delete = Quiz::where("id", "=", $id);
        $quiz = $delete->first();

        $this->messages = "Quiz Delete Failed";
        $deleteQuiz = Quiz::where("id", "=", $id)->update(["deleted_at" => date("Y-m-d H:i:s")]);
        if ($deleteQuiz) {
            $this->model = true;
            $this->messages = "Quiz Deleted Successfully";
            event(new DeleteFeedable($quiz));
            $quiz->removeFromGraph(); //Remove node and edge from neo4j
            $quiz->removeFromCache();
        }
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


    private function validateQuizFormJson($request, $isUpdation = false)
    {
        //FORM JSON Validation;
        $decodeJson = (is_array($request->form_json) ? $request->form_json : json_decode($request->form_json, true));
        if (!empty($decodeJson)) {
            if ($isUpdation) {
                $getOldJson = Quiz::where("id", "=", $isUpdation)->select("form_json")->first()->toArray();
                $oldJsonArray = $this->prepQuestionJson($getOldJson["form_json"]);
                $listOfQuestionIds = array_keys($oldJsonArray);
            }
            //required node for questions    
            $requiredNode = ["title", "question_type", "image_meta", "id", "options"];
            //required option nodes
            $optionNodeChecker = ["id", "image_meta", "title"];

            $maxQueId = 1;
            if ($isUpdation) {
                $maxQueId = max($listOfQuestionIds);
                $maxQueId++;
            }

            foreach ($decodeJson as &$values) {
                $diff = array_diff($requiredNode, array_keys($values));
                // echo (isset($values['id']));

                if (!$isUpdation || !isset($values['id']) || empty($values['id'])) {
                    $values['id'] = (int) $maxQueId;
                    $maxQueId++;
                }

                $values['id'] = (int) $values["id"];
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

                    $correctOptionChecker = false;
                    $optionCount = 0;
                    $optionType = 0;
                    foreach ($values["options"] as &$opt) {
                        if (!$isUpdation || !isset($opt['id']) || empty($opt['id'])) {
                            $opt['id'] = (int)$maxOptionId;

                            $maxOptionId++;
                        }
                        $opt['id'] = (int)$opt["id"];
                        if ($optionCount == 0) {               //for checking if all options type are same(image/normal)
                            if (!empty($opt['image_meta'])) {
                                $optionType = 1;            //1 for image type
                            }
                        } else {
                            if ($optionType && empty($opt['image_meta'])) {
                                $this->errors["form_json"] = "All options should be of same type ";
                            } else if (!$optionType && !empty($opt['image_meta'])) {
                                $this->errors["form_json"] = "All options should be of same type ";
                            }
                        }


                        $optionCount++;

                        $diffOptions = array_diff($optionNodeChecker, array_keys($opt));
                        if (!empty($diffOptions)) {
                            $this->errors["form_json"] = "Option Nodes Missing " . implode(",", $diffOptions);
                        }
                        if (isset($opt['is_correct']) && $opt["is_correct"]) {
                            $opt["is_correct"] = true;
                            $correctOptionChecker = true;
                        }
                        else{
                            $opt["is_correct"] = false;
                        }
                    }
                    if (!$correctOptionChecker) {
                        $this->errors["form_json"] = "Please tick atleast one correct option in question {$values["id"]} ";
                    }
                } else {
                    $this->errors["form_json"] = "Question Nodes Missing " . implode(",", $diff);
                }
            }
            // echo '<pre>'; print_r($decodeJson); echo '</pre>';


        } else {
            $this->errors["form_json"] = "Invalid Form Json";
        }
        if (!is_array($request->image_meta)) {
            $this->errors["image_meta"] = "The image meta must be an object.";
        }

        return $decodeJson;
    }

    protected function addQuizGraph($quiz)
    {
        $quizIds = Quiz::where('quiz_id', '=', $quiz->id)
            ->whereNull('deleted_at')
            ->pluck('profile_id')->toArray();
        if (count($quizIds) > 0) {
            $quiz->addToGraph();
            foreach ($quizIds as $profileId) {
                $quiz->addParticipationEdge($profileId);
            }
        }
    }

    public function closeQuizes($id, Request $request)
    {

        $quiz = \DB::table("quizes")->where("id", "=", trim($id))->first();

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if (isset($quiz->company_id) && !empty($quiz->company_id)) {
            $companyId = $quiz->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                $this->model = false;
                return $this->sendError("User does not belong to this company");
            }
        } else if (isset($quiz->profile_id) &&  $quiz->profile_id != $request->user()->profile->id) {
            $this->model = false;
            return $this->sendError("Only Admin can close the quiz");
        }

        if ($quiz->state == config("constant.QUIZ_STATES.CLOSED")) {
            $this->model = false;
            return $this->sendError("Quiz Already Closed");
        }


        $reasonId = $request->input('reason_id');

        $getReason = $this->quizCloseReason();
        $reasonList = $getReason->original["data"];

        $reasons = [];
        foreach ($reasonList as $reason) {
            $reasons[$reason["id"]] = $reason;
        }

        $this->model = false;
        if (isset($reasons[$reasonId])) {
            $reason = $reasons[$reasonId]["reason"];
            $description = $request->input('description');

            $data = ['quiz_id' => $quiz->id, 'reason' => $reason, 'other_reason' => $description];
        } else {
            return $this->sendError("Please select valid reason");
        }

        $close = Quiz::where("id", "=", $quiz->id);
        $quiz = $close->update(["state" => config("constant.QUIZ_STATES.CLOSED")]);
        $get = $close->first();
        PaymentDetails::where("model_id", $id)->update(["is_active" => 0]);
        $this->messages = "Quiz Close Failed";
        if ($quiz) {
            $this->model = \DB::table('quiz_close_reasons')->insert($data);;
            $this->messages = "Quiz Closed Successfully";
            $get->removeFromGraph(); // remove node and edge from neo4j
            event(new DeleteFeedable($get));
        }
        return $this->sendResponse();
    }

    public function quizCloseReason()
    {
        $data[] = ['id' => 1, 'reason' => 'Completed'];
        $data[] = ['id' => 2, 'reason' => 'Not enough responses'];
        $data[] = ['id' => 3, 'reason' => 'Other'];
        $this->model = $data;
        return $this->sendResponse();
    }

    public function submitQuiz($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_status' => 'required|numeric',
                'answer_json' => 'required|array'
            ]);

            if ($validator->fails()) {
                $this->model = ["status" => false];
                $this->errors = $validator->messages();
                return $this->sendResponse();
            }

            $quiz = $this->model->where("id", "=", $id)->first();

            $this->model = [];
            if (empty($quiz)) {
                $this->model = ["status" => false];
                return $this->sendError("Invalid Quiz");
            }
            if ($quiz->state == config("constant.QUIZ_STATES.CLOSED")) {
                $this->model = ["status" => false];
                return $this->sendError("Quiz is closed. Cannot submit answers");
            }

            if ($quiz->state == config("constant.QUIZ_STATES.EXPIRED")) {
                $this->model = ["status" => false];
                return $this->sendError("Quiz is expired. Cannot submit answers");
            }

            if (isset($quiz->profile_id) && $quiz->profile_id == $request->user()->profile->id) {
                $this->model = ["status" => false];
                return $this->sendError("Admin Cannot Fill the Quizes");
            }


            $checkApplicant = \DB::table("quiz_applicants")->where('quiz_id', $id)->where('profile_id', $request->user()->profile->id)->whereNull('deleted_at')->first();
            if (empty($checkApplicant)) {
                \DB::table("quiz_applicants")->insert(["quiz_id" => $id, "profile_id" => request()->user()->profile->id, "application_status" => 1]);
            }

            if (!empty($checkApplicant) && $checkApplicant->application_status == config("constant.QUIZ_APPLICANT_ANSWER_STATUS.COMPLETED")) {
                $this->model = ["status" => false];
                return $this->sendError("Already Answered");
            }


            $questions = (!is_array($request->answer_json) ? json_decode($request->answer_json, true) : $request->answer_json);
            $prepareQuestionJson = $this->prepQuestionJson($quiz->form_json);

            $mandateQuestions = [];
            $mandateQuestions =  array_map(function ($v) {
                if (isset($v["is_mandatory"]) && $v["is_mandatory"] == true) {
                    return  $v["id"];
                }
            }, $prepareQuestionJson);

            $answerQuestionIds = [];

            $answerQuestionIds =  array_map(function ($vi) {
                return  $vi["question_id"];
            }, $questions);

            $mandateQuestions = array_values(array_filter($mandateQuestions));


            if (!empty(array_diff($mandateQuestions, $answerQuestionIds))) {
                return $this->sendError("Mandatory Questions Cannot Be Blank");
            }

            DB::beginTransaction();
            $commit = true;
            foreach ($questions as $values) {

                if (!isset($values["options"]) || empty($values["options"])) {
                    DB::rollback();
                    $this->model = ["status" => false];
                    return $this->sendError("Options not found");
                }
                $answerArray = [];
                $answerArray["profile_id"] = $request->user()->profile->id;
                $answerArray["quiz_id"] = $id;
                $answerArray["question_id"] = $values["question_id"];
                $answerArray["current_status"] = $request->current_status;


                if (isset($values["options"]) && !empty($values["options"])) {

                    foreach ($values["options"] as $optVal) {

                        $answerArray["option_id"] = $optVal["id"];
                        $quizAnswer = QuizAnswers::create($answerArray);
                        if (!$quizAnswer) {
                            $commit = false;
                        }
                    }
                }
            }
            $user = $request->user()->profile;
            $responseData = [];

            if ($commit) {
                DB::commit();
                // $score = $this->calculateScore($id);
                $result = $this->quizResult($id, false);
                // return $score;
                // $score=(string) $score;
                $this->model = true;
                $responseData = ["status" => true];


                $this->messages = "Answer Submitted Successfully";

                $data = [];
                $data['helper'] = $result["helper"];
                $data['title'] = $result["title"];
                $data['subtitle'] = $result["subtitle"];
                $data['score_text'] = $result["score_text"];
                $data["correctAnswerCount"] = $result["correctAnswerCount"];
                $data["score"] = $result["score"];

                $checkApplicant = \DB::table("quiz_applicants")->where('quiz_id', $id)->where('profile_id', $request->user()->profile->id)->update(["score" => $result['score'], "application_status" => config("constant.QUIZ_APPLICANT_ANSWER_STATUS.COMPLETED"), "completion_date" => date("Y-m-d H:i:s")]);
                $user = $request->user()->profile->id;
                Redis::set("quizes:application_status:$request->quiz_id:profile:$user", config("constant.QUIZ_APPLICANT_ANSWER_STATUS.COMPLETED"));
            } else {
                $responseData = ["status" => false];
            }

            //NOTE: Check for all the details according to flow and create txn and push txn to queue for further process.
            if ($this->model == true && $request->current_status == config("constant.QUIZ_APPLICANT_ANSWER_STATUS.COMPLETED")) {
                $request->quiz_id = $id;
                $responseData = $this->paidProcessing($request);
                $quiz->addToGraph();
                $quiz->addParticipationEdge($request->user()->profile->id); //Add edge to neo4j
            }
            $responseData = array_merge($responseData, $data);
            return $this->sendResponse([$responseData]);
        } catch (Exception $ex) {
            DB::rollback();
            $this->model = ["status" => false];
            return $this->sendError("Error Saving Answers " . $ex->getMessage() . " " . $ex->getFile() . " " . $ex->getLine());
        }
    }


    public function paidProcessing(Request $request)
    {
        $responseData = $flag = [];
        $requestPaid = $request->is_paid ?? false;
        $responseData["status"] = true;

        $paymnetExist = PaymentDetails::where('model_id', $request->quiz_id)->where('is_active', 1)->first();
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
                $responseData["subTitle"] = "You have successfully completed the quiz.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/congratulation.png";
                $responseData["helper"] = "We appreciate your effort and have sent you a reward link to your registered email and phone number, redeem it and enjoy.";
            } else if ($flag["status"] == false && isset($flag["reason"]) && $flag["reason"] == "phone") {
                $responseData["get_paid"] = true;
                $responseData["title"] = "Congratulations!";
                $responseData["subTitle"] = "You have successfully completed the quiz.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/congratulation.png";
                $responseData["helper"] = "We appreciate your effort, but unfortunately you don't have your phone number updated in your profile. Please update phone number and contact us at payment@tagtaste.com to redeem the reward.";
            } else if ($flag["status"] == false && isset($flag["reason"]) && $flag["reason"] == "paid") {
                $responseData["get_paid"] = false;
                $responseData["title"] = "Uh Oh!";
                $responseData["subTitle"] = "You have successfully completed the quiz.";
                $responseData["icon"] = "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/Payment/Static/Submit-Review/congratulation.png";
                $responseData["helper"] = "We appreciate your effort but unfortunately you missed the reward. Please contact us at payment@tagtaste.com for any further help.";
            } else if ($flag["status"] == false && isset($flag["reason"]) && $flag["reason"] == "not_paid") {
                $responseData["is_paid"] = false;
            } else {
                $responseData["get_paid"] = false;
                $responseData["title"] = "Uh Oh!";
                $responseData["subTitle"] = "You have successfully completed the quiz.";
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


            $data = ["amount" => $amount, "model_type" => "quiz", "model_id" => $request->quiz_id, "payment_id" => $paymentDetails->id];

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
                \Log::info("Payment Returned False" . " " . json_encode($data));
            }
        } else {
            PaymentDetails::where('id', $paymentDetails->id)->update(['is_active' => 0]);
            if ($request->has("is_paid") && $request->is_paid == true) {
                return ["status" => false, "reason" => "paid"];
            }
        }

        return ["status" => false];
    }

    public function getMyQuiz(Request $request)
    {
        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $quizes = $this->model->whereNull("deleted_at");
        if ($request->has('state') && !empty($request->input('state'))) {
            $states = [$request->state];
            if ($request->state == config("constant.QUIZ_STATES.PUBLISHED")) {
                $states = [config("constant.QUIZ_STATES.PUBLISHED"), config("constant.QUIZ_STATES.CLOSED"), config("constant.QUIZ_STATES.EXPIRED")];
            }
            $quizes = $quizes->whereIn("state", $states);
        }

        $quizes = $quizes->orderBy('state', 'asc')->orderBy('created_at', 'desc');
        $profileId = $request->user()->profile->id;
        $title = isset($request->title) ? $request->title : null;

        $this->model = [];
        $data = [];

        //Get compnaies of the logged in user.
        $companyIds = \DB::table('company_users')->where('profile_id', $profileId)->pluck('company_id');

        $quizes = $quizes->where(function ($q) use ($profileId, $companyIds) {
            $q->orWhere('profile_id', "=", $profileId);
            $q->orWhereIn('company_id', $companyIds);
        });

        if (!is_null($title)) {
            $quizes = $quizes->where('title', 'like', '%' . $title . '%');
        }
        $this->model['count'] = $quizes->count();

        $quizes = $quizes->skip($skip)->take($take)
            ->get();
        foreach ($quizes as $quiz) {

            $quiz->image_meta = json_decode($quiz->image_meta);
            $quiz->video_meta = json_decode($quiz->video_meta);
            $quiz->form_json = json_decode($quiz->form_json);
            $data[] = [
                'quiz' => $quiz,
                'meta' => $quiz->getMetaFor($profileId)
            ];
        }
        $this->model['quizes'] = $data;
        return $this->sendResponse();
    }




    public function like(Request $request, $quizId)
    {
        $profileId = $request->user()->profile->id;
        $key = "meta:quiz:likes:" . $quizId;
        // return $key;
        $quizLike = Redis::sIsMember($key, $profileId);
        $this->model = [];

        if ($quizLike) {
            QuizLike::where('profile_id', $profileId)->where('quiz_id', $quizId)->delete();
            Redis::sRem($key, $profileId);
            $this->model['liked'] = false;
        } else {
            QuizLike::insert(['profile_id' => $profileId, 'quiz_id' => $quizId]);
            Redis::sAdd($key, $profileId);
            $this->model['liked'] = true;
            $recipe = Quiz::find($quizId);
            event(new Like($recipe, $request->user()->profile));
        }
        $this->model['likeCount'] = Redis::sCard($key);

        $peopleLike = new PeopleLike();
        $this->model['peopleLiked'] = $peopleLike->peopleLike($quizId, "quiz", request()->user()->profile->id);

        return $this->sendResponse();
    }



    public function reports($id, Request $request)
    {

        $checkIFExists = $this->model->where("id", "=", $id)->whereNull("deleted_at")->first();



        if (empty($checkIFExists)) {
            $this->model = false;
            return $this->sendError("Invalid Quiz");
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
                return $this->sendError("User does not belong to this company");
            }
        }
        else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            //($checkIFExists->profile_id);
            $this->model = false;
            return $this->sendError("Only Quiz Admin can view this report");
        }

        $applicants = QuizApplicants::where("quiz_id", "=", $id)->where("application_status", "=", config("constant.QUIZ_APPLICANT_ANSWER_STATUS.COMPLETED"))->where("deleted_at", "=", null);

        if ($request->has('filters') && !empty($request->filters)) {
            $applicants->whereIn('profile_id', $profileIds, 'and', $type);
        }

        $getCount = $applicants->get();

        $prepareNode = ["answer_count" => $getCount->count(), "reports" => []];

        $pluck = $getCount->pluck("profile_id")->toArray();

        $getJson = json_decode($checkIFExists["form_json"], true);
        $counter = 0;

        foreach ($getJson as $values) {

            $answers = QuizAnswers::where("quiz_id", "=", $id)->where("question_id", "=", $values["id"])->whereIn("profile_id", $pluck)->get();

            $ans = $answers->pluck("option_id")->toArray();
            $ar = array_values(array_filter($ans));
            $getAvg = (count($ar) ? $this->array_avg($ar, $getCount->count()) : 0);

            $prepareNode["reports"][$counter]["question_id"] = $values["id"];
            $prepareNode["reports"][$counter]["title"] = $values["title"];
            $prepareNode["reports"][$counter]["question_type"] = $values["question_type"];
            $prepareNode["reports"][$counter]["image_meta"] = (!is_array($values["image_meta"]) ?  json_decode($values["image_meta"], true) : $values["image_meta"]);

            $optCounter = 0;

            foreach ($values["options"] as $optVal) {
                $prepareNode["reports"][$counter]["options"][$optCounter]["id"] = $optVal["id"];
                $prepareNode["reports"][$counter]["options"][$optCounter]["value"] = $optVal["title"];
                $prepareNode["reports"][$counter]["options"][$optCounter]["image_meta"] = (!is_array($optVal["image_meta"]) ? json_decode($optVal["image_meta"], true) : $optVal["image_meta"]);
                $prepareNode["reports"][$counter]["options"][$optCounter]["answer_count"] = (isset($getAvg[$optVal["id"]]) ? $getAvg[$optVal["id"]]["count"] : 0);
                $prepareNode["reports"][$counter]["options"][$optCounter]["answer_percentage"] = (isset($getAvg[$optVal["id"]]) ? $getAvg[$optVal["id"]]["avg"] : 0);
                $prepareNode["reports"][$counter]["options"][$optCounter]["is_correct"] = isset($optVal["is_correct"])?$optVal["is_correct"]:false;

                $optCounter++;
            }

            uasort($prepareNode["reports"][$counter]["options"], function ($a, $b) {
                if (isset($a['answer_percentage']) && isset($b['answer_percentage'])) {
                    if ($a['answer_percentage'] == $b['answer_percentage']) {
                        return 0;
                    }
                    return ($a['answer_percentage'] < $b['answer_percentage']) ? 1 : -1;
                }
            });



            $prepareNode["reports"][$counter]["options"] = array_values($prepareNode["reports"][$counter]["options"]);
            $answers = [];
            $counter++;
        }


        $this->messages = "Report Successful";
        $this->model = $prepareNode;
        return $this->sendResponse();
    }


    public function inputAnswers($id, $question_id, $option_id, Request $request)
    {

        $checkIFExists = $this->model->where("id", "=", $id)->whereNull("deleted_at")->first();
        if (empty($checkIFExists)) {
            $this->model = false;
            return $this->sendError("Invalid Quiz");
        }

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                $this->model = false;
                return $this->sendError("User does not belong to this company");
            }
        }
        else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            $this->model = false;
            return $this->sendError("Only Quiz Admin can view this report");
        }

        if ($request->has('filters') && !empty($request->filters)) {
            $getFiteredProfileIds = $this->getProfileIdOfFilter($checkIFExists, $request);
            $profileIds = $getFiteredProfileIds['profile_id'];
            $type = $getFiteredProfileIds['type'];
        }



        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $answers = QuizAnswers::where("quiz_id", "=", $id)->where("question_id", "=", $question_id)->where("option_id", "=", $option_id)->whereNull("deleted_at")->orderBy('created_at', 'desc');

        $getJson = json_decode($checkIFExists["form_json"], true);
        $title = "";
        foreach ($getJson as $values) {
            if ($question_id == $values["id"]) {
                foreach ($values["options"] as $optVal) {
                    if ($option_id == $optVal["id"]) {
                        $title = $optVal["title"];
                        break;
                    }
                   
                }
            }
        }
        if ($request->has('filters') && !empty($request->filters)) {
            $answers->whereIn('profile_id', $profileIds, 'and', $type);
        }

        $this->model = [];
        $data = ["answer_count" => $answers->get()->count(), "report" => []];

        $this->model['count'] = $answers->count();
        $respondent = $answers->skip($skip)->take($take)
            ->get();
        foreach ($respondent as $profile) {
            $data["report"][] = ["profile" => $profile->profile, "answer" => $title];
        }
        $this->model = $data;
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

    public function calculateScore($id)
    {
        //calculation of final score of an applicant
        //  dd("helo");
        $correctAnswersCount = 0;
        $questions =  Quiz::where("id", $id)->first();
        $questions = json_decode($questions->form_json);
        $answerMapping = [];
        $answers = QuizAnswers::where("quiz_id", $id)->where('profile_id', request()->user()->profile->id)->whereNull('deleted_at')->get();

        $score = 0;
        // return $answers;
        $total = count($questions);

        if (count($answers)) {
            foreach ($questions as $value) {
                foreach ($value->options as $option) {
                    if (isset($option->is_correct) && $option->is_correct) {

                        $answerMapping[$value->id][] = $option->id;
                    }
                }
            }
            // return $answers;
            //    print_r($answerMapping);

            foreach ($questions as $question) {
                $answerArray = QuizAnswers::where("question_id", $question->id)->pluck("option_id")->toArray();
                if (!count(array_diff($answerArray, $answerMapping[$question->id]))) {
                    $correctAnswersCount++;
                    $score += 1;
                }
            }
            $score = ($score / $total) * 100;
            $result["score"] = $score;
            $result["correctAnswerCount"] = $correctAnswersCount;
        } else {
            $result["score"] = 0;
            $result["correctAnswerCount"] = 0;
        }
        $result["total"] = $total;

        return $result;
    }


    public function quizResult($id, $feed = true)
    {
        $data = [];

        $quiz = Quiz::where("id", "=", $id)->first();

        $this->model = [];
        if (empty($quiz)) {
            $this->model = ["status" => false];
            return $this->sendError("Invalid Quiz");
        }
        $applicant = QuizApplicants::where("quiz_id", $id)->where("profile_id", request()->user()->profile->id)->whereNull("deleted_at")
            ->first();
        if (empty($applicant)) {
            return $this->sendError("user has not attempted the quiz");
        }
        $result = $this->calculateScore($id);
        $data["helper"] = "Congrats";
        $data["title"] = "Quiz Completed Successfully";
        $data["subtitle"] = "You attempted {$result["total"]} questions and from that {$result["correctAnswerCount"]} answer is correct";
        $data["score_text"] = $result["score"] . "% Score";
        $data["correctAnswerCount"] = $result["correctAnswerCount"];
        $data["score"] = $result["score"];
        if ($feed) {
            return $this->sendResponse($data);
        }
        return $data;
    }

    public function getAnswers($id, $ques_id)
    {

        $quiz = Quiz::where("id", "=", $id)->first();
        $this->model = [];
        $data = [];
        if (empty($quiz)) {
            $this->model = ["status" => false];
            return $this->sendError("Invalid Quiz");
        }

        $questions =  json_decode($quiz->form_json);

        foreach ($questions as $question) {
            if ($ques_id == $question->id) {
                foreach ($question->options as $option) {
                    if (isset($option->is_correct) && $option->is_correct) {
                        $data["options"][] = (int)$option->id;
                    }
                }
            }
        }

        $this->messages = "Request Successful";
        return $this->sendResponse($data);
    }

    public function similarQuizes(Request $request, $quizId)
    {
        $quiz = $this->model->where('id', $quizId)->whereNull("deleted_at")->first();
        if ($quiz == null) {
            $this->model = false;
            return $this->sendError("Invalid Quiz Id");
        }

        $profileId = $request->user()->profile->id;
        $quizes = $this->model->where('state', 2)
            ->whereNull('deleted_at')->where("id", "<>", $quizId)
            ->inRandomOrder()
            ->take(3)->get();

        $this->model = [];
        foreach ($quizes as $quiz) {
            $meta = $quiz->getMetaFor($profileId);
            $quiz->image_meta = json_decode($quiz->image_meta);
            $quiz->video_meta = json_decode($quiz->video_meta);

            $this->model[] = ['quizes' => $quiz, 'meta' => $meta];
        }
        return $this->sendResponse();
    }

    public function userReport($id, $profile_id, Request $request)
    {

        $checkIFExists = $this->model->where("id", "=", $id)->whereNull("deleted_at")->first();
        if (empty($checkIFExists)) {
            $this->model = false;
            return $this->sendError("Invalid Quiz");
        }

        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                $this->model = false;
                return $this->sendError("User does not belong to this company");
            }
        } 
        else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            $this->model = false;
            return $this->sendError("Only Quiz Admin can view this report");
         }

        $applicants = QuizApplicants::where("quiz_id",$id)->whereNull("deleted_at")->orderBy("completion_date","desc")->get()->toArray();
        $posToValue =[];
        $valueToPos = [];
        
        foreach($applicants as $key =>$applicant){
        $posToValue[$key] = $applicant["profile_id"];
        $valueToPos[$applicant["profile_id"]]= $key;
        }

        $prepareNode = ["reports" => []];

        $getJson = json_decode($checkIFExists["form_json"], true);
        $counter = 0;
        $optionValues = [];

        foreach ($getJson as $values) {
            $answers = QuizAnswers::where("quiz_id", "=", $id)->where("question_id", "=", $values["id"])->where("profile_id", "=", $profile_id)->get();

            $pluckOpId = $answers->pluck("option_id")->toArray();

            $prepareNode["reports"][$counter]["question_id"] = $values["id"];
            $prepareNode["reports"][$counter]["title"] = $values["title"];
            $prepareNode["reports"][$counter]["question_type"] = $values["question_type"];
            $prepareNode["reports"][$counter]["image_meta"] = (!is_array($values["image_meta"]) ? json_decode($values["image_meta"]) : $values["image_meta"]);
            $prepareNode["reports"][$counter]["is_answered"] = false;

            if ($answers->count()) {
                $optCounter = 0;
                $answers = $answers->toArray();
                $prepareNode["reports"][$counter]["is_answered"] = true;

                if (isset($values["options"])) {

                    foreach ($values["options"] as $optVal) {
                        if (in_array($optVal["id"], $pluckOpId) || (isset($values["max"]) && in_array($optVal["id"], $optionValues))) {

                            $flip = array_flip($pluckOpId);

                            $pos = (isset($flip[$optVal["id"]]) ? $flip[$optVal["id"]] : false);
                            if ($pos === false) {
                                continue;
                            }

                            $prepareNode["reports"][$counter]["is_answered"] = (($answers[$pos]["option_id"] == null) ? false : true);


                            $prepareNode["reports"][$counter]["options"][$optCounter]["id"] = $optVal["id"];
                            $prepareNode["reports"][$counter]["options"][$optCounter]["value"] = $optVal["title"];
                            $prepareNode["reports"][$counter]["options"][$optCounter]["image_meta"] = (!is_array($optVal["image_meta"]) ? json_decode($optVal["image_meta"], true) : $optVal["image_meta"]);


                            $optCounter++;
                        } else {
                            $prepareNode["reports"][$counter]["is_answered"] = (($answers[0]["option_id"] == null) ? false : true);
                        }
                    }
                }
            }

            $answers = [];
            $counter++;
        }
       
        $prepareNode["previous"]= isset($applicants[($valueToPos[$profile_id]-1)])?Profile::find($posToValue[($valueToPos[$profile_id]-1)]):null;
        $prepareNode["next"] = isset($applicants[($valueToPos[$profile_id]+1)])?Profile::find($posToValue[($valueToPos[$profile_id]+1)]):null;
        $this->messages = "Report Successful";
        $this->model = $prepareNode;
        return $this->sendResponse();
    }
    

    public function quizRespondents($id, Request $request)
    {
        $checkIFExists = $this->model->where("id", "=", $id)->whereNull("deleted_at")->first();
        if (empty($checkIFExists)) {
            $this->model = false;
            return $this->sendError("Invalid Quiz");
        }


        //NOTE : Verify copmany admin. Token user is really admin of company_id comning from frontend.
        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                $this->model = false;
                return $this->sendError("User does not belong to this company");
            }
        } 
        else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            $this->model = false;
            return $this->sendError("Only Quiz Admin can view this report");
        }

        if ($request->has('filters') && !empty($request->filters)) {
            $getFiteredProfileIds = $this->getProfileIdOfFilter($checkIFExists, $request);
            $profileIds = $getFiteredProfileIds['profile_id'];
            $type = $getFiteredProfileIds['type'];
        }


        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $count = QuizApplicants::where("quiz_id", "=", $id)->where("deleted_at", "=", null)->where("application_status", "=", config("constant.QUIZ_APPLICANT_ANSWER_STATUS.COMPLETED"))->orderBy('completion_date', 'desc');

        if ($request->has('filters') && !empty($request->filters)) {
            $count->whereIn('profile_id', $profileIds, 'and', $type);
        }

        $this->model = [];
        $countInt = $count->count();
        $data = ["answer_count" => $countInt];

        $respondent = $count->skip($skip)->take($take)
            ->get();

        foreach ($respondent as $profile) {
            $data['report'][] = $profile->profile;
        }

        $this->model = $data;
        return $this->sendResponse();
    }


    public function getFilters($quizId, Request $request)
    {
        return $this->getFilterParameters($quizId, $request);
    }

    public function excelReport($id, Request $request)
    {
        $checkIFExists = $this->model->where("id", "=", $id)->whereNull("deleted_at")->first();

        if (empty($checkIFExists)) {
            $this->model = false;
            return $this->sendError("Invalid Quiz");
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
                return $this->sendError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            // return $this->sendError("Only Survey Admin can view this report");
        }

        $headers = [];
        $getJson = json_decode($checkIFExists["form_json"], true);
        $questionIdMapping = [];
        $optionIdMapping =[];
        foreach ($getJson as $values) {

            $questionIdMapping[$values["id"]] = html_entity_decode($values["title"]);
            foreach($values["options"] as $option){
                $optionIdMapping[$values["id"]][$option["id"]] = html_entity_decode($option["title"]);
  
            }

         
        }
        // dd($questionIdMapping);
        $applicants = QuizApplicants::where("quiz_id", "=", $id)->where("application_status", "=", config("constant.QUIZ_APPLICANT_ANSWER_STATUS.COMPLETED"))->where("deleted_at", "=", null);

        if ($request->has('filters') && !empty($request->filters)) {
            $applicants->whereIn('profile_id', $profileIds, 'and', $type);
        }

        $getCount = $applicants->get();


        $pluck = $getCount->pluck("profile_id")->toArray();

        $getQuizAnswers = QuizAnswers::where("quiz_id", "=", $id);

        if ($request->has("profile_ids") && !empty($request->input("profile_ids"))) {
            $getQuizAnswers = $getQuizAnswers->whereIn("profile_id", $request->profile_ids);
        } else if ($request->has('filters') && !empty($request->filters)) {
            $getQuizAnswers = $getQuizAnswers->whereIn("profile_id", $pluck);
        }

        $getQuizAnswers = $getQuizAnswers->get();
        $counter = 0;
        foreach ($getQuizAnswers as $answers) {
            if (!isset($headers[$answers->profile_id])) {
                $counter++;
                $headers[$answers->profile_id] =  ["Sr no" => $counter, "Name" => null, "Email" => null, "Age" => null, "Phone" => null, "City" => null, "Hometown" => null, "Profile Url" => null, "Timestamp" => null];
                foreach ($questionIdMapping as $key => $value) {

                        $headers[$answers->profile_id][$value . "_(" . $key . ")_"] = null;
                    
                }
            }
            $image = (!is_array($answers->image_meta) ? json_decode($answers->image_meta, true) : $answers->image_meta);
            if (isset($questionIdMapping[$answers->question_id])) {
                // if (!isset($headers[$answers->profile_id])) {
                //     ;
                // }
                // $headers[$answers->profile_id]["Sr no"] = $counter;
                $headers[$answers->profile_id]["Name"] = html_entity_decode($answers->profile->name);
                $headers[$answers->profile_id]["Email"] = html_entity_decode($answers->profile->email);
                $headers[$answers->profile_id]["Age"] = floor((time() - strtotime($answers->profile->dob)) / 31556926);
                $headers[$answers->profile_id]["Phone"] = \DB::Table("profiles")->where("id", "=", $answers->profile->id)->first()->phone;
                $headers[$answers->profile_id]["City"] = html_entity_decode($answers->profile->city);
                $headers[$answers->profile_id]["Hometown"] = html_entity_decode($answers->profile->hometown);
                $headers[$answers->profile_id]["Profile Url"] = env('APP_URL') . "/@" . html_entity_decode($answers->profile->handle);
                $headers[$answers->profile_id]["Timestamp"] = date("Y-m-d H:i:s", strtotime($answers->created_at)) . " GMT +5.30";

                $ans = "";


                    if (isset($headers[$answers->profile_id][$questionIdMapping[$answers->question_id] . "_(" . $answers->question_id . ")_"]) && !empty($headers[$answers->profile_id][$questionIdMapping[$answers->question_id] . "_(" . $answers->question_id . ")_"])) {
                        $ans .= $headers[$answers->profile_id][$questionIdMapping[$answers->question_id] . "_(" . $answers->question_id . ")_"] . ";";
                    }
                    $ans .= html_entity_decode($optionIdMapping[$answers->question_id][$answers->option_id]);

                $p = false;
                if (!empty($image) && is_array($image)) {
                    if (!empty(array_column($image, "original_photo"))) {
                        $ans .= ";";
                    }
                    $ans .= implode(";", array_column($image, "original_photo"));
                    $p = true;
                }

             

                if (!empty($url) && is_array($url)) {
                    if ($p && !empty(array_column($url, "url"))) {
                        $ans .= ";";
                    }
                    $ans .=   implode(";", array_column($url, "url"));
                }
                 
                    $headers[$answers->profile_id][$questionIdMapping[$answers->question_id] . "_(" . $answers->question_id . ")_"] = $ans;
                
            }
        }

        $finalData = array_values($headers);

        $relativePath = "reports/quizesAnsweredExcel";
        $name = "quizes-" . $id . "-" . uniqid();

        $excel = Excel::create($name, function ($excel) use ($name, $finalData) {
            // Set the title
            $excel->setTitle($name);

            // Chain the setters
            $excel->setCreator('Tagtaste')
                ->setCompany('Tagtaste');

            // Call them separately
            $excel->setDescription('Quiz Response List');

            $excel->sheet('Sheetname', function ($sheet) use ($finalData) {
                $sheet->fromArray($finalData, null, 'A1', true, true);
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
}
