<?php

namespace App\Http\Controllers\Api\Survey;

use App\Company;
use App\Deeplink;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Recipe\Profile;
use App\SurveyAnswers;
use App\surveyApplicants;
use App\SurveyAttemptMapping;
use App\Surveys;
use App\SurveysEntryMapping;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel;
use SurveyApplicants as GlobalSurveyApplicants;
use Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helper;
use Illuminate\Support\Facades\DB;


class SurveyApplicantController extends Controller
{

    use SendsJsonResponse, FilterTraits;

    private $frontEndApplicationStatus = [0 => "Begin Tasting", 1 => "Notified", 2 => "Completed", 3 => "In Progress"];
    public function __construct(Surveys $model)
    {
        $this->model = $model;
    }

    public function index($id, Request $request)
    {
        $checkIFExists = $this->model->where("id", "=", $id)->whereNull("deleted_at")->first();
        if (empty($checkIFExists)) {
            $this->model = false;
            return $this->sendError("Invalid Survey");
        }

        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            return $this->sendError("Only Admin can view applicant list");
        }

        $version_num = '';
        if($request->is('*/v1/*')){
            $version_num = 'v1';
        }

        //paginate
        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $this->model = [];
        //filters data
        $q = $request->input('q');
        $profileIds = [];
        if ($request->has('filters') && !empty($request->filters)) {
            $getFiteredProfileIds = $this->getProfileIdOfFilter($checkIFExists, $request, $version_num);
            $profileIds = $getFiteredProfileIds['profile_id'];
        }

        $applicants = surveyApplicants::where("survey_id", "=", $id)->whereNull("deleted_at")->whereNull("rejected_at");
        if ($q != null) {
            $searchByProfile = surveyApplicants::where('survey_id', $id)
                ->whereNUll('company_id')
                ->join('profiles', 'survey_applicants.profile_id', '=', 'profiles.id')
                ->join('users', 'profiles.user_id', '=', 'users.id')
                ->where('users.name', 'LIKE', '%' . $q . '%')
                ->pluck('survey_applicants.id');

            $searchByCompany = surveyApplicants::where('survey_id', $id)
                ->leftJoin('companies', 'survey_applicants.company_id', '=', 'companies.id')
                ->where('companies.name', 'LIKE', $q . '%')
                ->pluck('survey_applicants.id');

            $searchByProfile->merge($searchByCompany);
            $applicants = $applicants->whereIn('id', $searchByProfile);
        }

        if ($request->sortBy != null) {
            $applicants = $this->sortApplicants($request->sortBy, $applicants, $id);
        }

        if (!empty($profileIds)) {
            $applicants = $applicants
                ->whereIn('profile_id', $profileIds);
        }

        $applicants = $applicants->orderBy("created_at", "desc")->skip($skip)->take($take)->get()->toArray();


        $profileIdsForCounts = (($request->has('filters') && !empty($request->filters)) ? array_column($applicants, 'profile_id') : SurveyApplicants::where("survey_id", "=", $id)->whereNull("deleted_at")->get()->pluck("profile_id"));
        //count of sensory trained
        $countSensory = \DB::table('profiles')->where('is_sensory_trained', "=", 1)
            ->whereIn('profiles.id', $profileIdsForCounts)
            ->get();


        //count of experts
        $countExpert = \DB::table('profiles')
            ->select('id')
            ->where('is_expert', 1)
            ->whereIn('id', $profileIdsForCounts)
            ->get();

        //count of super tasters
        $countSuperTaste = \DB::table('profiles')
            ->select('id')
            ->where('is_tasting_expert', 1)
            ->whereIn('id', $profileIdsForCounts)
            ->get();
        $this->model['applicants'] = $applicants;
        $this->model['totalApplicants'] = surveyApplicants::where('survey_id', $id)
            ->whereNull('deleted_at')->count();

        $this->model['invitedApplicantsCount'] = surveyApplicants::where('survey_id', $id)->where('is_invited', 1)->whereNull("deleted_at")->count();
        $this->model['rejectedApplicantsCount'] = surveyApplicants::where('survey_id', $id)->whereNotNull('rejected_at')->whereNull("deleted_at")->count();

        $this->model["overview"][] = ['title' => "Sensory Trained", "count" => $countSensory->count()];
        $this->model["overview"][] = ['title' => "Experts", "count" => $countExpert->count()];
        $this->model["overview"][] = ['title' => "Super Taster", "count" => $countSuperTaste->count()];

