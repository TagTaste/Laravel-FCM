<?php

namespace App\Http\Controllers\Api\Survey;

use App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Recipe\Profile;
use App\surveyApplicants;
use App\Surveys;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;
use SurveyApplicants as GlobalSurveyApplicants;
use Tagtaste\Api\SendsJsonResponse;

class SurveyApplicantController extends Controller
{

    use SendsJsonResponse, FilterTraits;
    public function __construct(Surveys $model)
    {
        $this->model = $model;
    }

    public function index($id, Request $request)
    {
        $checkIFExists = $this->model->where("id", "=", $id)->whereNull("deleted_at")->first();
        if (empty($checkIFExists)) {
            return $this->sendError("Invalid Survey");
        }

        //paginate
        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $this->model = [];
        //filters data
        $q = $request->input('q');
        $profileIds = [];
        if ($request->has('filters') && !empty($request->filters)) {
            $getFiteredProfileIds = $this->getProfileIdOfFilter($checkIFExists, $request);
            $profileIds = $getFiteredProfileIds['profile_id'];
        }


        $applicants = surveyApplicants::where("survey_id", "=", $id)->whereNull("deleted_at");
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

        if(!empty($profileIds)){
        $applicants = $applicants
            ->whereIn('profile_id', $profileIds);
        }

        $applicants = $applicants->skip($skip)->take($take)->get()->toArray();


        $profileIdsForCounts = array_column($applicants,'profile_id');
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
        $this->model["overview"][] = ['title' => "Sensory Trained", "count" => $countSensory->count()];
        $this->model["overview"][] = ['title' => "Experts", "count" => $countExpert->count()];
        $this->model["overview"][] = ['title' => "Super Taster", "count" => $countSuperTaste->count()];

        return $this->sendResponse();
    }

    public function showInterest($id, Request $request)
    {

        $checkIFExists = $this->model->where("id", "=", $id)->whereNull("deleted_at")->first();
        if (empty($checkIFExists)) {
            return $this->sendError("Invalid Survey");
        }

        $checkIfAlreadyInterested = surveyApplicants::where("profile_id", $request->user()->profile->id)->where("survey_id", $id)->whereNull('deleted_at')->first();

        if (!empty($checkIfAlreadyInterested)) {
            return $this->sendError("Already Shown Interest");
        }

        if ($checkIFExists->profile_id == $request->user()->profile->id) {
            return $this->sendError("Admins Cannot Show Interest");
        }

        $profile = Profile::where('id', $request->user()->profile->id)->first();


        $data = [
            'profile_id' => $request->user()->profile->id, 'survey_id' => $id,
            'message' => ($request->message ?? null),
            'age_group' => $profile->ageRange ?? null, 'gender' => $profile->gender ?? null, 'hometown' => $profile->hometown ?? null, 'current_city' => $profile->city ?? null, "application_status" => (int)config("constant.SURVEY_APPLICANT_ANSWER_STATUS.INVITED"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")
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

    public function beginSurvey($id, Request $request)
    {
        $checkIFExists = $this->model->where("id", "=", $id)->whereNull("deleted_at")->first();
        if (empty($checkIFExists)) {
            return $this->sendError("Invalid Survey");
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
                $checkIFInvited = surveyApplicants::where("survey_id", "=", $id)->where("application_status", "=", config("constant.SURVEY_APPLICANT_ANSWER_STATUS.INVITED"))->where("profile_id", $profileId)->first();

                if (empty($checkIFInvited)) {
                    $this->model = false;
                    return $this->sendError("User has already began/completed survey");
                }
            }

            $currentStatus = Redis::get("application_status:$id:profile:$profileId");
            if ($currentStatus == 0) {
                Redis::set("application_status:$id:profile:$profileId", 1);
                $b = surveyApplicants::where("profile_id", $profileId)->where('survey_id', $id)->where('application_status', 0)->update(["application_status" => 1]);
                if ($b == false) {
                    $this->model = false;
                }
            }

            $who = null;
            if ($checkIFExists->company_id) {
                $company = Company::where('id', $checkIFExists->company_id)->first();
                if (empty($company)) {
                    $who = Profile::where("id", "=", $checkIFExists->profile_id)->first();
                }
            }
            $checkIFExists->profile_id = $profileId;
            // event(new \App\Events\Actions\BeginTasting($collaborate, $who, null, null, null, $company, $batchId));
        }

        return $this->sendResponse();
    }
}