        return $this->sendResponse();
    }

    public function showInterest($id, Request $request)
    {

        $checkIFExists = $this->model->where("id", "=", $id)->whereNull("deleted_at")->first();
        if (empty($checkIFExists)) {
            $this->model = false;
            return $this->sendError("Invalid Survey");
        }


        if ($checkIFExists->state == config("constant.SURVEY_STATES.CLOSED")) {
            $this->model = ["status" => false];
            return $this->sendError("Survey is closed. Cannot show interest");
        }

        if ($checkIFExists->state == config("constant.SURVEY_STATES.EXPIRED")) {
            $this->model = ["status" => false];
            return $this->sendError("Survey is expired. Cannot show interest");
        }

        if ($checkIFExists->is_private != config("constant.SURVEY_PRIVATE")) {
            return $this->sendError("Cannot show interest on public surveys");
        }

        $checkIfAlreadyInterested = surveyApplicants::where("profile_id", $request->user()->profile->id)->where("survey_id", $id)->whereNull('deleted_at')->first();

        if (!empty($checkIfAlreadyInterested)) {
            return $this->sendError("Already Shown Interest");
        }

        if ($checkIFExists->profile_id == $request->user()->profile->id) {
            return $this->sendError("Admins Cannot Show Interest");
        }

        $profile = Profile::where('id', $request->user()->profile->id)->first();

        $dob = isset($profile->dob) ? date("Y-m-d", strtotime($profile->dob)) : null;

        $data = [
            'profile_id' => $request->user()->profile->id, 'survey_id' => $id,
            'message' => ($request->message ?? null),
            'age_group' => $profile->ageRange ?? null, 'gender' => $profile->gender ?? null, 'hometown' => $profile->hometown ?? null, 'current_city' => $profile->city ?? null, "application_status" => (int)config("constant.SURVEY_APPLICANT_ANSWER_STATUS.TO_BE_NOTIFIED"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s"), "dob" => $dob, "generation" => Helper::getGeneration($profile->dob)
        ];

        $create = surveyApplicants::create($data);

        if (isset($create->id)) {
            $profile = $request->user()->profile->id;
            // Redis::sAdd("surveys:$id:profile:$request->user()->profile_id:", $batch->id);
            Redis::set("surveys:application_status:$id:profile:$profile", 0);
            $this->model = true;
            $this->messages = "Thanks for showing interest. We will notify you when admin accept your request for survey.";

            event(new \App\Events\Actions\surveyApplicantEvents(
                $checkIFExists,
                $request->user()->profile,
                null,
                null,
                'survey_manage',
                null,
                [
                    "survey_url" => Deeplink::getShortLink("surveys", $checkIFExists->id),
                    "survey_name" => $checkIFExists->title,
                    "survey_id" => $checkIFExists->id,
                    "profile" => (object)[
                        "id" => $request->user()->profile->id,
                        "name" => $request->user()->profile->name,
                        "image" => $request->user()->profile->image
                    ],
                    "is_private" => $checkIFExists->is_private, "type" => "showInterest", "comment" => $request->message ?? null
                ]
            ));
        } else {
            $this->model = false;
            $this->errors[] = "Failed to show interest in survey";
        }
        return $this->sendResponse();
    }

    public function beginSurvey($id, Request $request)
    {
        $checkIFExists = $this->model->where("id", "=", $id)->whereNull("deleted_at")->where(function ($q) {
            $q->orWhere('state', "!=", config("constant.SURVEY_STATES.CLOSED"));
            $q->orWhere("state", "!=", config("constant.SURVEY_STATES.EXPIRED"));
        })->first();

        if (empty($checkIFExists)) {
            return $this->sendError("You cannot perform this action on this survey anymore.");
        }


        if ($checkIFExists->is_private != config("constant.SURVEY_PRIVATE")) {
            return $this->sendError("Cannot begin for public surveys");
        }

        $bgnAll = false;
        $profileIds = $request->input('profile_id');
        if ($request->has("begin_all")) {
            if ($request->input("begin_all") == 1) {
                $profileIds = surveyApplicants::where('survey_id', $id)->get()->pluck('profile_id');
                $bgnAll = true;
            }
        }
        $this->model = true;
        foreach ($profileIds as $profileId) {

            if ($bgnAll == false) {
                $checkIFInvited = surveyApplicants::where("survey_id", "=", $id)->where("application_status", "=", config("constant.SURVEY_APPLICANT_ANSWER_STATUS.TO_BE_NOTIFIED"))->where("profile_id", $profileId)->first();

                if (empty($checkIFInvited)) {
                    $this->model = false;
                    return $this->sendError("Cannot begin survey for specified user");
                }
            }

            $currentStatus = Redis::get("surveys:application_status:$id:profile:$profileId");
            if ($currentStatus == 0) {
                $b = surveyApplicants::where("profile_id", $profileId)->where('survey_id', $id)->where('application_status', 0)->update(["application_status" => 1]);
                if ($b == false) {
                    $this->model = false;
                } else {
                    Redis::set("surveys:application_status:$id:profile:$profileId", 1);
                    $who = Profile::where("id", $profileId)->first();
                    $checkIFExists->profile_id = $profileId;
                    event(new \App\Events\Actions\surveyApplicantEvents(
                        $checkIFExists,
                        $who,
                        null,
                        null,
                        'fill_survey',
                        null,
                        ["survey_url" => Deeplink::getShortLink("surveys", $checkIFExists->id), "survey_name" => $checkIFExists->title, "survey_id" => $checkIFExists->id, "profile" => (object)["id" => $who->id, "name" => $who->name, "image" => $who->image], "is_private" => $checkIFExists->is_private, "type" => "beginSurvey"]
                    ));
                }
            }
        }
        return $this->sendResponse();
    }

    public function startSurvey($id, Request $request){

    try{
        $survey = $this->model->where("id", "=", $id)->first();
        $this->model = [];
        if (empty($survey)) {
            $this->model = false;
            return $this->sendNewError("Invalid Survey");
        }
        if ($survey->state == config("constant.SURVEY_STATES.CLOSED")) {
            $this->model = false;
            return $this->sendNewError("Survey is closed. Cannot submit answers");
        }

        if ($survey->state == config("constant.SURVEY_STATES.EXPIRED")) {
            $this->model = false;
            return $this->sendNewError("Survey is expired. Cannot submit answers");
        }

        if ($survey->state == config("constant.SURVEY_STATES.DRAFT")) {
            $this->model = false;
            return $this->sendNewError("Survey is in draft. Cannot submit answers");
        }

        if (isset($survey->profile_id) && $survey->profile_id == $request->user()->profile->id) {
            $this->model = false;
            return $this->sendNewError("Admin Cannot Fill the Surveys");
        }
        
        $checkApplicant = \DB::table("survey_applicants")->where('survey_id', $id)->where('profile_id', $request->user()->profile->id)->whereNull('deleted_at')->first();

        DB::beginTransaction();
        if (empty($checkApplicant) && (is_null($survey->is_private) || !$survey->is_private)) {
            $this->saveApplicants($survey, $request);
        } elseif (empty($checkApplicant)) {
            $this->status = false;
            return $this->sendNewError($survey->profile->user->name . " accepted your survey participation request by mistake and it has been reversed.");
        }
        
        if (!($survey->multi_submission) && !empty($checkApplicant) && $checkApplicant->application_status == config("constant.SURVEY_APPLICANT_ANSWER_STATUS.COMPLETED")) {
            $this->model = false;
            return $this->sendNewError("Already Answered");
        }
        
        if (
            !empty($checkApplicant) && $checkApplicant->application_status == config("constant.SURVEY_APPLICANT_ANSWER_STATUS.TO_BE_NOTIFIED")

        ) {

            $this->model = false;
            return $this->sendNewError($survey->profile->user->name . " accepted your survey participation request by mistake and it has been reversed.");
        }
        
        $last_attempt = SurveyAttemptMapping::where("survey_id", $id)->where("profile_id", $request->user()->profile->id)
        ->orderBy("updated_at", "desc")->whereNull("deleted_at")->first();
        
        $answerAttempt = [];
        $answerAttempt["profile_id"] = $request->user()->profile->id;
        $answerAttempt["survey_id"] = $id;

       
        
        if (empty($last_attempt)) {   //WHEN ITS FIRST ATTEMPT
            $attempt_number = 1;
            $answerAttempt["attempt"] = $attempt_number;
            $attemptEntry = SurveyAttemptMapping::create($answerAttempt);  //entry on first hit
            SurveysEntryMapping::create(["surveys_attempt_id"=>$attemptEntry->id,"activity"=>config("constant.SURVEY_ACTIVITY.START")]);
            $this->model = true;
        } else {    //when its not first attempt
            $attempt_number = $last_attempt->attempt;
            if ($survey->multi_submission && $checkApplicant->application_status == config("constant.SURVEY_APPLICANT_ANSWER_STATUS.COMPLETED")) {
                $attempt_number += 1;
                $answerAttempt["attempt"] = $attempt_number;
                $attemptEntry = SurveyAttemptMapping::create($answerAttempt);    //when new attempt of same user first entry
                SurveysEntryMapping::create(["surveys_attempt_id"=>$attemptEntry->id,"activity"=>config("constant.SURVEY_ACTIVITY.START")]);
                $this->model = true;    
            }else{
                SurveysEntryMapping::create(["surveys_attempt_id"=>$last_attempt->id,"activity"=>config("constant.SURVEY_ACTIVITY.START")]);
                $this->model = true;
            }
        }
        
         //update applicant to inprogress
        $checkApplicant = \DB::table("survey_applicants")->where('survey_id', $id)->where('profile_id', $request->user()->profile->id)->update(["application_status" => config("constant.SURVEY_APPLICANT_ANSWER_STATUS.INPROGRESS"), "completion_date" => null]);
        $user = $request->user()->profile->id;
        Redis::set("surveys:application_status:$id:profile:$user", config("constant.SURVEY_APPLICANT_ANSWER_STATUS.INPROGRESS"));
        DB::commit();
        return $this->sendNewResponse();
    } catch (Exception $ex) {
        DB::rollback();
        $this->model = false;
        return $this->sendNewError("Error saving data " . $ex->getMessage() . " " . $ex->getFile() . " " . $ex->getLine());
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
        $dob = isset($profile->dob) ? date("Y-m-d", strtotime($profile->dob)) : null;

        if (empty($checkApplicant)) {
            $inputs = [
                'is_invited' => $isInvited, 'profile_id' => $loggedInprofileId, 'survey_id' => $id->id,
                'message' => $request->input('message'), 'address' => $applierAddress,
                'city' => $city, 'age_group' => $this->calcDobRange(date("Y", strtotime($profile->dob))), 'gender' => $profile->gender, 'hometown' => $profile->hometown, 'current_city' => $profile->city, "completion_date" => null, "created_at" => date("Y-m-d H:i:s"), "dob" => $dob, "generation" => Helper::getGeneration($profile->dob)
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
                $update['generation'] = Helper::getGeneration($profile->dob);
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

    public function userList($id, Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $query = $request->input('q');

        $alreadyInApplicantsList = surveyApplicants::select('survey_applicants.profile_id')
            ->where('survey_applicants.profile_id', '!=', $loggedInProfileId)->whereNull('survey_applicants.deleted_at')->where('survey_applicants.survey_id', $id)->get()->toArray();
        $alreadyInApplicantsList = array_column($alreadyInApplicantsList, 'profile_id');
        $this->model = \App\Recipe\Profile::select('profiles.*')->join('users', 'profiles.user_id', '=', 'users.id')
            ->where('profiles.id', '!=', $loggedInProfileId)->whereNotIn('profiles.id', $alreadyInApplicantsList)->where('users.name', 'like', "%$query%")->take(15)->get();

        return $this->sendResponse();
    }

    public function inviteForReview($id, Request $request)
    {
        $survey = $this->model->where('id', $id)->whereNull('deleted_at')->where(function ($q) {
            $q->orWhere('state', "!=", config("constant.SURVEY_STATES.CLOSED"));
            $q->orWhere("state", "!=", config("constant.SURVEY_STATES.EXPIRED"));
        })->first();

        if (empty($survey)) {
            return $this->sendError("You cannot perform this action on this survey anymore.");
        }


        if ($survey->is_private != config("constant.SURVEY_PRIVATE")) {
            return $this->sendError("Cannot invite for public surveys");
        }

        if (isset($survey->company_id) && !empty($survey->company_id)) {
            $companyId = $survey->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendError("User does not belong to this company");
            }
        } else if (isset($survey->profile_id) &&  $survey->profile_id != $request->user()->profile->id) {
            return $this->sendError("Only Admin can invite in this survey");
        }

        $profileId = $request->user()->profile->id;

        $profileIds = $request->input('profile_id');

        $checkExist = surveyApplicants::whereIn('profile_id', $profileIds)->where('survey_id', $id)->whereNull('deleted_at')->exists();
        if ($checkExist) {
            return $this->sendError("Already Invited");
        }
        // $company = Company::where('id', $survey->company_id)->first();
        $now = date("Y-m-d H:i:s");
        foreach ($profileIds as $profileId) {
            $survey->profile_id = $profileId;
            $profile = Profile::where("id", $profileId)->first();

            $inputs = ['profile_id' => $profileId, 'survey_id' => $id, 'is_invited' => 1, 'created_at' => $now, 'updated_at' => $now, "application_status" => config("constant.SURVEY_APPLICANT_ANSWER_STATUS.INCOMPLETE"), 'age_group' => $profile->ageRange ?? null, 'gender' => $profile->gender ?? null, 'hometown' => $profile->hometown ?? null, 'current_city' => $profile->city ?? null];
            $c = surveyApplicants::create($inputs);
            if (isset($c->id)) {
                Redis::set("surveys:application_status:$id:profile:$profileId", 1);
                $who = Profile::where("id", $profileId)->first();
                $comp = $survey->profile;

                if (!empty($survey->company_id)) {

                    $comp =  Company::find($survey->company_id);
                }
                $survey->profile_id = $profileId;
                event(new \App\Events\Actions\surveyApplicantEvents(
                    $survey,
                    null,
                    null,
                    null,
                    'fill_survey',
                    $comp,
                    ["survey_url" => Deeplink::getShortLink("surveys", $survey->id), "survey_name" => $survey->title, "survey_id" => $survey->id, "profile" => (object)["id" => $comp->id, "name" => $comp->name, "image" => isset($comp->image) ? $comp->image : $comp->logo], "is_private" => $survey->is_private, "type" => "inviteForReview"]
                ));
            }
        }

        $this->model = surveyApplicants::whereIn('profile_id', $profileIds)->where('survey_id', $id)->get();
        return $this->sendResponse();
    }

    public function applicantFilters($id, Request $request)
    {
        $version_num = '';
        if($request->is('*/v1/*')){
            $version_num = 'v1';
        }

        $gender = ['Male', 'Female', 'Other'];
        $age = Helper::getGenerationFilter('string');
        // $age = ['< 18', '18 - 35', '35 - 55', '55 - 70', '> 70'];

        $userType = ['Expert', 'Consumer'];
        $sensoryTrained = ["Yes", "No"];
        $superTaster = ["SuperTaster", "Normal"];
        $surveyApplicants = surveyApplicants::where('survey_id', $id)
        ->whereNull('survey_applicants.deleted_at');
        $applicationStatus = [
            'To Be Notified',
            'Notified',
            'Completed',
            'In Progress'
        ];
        $city = [];
        $profile = [];
        $hometown = [];
        $current_city = [];
        $applicants = $surveyApplicants->get();
        $applicantProfileIds = $applicants->pluck('profile_id');

        foreach ($applicants as $applicant) {
            if (isset($applicant->city)) {
                if (!in_array($applicant->city, $city))
                    $city[] = $applicant->city;
            }
        }

        if (isset($version_num) && $version_num == 'v1')
        {
            $surveyData = Surveys::where("id", "=", $id)->first();
            $filters = $request->input('filters');
            $isFilterable = isset($filters) && !empty($filters) ? true : false;
            $filteredProfileIds = $this->getProfileIdOfFilter($surveyData, $request, $version_num)['profile_id'];

            $profileIds = isset($filters) && !empty($filters) ? $filteredProfileIds : $applicantProfileIds;

            $profileModel = Profile::whereNull('deleted_at');
           
            $ageCounts = $this->getCount($surveyApplicants, 'generation', $filteredProfileIds, $isFilterable);
            $genderCounts = $this->getCount($surveyApplicants,'gender', $filteredProfileIds, $isFilterable);

            foreach($gender as $key => $gen)
            {  
                $inner_arr['key'] = $gen;
                $inner_arr['value'] = $gen;
                $inner_arr['count'] = isset($genderCounts[$gen]) ? $genderCounts[$gen] : 0;
                $gender[$key] = $inner_arr;
            }

            foreach($age as $key => $val)
            {  
                $inner_arr['key'] = $val;
                $inner_arr['value'] = $val;
                $inner_arr['count'] = isset($ageCounts[$val]) ? $ageCounts[$val] : 0;
                $age[$key] = $inner_arr;
            }
            
            //count of experts
            $userTypeCounts = $this->getCount($profileModel,'is_expert', $profileIds, true);

            $userType = [['key' => 'Expert', 'value' => 'Expert', 'count' => isset($userTypeCounts[1]) ? $userTypeCounts[1] : 0], ['key' => 'Consumer', 'value' => 'Consumer', 'count' => isset($userTypeCounts[0]) ? $userTypeCounts[0] : 0]];

            // sensory trained or not
            $sensoryTrainedCounts = $this->getCount($profileModel,'is_sensory_trained', $profileIds, 'true');

            $sensoryTrained = [['key' => 'Yes', 'value' => 'Yes', 'count' => isset($sensoryTrainedCounts[1]) ? $sensoryTrainedCounts[1] : 0], ['key' => 'No', 'value' => 'No', 'count' => isset($sensoryTrainedCounts[0]) ? $sensoryTrainedCounts[0] : 0]];

            // supar taster or not
            $superTasterCounts = $this->getCount($profileModel,'is_tasting_expert', $profileIds, 'true');
            
            $superTaster = [['key' => 'SuperTaster', 'value' => 'SuperTaster', 'count' => isset($superTasterCounts[1]) ? $superTasterCounts[1] : 0], ['key' => 'Normal', 'value' => 'Normal', 'count' => isset($superTasterCounts[0]) ? $superTasterCounts[0] : 0]];

            // application status
            $statusCounts = $this->getCount($surveyApplicants, 'application_status', $filteredProfileIds, $isFilterable);

            foreach($applicationStatus as $key => $val)
            {  
                $inner_arr['key'] = $val;
                $inner_arr['value'] = $val;
                $val = config("constant.SURVEY_APPLICANT_STATUS." . ucwords($val));
                $inner_arr['count'] = isset($statusCounts[$val]) ? $statusCounts[$val] : 0;
                $applicationStatus[$key] = $inner_arr;
            }
        }

       // profile specializations
       $specializations = \DB::table('profiles')
            ->leftJoin('profile_specializations', 'profiles.id', '=', 'profile_specializations.profile_id')
            ->leftJoin('specializations', 'specializations.id', '=', 'profile_specializations.specialization_id');

        $query = clone $specializations;
        $specializationNames = $query->whereIn('profiles.id', $applicantProfileIds)->groupBy('name')->pluck('name');

        if (isset($version_num) && $version_num == 'v1')
        {
            $specializationsCount = $specializations->select('name', \DB::raw('COUNT(*) as count'))->whereIn('profiles.id', $profileIds)->groupBy('name')->pluck('count','name');
        }

        foreach ($specializationNames as $key => $specialization) {
            if (isset($version_num) && $version_num == 'v1'){
                $profile[$key]['key'] = $specialization;
                $profile[$key]['value'] = $specialization;
                $profile[$key]['count'] = isset($specializationsCount[$specialization]) ? $specializationsCount[$specialization] : 0;
            }
            else
            {
                if (!in_array($specialization, $profile) && $specialization != null){
                    $profile[] = $specialization;
                }   
            }
        }

        $profile = array_filter($profile);
        $data = [];
        // $filters = $request->input('filter');
        // if (isset($filters) && count($filters)) {
        //     foreach ($filters as $filter) {
        //         if ($filter == 'gender')
        //             $data['gender'] = $gender;
        //         if ($filter == 'age')
        //             $data['age'] = $age;
        //         if ($filter == 'city')
        //             $data['city'] = $city;
        //         if ($filter == 'profile')
        //             $data['profile'] = $profile;
        //         if ($filter == 'super_taster')
        //             $data['super_taster'] = $superTaster;
        //         if ($filter == 'user_type')
        //             $data['user_type'] = $userType;
        //         if ($filter == 'sensory_trained')
        //             $data['sensory_trained'] = $sensoryTrained;
        //         if ($filter == 'application_status')
        //             $data['application_status'] = $applicationStatus;
        //     }
        // } else {
            $data = ['gender' => $gender, 'age' => $age, 'city' => $city,  'profile' => $profile, "sensory_trained" => $sensoryTrained, "user_type" => $userType, "super_taster" => $superTaster, "application_status" => $applicationStatus];
        // }
        $this->model = $data;
        return $this->sendResponse();
    }


    public function export($id, Request $request)
    {
        $survey = $this->model->where('id', $id)->whereNull('deleted_at')->first();

        if ($survey === null) {
            $this->model = false;
            return $this->sendError("Invalid Survey");
        }
        $profileId = $request->user()->profile->id;

        if (isset($survey->company_id) && !empty($survey->company_id)) {
            $companyId = $survey->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendError("User does not belong to this company");
            }
        } else if (isset($survey->profile_id) &&  $survey->profile_id != $request->user()->profile->id) {
            // return $this->sendError("Only Admin can download report of this survey");
        }

        //filters data
        $profileIds = null;
        if ($request->has('filters') && !empty($request->filters)) {
            $getFiteredProfileIds = $this->getProfileIdOfFilter($survey, $request);
            $profileIds = $getFiteredProfileIds['profile_id'];
        }

        $applicants = surveyApplicants::where('survey_id', $id);
        // ->whereIn('profile_id', $profileIds, $boolean, $type)
        if ($profileIds !== null) {
            $applicants  = $applicants->whereIn('profile_id', $profileIds);
        }
        $applicants = $applicants->whereNull('deleted_at')
            ->whereNull('rejected_at')
            ->orderBy("created_at", "desc")
            ->get();


        $finalData = array();

        // return $this->sendResponse($applicants);

        foreach ($applicants as $key => $applicant) {
            $job_profile = '';
            if (isset($applicant->profile->profile_occupations)) {
                if (isset($applicant->profile->profile_occupations->toArray()['0'])) {
                    $job_profile = $applicant->profile->profile_occupations->toArray()['0']['name'];
                }
            }
            $specialization = '';
            foreach ($applicant->profile->profile_specializations as $profile_specialization_key => $profile_specialization) {
                if (isset($profile_specialization->toArray()['name'])) {
                    if ($profile_specialization_key == 0) {
                        $specialization .= $profile_specialization->toArray()['name'];
                    } else {
                        $specialization .= ", " . $profile_specialization->toArray()['name'];
                    }
                }
            }

            $duration = '-';
            if($applicant->application_status == 2){
                $submission = SurveyAttemptMapping::where("survey_id", $id)->where("profile_id", $applicant->profile->id)->whereNotNull("completion_date")->first();
                
                $durationForSection = $this->secondsToTime(strtotime($submission["completion_date"]) - strtotime($submission["created_at"]));
                
                $submission_entry = SurveysEntryMapping::where("surveys_attempt_id",$submission["id"])->orderBy("created_at", "asc")->whereNull("deleted_at")->first();
                if(isset($submission_entry)){
                    $durationForSection = $this->secondsToTime(strtotime($submission["completion_date"]) - strtotime($submission_entry["created_at"]));
                    $duration = $durationForSection;
                }

                if ($survey->is_section && !empty($durationForSection)) {
                    $duration = $durationForSection;
                }
            }

            $temp = array(
                "S. No" => $key + 1,
                "Name" => htmlspecialchars_decode($applicant->profile->name),
                "Gender" => $applicant->profile->gender,
                "Profile link" => env('APP_URL') . "/@" . $applicant->profile->handle,
                "Email" => $applicant->profile->email,
                "Phone Number" => $applicant->profile->getContactDetail(),
                "generation" => $applicant->generation,
                "Occupation" => $job_profile,
                "Specialization" => $specialization,
                "Hometown" => $applicant->hometown,
                "Current City" => $applicant->current_city,
                "Application Status" => $this->frontEndApplicationStatus[$applicant->application_status] ?? "",
                "Duration" => $duration
            );
            array_push($finalData, $temp);
        }


        $relativePath = "reports/surveysAnsweredExcel/$id";
        $name = "survey-" . $id . "-" . uniqid();

        $excel = Excel::create($name, function ($excel) use ($name, $finalData) {
            // Set the title
            $excel->setTitle($name);

            // Chain the setters
            $excel->setCreator('Tagtaste')
                ->setCompany('Tagtaste');

            // Call them separately
            $excel->setDescription('A Surveys Applicants list');

            $excel->sheet('Sheetname', function ($sheet) use ($finalData) {
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
        $excel_save_path = storage_path("exports/" . $excel->filename . ".xlsx");
        $s3 = Storage::disk('s3');
        $resp = $s3->putFile($relativePath, new File($excel_save_path), ['visibility' => 'public']);
        $this->model = Storage::url($resp);
        unlink($excel_save_path);

        return $this->sendResponse();
    }

    public function downloadRejectedApplicants($id, Request $request)
    {
        $survey = $this->model->where('id', $id)->whereNull('deleted_at')->first();

        if ($survey === null) {
            $this->model = false;
            return $this->sendError("Invalid Survey");
        }
        $profileId = $request->user()->profile->id;

        if (isset($survey->company_id) && !empty($survey->company_id)) {
            $companyId = $survey->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendError("User does not belong to this company");
            }
        } else if (isset($survey->profile_id) &&  $survey->profile_id != $request->user()->profile->id) {
            // return $this->sendError("Only Admin can download report of this survey");
        }

        //filters data
        $profileIds = null;
        if ($request->has('filters') && !empty($request->filters)) {
            $getFiteredProfileIds = $this->getProfileIdOfFilter($survey, $request);
            $profileIds = $getFiteredProfileIds['profile_id'];
        }

        $applicants = surveyApplicants::where('survey_id', $id);
        // ->whereIn('profile_id', $profileIds, $boolean, $type)
        if ($profileIds !== null) {
            $applicants  = $applicants->whereIn('profile_id', $profileIds);
        }
        $applicants = $applicants->whereNull('deleted_at')
            ->whereNotNull('rejected_at')
            ->orderBy("created_at", "desc")
            ->get();


        $finalData = array();

        // return $this->sendResponse($applicants);

        foreach ($applicants as $key => $applicant) {
            $job_profile = '';
            if (isset($applicant->profile->profile_occupations)) {
                if (isset($applicant->profile->profile_occupations->toArray()['0'])) {
                    $job_profile = $applicant->profile->profile_occupations->toArray()['0']['name'];
                }
            }
            $specialization = '';
            foreach ($applicant->profile->profile_specializations as $profile_specialization_key => $profile_specialization) {
                if (isset($profile_specialization->toArray()['name'])) {
                    if ($profile_specialization_key == 0) {
                        $specialization .= $profile_specialization->toArray()['name'];
                    } else {
                        $specialization .= ", " . $profile_specialization->toArray()['name'];
                    }
                }
            }

            $temp = array(
                "S. No" => $key + 1,
                "Name" => htmlspecialchars_decode($applicant->profile->name),
                "Gender" => $applicant->profile->gender,
                "Profile link" => env('APP_URL') . "/@" . $applicant->profile->handle,
                "Email" => $applicant->profile->email,
                "Phone Number" => $applicant->profile->getContactDetail(),
                "Occupation" => $job_profile,
                "Specialization" => $specialization,
                "Hometown" => $applicant->hometown,
                "Current City" => $applicant->current_city
            );
            array_push($finalData, $temp);
        }


        $relativePath = "reports/surveysAnsweredExcel/$id";
        $name = "survey-" . $id . "-" . uniqid();

        $excel = Excel::create($name, function ($excel) use ($name, $finalData) {
            // Set the title
            $excel->setTitle($name);

            // Chain the setters
            $excel->setCreator('Tagtaste')
                ->setCompany('Tagtaste');

            // Call them separately
            $excel->setDescription('A Surveys Applicants list');

            $excel->sheet('Sheetname', function ($sheet) use ($finalData) {
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
        $excel_save_path = storage_path("exports/" . $excel->filename . ".xlsx");
        $s3 = Storage::disk('s3');
        $resp = $s3->putFile($relativePath, new File($excel_save_path), ['visibility' => 'public']);
        $this->model = Storage::url($resp);
        unlink($excel_save_path);

        return $this->sendResponse();
    }

    public function rollbackSurveyApplicant(Request $request, $id)
    {
        $survey = $this->model->where("id", "=", $id)->whereNull("deleted_at")->where(function ($q) {
            $q->orWhere('state', "!=", config("constant.SURVEY_STATES.CLOSED"));
            $q->orWhere("state", "!=", config("constant.SURVEY_STATES.EXPIRED"));
        })->first();

        if (empty($survey)) {
            return $this->sendError("You cannot perform this action on this survey anymore.");
        }

        $profileIds = $request->input('profile_id');
        $err = true;
        foreach ($profileIds as $profileId) {
            $info = [];
            $currentStatus = surveyApplicants::where("profile_id", $profileId)->where('survey_id', $id)->whereNull('deleted_at')->pluck('application_status');
            $currentStatus = $currentStatus[0];
            if ($currentStatus == 1) {
                //perform operation
                Redis::set("surveys:application_status:$id:profile:$profileId", 0);
                $t = surveyApplicants::where("profile_id", $profileId)->where('survey_id', $id)->whereNull('deleted_at')->update(["application_status" => 0]);
                $err = false;

                if ($t) {
                    $this->model = true;
                    $applicant =  surveyApplicants::where("profile_id", $profileId)->whereNull('deleted_at')->where('survey_id', $id)->first();

                    $info["is_survey"] = 1;
                    $info["is_invited"] = $applicant->is_invited;
                    if ($applicant->is_invited) {
                        Redis::del("surveys:application_status:$id:profile:$profileId");
                        surveyApplicants::where("profile_id", $profileId)->where('survey_id', $id)->update(["deleted_at" => \Carbon\Carbon::now()]);
                    }
                } else {
                    $err = true;
                }
                $who = null;


                $company = Company::where('id', $survey->company_id)->first();
                if (empty($company)) {
                    $who = Profile::join('users', 'users.id', 'profiles.user_id')->where("profiles.id", "=", $survey->profile_id)->first();
                }
                $survey->profile_id = $profileId;
                event(new \App\Events\Actions\RollbackTaster($survey, $who, null, null, null, $company, $info));
            } else {
                $err = true;
            }
        }
        if ($err) {
            $this->model = false;
            return $this->sendError('Sorry, something went wrong');
        }
        return $this->sendResponse();
    }

    public function rejectApplicant(Request $request, $id)
    {
        $survey = $this->model->where("id", "=", $id)->whereNull("deleted_at")->where(function ($q) {
            $q->orWhere('state', "!=", config("constant.SURVEY_STATES.CLOSED"));
            $q->orWhere("state", "!=", config("constant.SURVEY_STATES.EXPIRED"));
        })->first();

        $this->model = false;
        if ($survey === null) {
            return $this->sendError("Invalid survey Project.");
        }


        $profileId = $request->user()->profile->id;


        $shortlistedProfiles = $request->input('profile_id');
        if (!is_array($shortlistedProfiles)) {
            $shortlistedProfiles = [$shortlistedProfiles];
        }

        // check if any user is already notified or not
        $checkAssignUser = \DB::table('survey_applicants')->where('survey_id', $id)->whereNull('deleted_at')->whereIn('profile_id', $shortlistedProfiles)
            ->where('application_status', 1)
            ->exists();
        if ($checkAssignUser) {
            return $this->sendError("You can not remove this user.");
        }
        $now = Carbon::now()->toDateTimeString();
        // begin transaction
        \DB::beginTransaction();
        try {

            // remove applicant
            $updated = \DB::table('survey_applicants')
                ->where('survey_id', $id)
                ->whereIn('profile_id', $shortlistedProfiles)
                ->update(['rejected_at' => $now]);

            $this->model = (bool) $updated;

            \DB::commit();
        } catch (\Exception $e) {
            // roll in case of error
            \DB::rollback();
            \Log::info($e->getMessage());
            $this->model = null;
            return $this->sendError("Please try again after some time.");
        }

        return $this->sendResponse();
    }

    public function getRejectApplicants(Request $request, $id)
    {
        $survey = $this->model->where('id', $id)->whereNull('deleted_at')->first();
        $page = $request->input('page');
        $q = $request->input('q');
        $filters = $request->input('filters');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $this->model = [];
        $list = surveyApplicants::where('survey_id', $id)->whereNull('deleted_at') //->whereNull('shortlisted_at')
            ->whereNotNull('rejected_at');

        if (isset($q) && $q != null) {
            $ids = $this->getSearchedProfile($q, $id);
            $list = $list->whereIn('id', $ids);
        }

        if (isset($filters) && $filters != null) {
            $getFiteredProfileIds = $this->getProfileIdOfFilter($survey, $request);
            $profileIds = $getFiteredProfileIds['profile_id'];
            $list = $list->whereIn('profile_id', $profileIds);
        }
        if ($request->sortBy != null) {
            $archived = $this->sortApplicants($request->sortBy, $list, $id);
        }

        $this->model['rejectedApplicantsCount'] = $list->count();
        $list = $list->skip($skip)->take($take)->get();
        $this->model['rejectedApplicantList'] = $list;

        return $this->sendResponse();
    }

    public function shortlistApplicant(Request $request, $id)
    {
        $survey = $this->model->where("id", "=", $id)->whereNull("deleted_at")->where(function ($q) {
            $q->orWhere('state', "!=", config("constant.SURVEY_STATES.CLOSED"));
            $q->orWhere("state", "!=", config("constant.SURVEY_STATES.EXPIRED"));
        })->first();
        $this->model = false;
        if ($survey === null) {
            return $this->sendError("Invalid survey Project.");
        }
        $profileId = $request->user()->profile->id;



        $shortlistedProfiles = $request->input('profile_id');
        if (!is_array($shortlistedProfiles)) {
            $shortlistedProfiles = [$shortlistedProfiles];
        }
        $now = Carbon::now()->toDateTimeString();

        // begin transaction
        \DB::beginTransaction();
        try {

            // shortlist applicant
            $updated =  \DB::table('survey_applicants')
                ->where('survey_id', $id)
                ->whereNull('deleted_at')
                ->whereIn('profile_id', $shortlistedProfiles)
                ->update([
                    'application_status' => 0,
                    'rejected_at' => null
                ]);
            $this->model  = (bool)$updated;

            \DB::commit();
        } catch (\Exception $e) {
            // roll in case of error
            \DB::rollback();
            \Log::info($e->getMessage());
            $this->model = null;
            return $this->sendError("Please try again after some time.");
        }



        return $this->sendResponse();
    }


    public function getInvitedApplicants(Request $request, $id)
    {
        $survey = $this->model->where('id', $id)->whereNull('deleted_at')->first();
        $page = $request->input('page');
        $q = $request->input('q');
        $filters = $request->input('filters');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $this->model = [];
        $list = surveyApplicants::where('survey_id', $id)->where('is_invited', 1)->whereNull('deleted_at')
            ->whereNull('rejected_at');


        if (isset($q) && $q != null) {
            $ids = $this->getSearchedProfile($q, $id);
            $list = $list->whereIn('id', $ids);
        }


        if (isset($filters) && $filters != null) {
            $getFiteredProfileIds = $this->getProfileIdOfFilter($survey, $request);
            $profileIds = $getFiteredProfileIds['profile_id'];
            $list = $list->whereIn('profile_id', $profileIds);
        }
        if ($request->sortBy != null) {
            $archived = $this->sortApplicants($request->sortBy, $list, $id);
        }

        $this->model['invitedApplicantsCount'] = $list->count();
        $this->model['invitedApplicants'] = $list->skip($skip)->take($take)->get();

        return $this->sendResponse();
    }

    function secondsToTime($seconds)
    {
        $s = $seconds % 60;
        $m = floor(($seconds % 3600) / 60);
        $h = floor(($seconds % 86400) / 3600);
        $d = floor(($seconds % 2592000) / 86400);
        $M = floor($seconds / 2592000);
        
        $durationStr = "";
        if ($M > 0) {
            $durationStr .= $M."m ";
        }

        if ($d > 0) {
            $durationStr .= $d."d ";
        }

        if ($h > 0) {
            $durationStr .= $h."h ";
        }

        if ($m > 0) {
            $durationStr .= $m."m ";
        }

        if ($s > 0) {
            $durationStr .= $s."s";
        }

        return $durationStr;
    }

    public function getSubmissionTimeline($id, $profile_id, Request $request)
    {
        $checkIFExists = $this->model->where("id", "=", $id)->whereNull("deleted_at")->first();
        if (empty($checkIFExists)) {
            $this->model = false;
            return $this->sendNewError("Invalid Survey");
        }


        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendNewError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            return $this->sendNewError("Only Admin can view applicant list");
        }
        //paginate
        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $this->model = [];
        //filters data

        $applicant = surveyApplicants::where("survey_id", "=", $id)->where("profile_id", $profile_id)->whereNull("deleted_at")->first();

        if (empty($applicant)) {
            return $this->sendNewError("User has not participated in survey");
        }

        $submissions = SurveyAttemptMapping::where("survey_id", $id)->where("profile_id", $profile_id)->whereNotNull("completion_date")->orderBy("attempt", "desc")->skip($skip)->take($take)->get()->toArray();
        if (empty($submissions)) {
            return $this->sendNewError("User has not completed the survey");
        }
        $profile = [];
        $profile["id"] = $applicant->id;
        $profile["profile_id"] = $profile_id;
        $profile["company_id"] = $applicant->company_id;
        $profile["survey_id"] = $id;
        $profile["inprogress_count"] = $applicant->inprogress_count;
        $profile["submission_count"] = $applicant->submission_count;

        $submission_status = [];
        
        foreach ($submissions as $index => $submission) {
            $submission_status = [];
            $duration = $this->secondsToTime(strtotime($submission["completion_date"]) - strtotime($submission["created_at"]));

            //create submission timeline
            $timeline_data = SurveysEntryMapping::where("surveys_attempt_id",$submission["id"])->orderBy("created_at", "asc")->whereNull("deleted_at")->get();
            $submission_status["id"] = $submission["id"];
            $submission_status["title"] = "Submission ".($applicant->submission_count - $index);
            $submission_status["is_collapsed"] = true;
            
            $timeline = []; 
            $section_exist = false;   
            $last_activity = null;
            $last_section = null;
            foreach($timeline_data as $t){
                $timeline_obj = [];
                $timeline_obj["section_id"] = $t->section_id;
                if($t->activity == config("constant.SURVEY_ACTIVITY.START")){
                    $timeline_obj["title"] = "BEGIN";
                    $timeline_obj["color_code"] = "#00A146";
                }else if($t->activity == config("constant.SURVEY_ACTIVITY.SECTION_SUBMIT")){
                    $timeline_obj["title"] = $t->section_title;
                    $timeline_obj["color_code"] = "#171717";
                    $section_exist = true;
                }else if($t->activity == config("constant.SURVEY_ACTIVITY.END")){
                    if($section_exist){
                        $timeline_obj["title"] = $t->section_title;
                        $timeline_obj["color_code"] = "#171717";                            
                    }else{
                        $timeline_obj["title"] = "END";
                        $timeline_obj["color_code"] = "#00AEB3";    
                    }
                }

                if($last_section == $t->section_id && $last_activity == $t->activity){
                    $last_obj = array_pop($timeline);
                    $last_timestamps = $last_obj["timestamps"];
                    array_push($last_timestamps, ["title"=>date("d M Y, h:i:s A", strtotime($t->created_at))]);
                    $last_obj["timestamps"] = $last_timestamps;
                    array_push($timeline, $last_obj);
                }else{
                    $timeline_obj["timestamps"] = [["title"=>date("d M Y, h:i:s A", strtotime($t->created_at))]];
                    array_push($timeline, $timeline_obj);
                    if($section_exist && $t->activity == config("constant.SURVEY_ACTIVITY.END")){
                        array_push($timeline, ["title"=>"END", "color_code"=>"#00AEB3"]);    
                    }    
                }
                $last_section = $t->section_id;
                $last_activity = $t->activity;
            }

            $entry_timestamp = $timeline_data[0] ?? null;
            if(count($timeline) == 0){
                //insert begin for old data
                $timeline_obj = ["title"=>"BEGIN", "color_code"=>"#00A146"];
                $timeline_obj["timestamps"] = [["title"=>date("d M Y, h:i:s A", strtotime($submission["created_at"]))]];
                if(isset($entry_timestamp)){
                    $timeline_obj["timestamps"] = [["title"=>date("d M Y, h:i:s A", strtotime($entry_timestamp->created_at))]];
                }
                array_push($timeline, $timeline_obj);    

                //insert end for old data
                $timeline_obj = ["title"=>"END", "color_code"=>"#00AEB3"];
                $timeline_obj["timestamps"] = [["title"=>date("d M Y, h:i:s A", strtotime($submission["completion_date"]))]];
                array_push($timeline, $timeline_obj);  
            }

            $submission_status["timeline"] = $timeline;

            //calculate duration if entry timestamp exist
            if(isset($entry_timestamp)){
                $duration = $this->secondsToTime(strtotime($submission["completion_date"]) - strtotime($entry_timestamp["created_at"]));
            }
            
            $submission_status["duration"] = $duration;
            $profile["submission_status"][] = $submission_status;
            $profile["profile"] = $applicant->profile;
        }

        $this->model = $profile;
        return $this->sendNewResponse();
    }

    public function getSubmissionStatus($id, $profile_id, Request $request)
    {
        $checkIFExists = $this->model->where("id", "=", $id)->whereNull("deleted_at")->first();
        if (empty($checkIFExists)) {
            $this->model = false;
            return $this->sendNewError("Invalid Survey");
        }


        if (isset($checkIFExists->company_id) && !empty($checkIFExists->company_id)) {
            $companyId = $checkIFExists->company_id;
            $userId = $request->user()->id;
            $company = Company::find($companyId);
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if (!$userBelongsToCompany) {
                return $this->sendNewError("User does not belong to this company");
            }
        } else if (isset($checkIFExists->profile_id) &&  $checkIFExists->profile_id != $request->user()->profile->id) {
            return $this->sendNewError("Only Admin can view applicant list");
        }
        //paginate
        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $this->model = [];
        //filters data

        $applicant = surveyApplicants::where("survey_id", "=", $id)->where("profile_id", $profile_id)->whereNull("deleted_at")->first();

        if (empty($applicant)) {
            return $this->sendNewError("User has not participated in survey");
        }
        $submissions = SurveyAttemptMapping::where("survey_id", $id)->where("profile_id", $profile_id)->whereNotNull("completion_date")->skip($skip)->take($take)->get()->toArray();
        if (empty($submissions)) {
            return $this->sendNewError("User has not completed the survey");
        }
        $profile = [];
        $profile["id"] = $applicant->id;
        $profile["profile_id"] = $profile_id;
        $profile["company_id"] = $applicant->company_id;
        $profile["survey_id"] = $id;
        $profile["inprogress_count"] = $applicant->inprogress_count;
        $profile["submission_count"] = $applicant->submission_count;

        $submission_status = [];
        foreach ($submissions as $submission) {
            $submission_status = [];
            $duration = "-";
            $durationForSection = $this->secondsToTime(strtotime($submission["completion_date"]) - strtotime($submission["created_at"]));

            $submission_entry = SurveysEntryMapping::where("surveys_attempt_id",$submission["id"])->orderBy("created_at", "asc")->whereNull("deleted_at")->first();
            
            //Check submission duration with start survey
            if(isset($submission_entry)){
                $durationForSection = $this->secondsToTime(strtotime($submission["completion_date"]) - strtotime($submission_entry["created_at"]));
                $duration = $durationForSection;
            }

            if ($checkIFExists->is_section && !empty($durationForSection)) {
                $duration = $durationForSection;
            }
            $submission_status[] = ["title" => "Date", "value" => date("d M Y", strtotime($submission["completion_date"]))];
            $submission_status[] = ["title" => "Time", "value" => date("h:i:s A", strtotime($submission["completion_date"]))];
            $submission_status[] = ["title" => "Duration", "value" => $duration];

            
            $profile["submission_status"][] = $submission_status;
            $profile["profile"] = $applicant->profile;
        }

        $this->model = $profile;
        return $this->sendResponse();
    }
}