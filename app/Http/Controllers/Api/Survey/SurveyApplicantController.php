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
use Maatwebsite\Excel\Facades\Excel;
use SurveyApplicants as GlobalSurveyApplicants;
use Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

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

        if (!empty($profileIds)) {
            $applicants = $applicants
                ->whereIn('profile_id', $profileIds);
        }

        $applicants = $applicants->skip($skip)->take($take)->get()->toArray();


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

        if($checkIFExists->state==config("constant.SURVEY_STATES.CLOSED")){
            $this->model = ["status" => false];
            return $this->sendError("Survey is closed. Cannot show interest");
        }

        if($checkIFExists->state==config("constant.SURVEY_STATES.EXPIRED")){
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


        $data = [
            'profile_id' => $request->user()->profile->id, 'survey_id' => $id,
            'message' => ($request->message ?? null),
            'age_group' => $profile->ageRange ?? null, 'gender' => $profile->gender ?? null, 'hometown' => $profile->hometown ?? null, 'current_city' => $profile->city ?? null, "application_status" => (int)config("constant.SURVEY_APPLICANT_ANSWER_STATUS.INVITED"), "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")
        ];

        $create = surveyApplicants::create($data);

        if (isset($create->id)) {
            $profile = $request->user()->profile->id;
            // Redis::sAdd("surveys:$id:profile:$request->user()->profile_id:", $batch->id);
            Redis::set("surveys:application_status:$id:profile:$profile", 0);
            $this->model = true;
            $this->messages = "Thanks for showing interest. We will notify you when admin accept your request for survey.";
        } else {
            $this->model = false;
            $this->errors[] = "Failed to show interest in survey";
        }
        return $this->sendResponse();
    }

    public function beginSurvey($id, Request $request)
    {
        $checkIFExists = $this->model->where("id", "=", $id)->whereNull("deleted_at")->where('state', "!=", config("constant.SURVEY_STATES.CLOSED"))->orWhere("state","!=",config("constant.SURVEY_STATES.EXPIRED"))->first();
        
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
                $checkIFInvited = surveyApplicants::where("survey_id", "=", $id)->where("application_status", "=", config("constant.SURVEY_APPLICANT_ANSWER_STATUS.INVITED"))->where("profile_id", $profileId)->first();

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
                }else{
                    Redis::set("surveys:application_status:$id:profile:$profileId", 1);
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
            // event(new \App\Events\Actions\BeginTasting($survey, $who, null, null, null, $company, $batchId));
        }
        return $this->sendResponse();
    }

    public function userList($id, Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $query = $request->input('q');

        $this->model = \App\Recipe\Profile::select('profiles.*')->join('users', 'profiles.user_id', '=', 'users.id')
            ->where('profiles.id', '!=', $loggedInProfileId)->where('users.name', 'like', "%$query%")->take(15)->get();

        return $this->sendResponse();
    }

    public function inviteForReview($id, Request $request)
    {
        $survey = $this->model->where('id', $id)->whereNull('deleted_at')->where('state', "!=", config("constant.SURVEY_STATES.CLOSED"))->orWhere("state","!=",config("constant.SURVEY_STATES.EXPIRED"))->first();
        
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

        $checkExist = surveyApplicants::whereIn('profile_id', $profileIds)->where('survey_id', $id)->exists();
        if ($checkExist) {
            return $this->sendError("Already Invited");
        }
        // $company = Company::where('id', $survey->company_id)->first();
        $now = date("Y-m-d H:i:s");
        foreach ($profileIds as $profileId) {
            $survey->profile_id = $profileId;
            $profile = Profile::where("id",$profileId)->first();
            // event(new \App\Events\Actions\InviteForReview($survey, null, null, null, null, $company));
            $inputs = ['profile_id' => $profileId, 'survey_id' => $id, 'is_invited' => 1, 'created_at' => $now, 'updated_at' => $now, "application_status" => config("constant.SURVEY_APPLICANT_ANSWER_STATUS.INCOMPLETE"),'age_group' => $profile->ageRange ?? null, 'gender' => $profile->gender ?? null, 'hometown' => $profile->hometown ?? null, 'current_city' => $profile->city ?? null];
            $c = surveyApplicants::create($inputs);
            if (isset($c->id)) {
                Redis::set("surveys:application_status:$id:profile:$profileId", 1);
            }
        }

        $this->model = surveyApplicants::whereIn('profile_id', $profileIds)->where('survey_id', $id)->get();
        return $this->sendResponse();
    }

    public function applicantFilters($id, Request $request)
    {
        $gender = ['Male', 'Female', 'Other'];
        $age = ['< 18', '18 - 35', '35 - 55', '55 - 70', '> 70'];

        $userType = ['Expert', 'Consumer'];
        $sensoryTrained = ["Yes", "No"];
        $superTaster = ["SuperTaster", "Normal"];
        $applicants = \DB::table('survey_applicants')->where('survey_id', $id)->get();
        $city = [];
        $profile = [];
        $hometown = [];
        $current_city = [];
        foreach ($applicants as $applicant) {
            if (isset($applicant->city)) {
                if (!in_array($applicant->city, $city))
                    $city[] = $applicant->city;
            }

            $specializations = \DB::table('profiles')
                ->leftJoin('profile_specializations', 'profiles.id', '=', 'profile_specializations.profile_id')
                ->leftJoin('specializations', 'specializations.id', '=', 'profile_specializations.specialization_id')
                ->where('profiles.id', $applicant->profile_id)
                ->pluck('name');
            foreach ($specializations as $specialization) {
                if (!in_array($specialization, $profile) && $specialization != null)
                    $profile[] = $specialization;
            }
        }
        //$profile = array_filter($profile);
        $data = [];
        $filters = $request->input('filter');
        if (count($filters)) {
            foreach ($filters as $filter) {
                if ($filter == 'gender')
                    $data['gender'] = $gender;
                if ($filter == 'age')
                    $data['age'] = $age;
                if ($filter == 'city')
                    $data['city'] = $city;

                if ($filter == 'profile')
                    $data['profile'] = $profile;
                if ($filter == 'super_taster')
                    $data['super_taster'] = $superTaster;
                if ($filter == 'user_type')
                    $data['user_type'] = $userType;
                if ($filter == 'sensory_trained')
                    $data['sensory_trained'] = $sensoryTrained;
            }
        } else {
            $data = ['gender' => $gender, 'age' => $age, 'city' => $city,  'profile' => $profile, "sensory_trained" => $sensoryTrained, "user_type" => $userType, "super_taster" => $superTaster];
        }
        $this->model = $data;
        return $this->sendResponse();
    }


    public function export($id, Request $request)
    {
        $survey = $this->model->where('id', $id)->whereNull('deleted_at')->first();

        if ($survey === null) {
            return $this->sendError("Invalid Survey");
        }
        $profileId = $request->user()->profile->id;

        if (!$request->user()->profile->is_premium) {
            return $this->sendError("You dont have access to this premium feature.");
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
            return $this->sendError("Only Admin can close the survey");
        }

        //filters data
        $profileIds = [];
        if ($request->has('filters') && !empty($request->filters)) {
            $getFiteredProfileIds = $this->getProfileIdOfFilter($survey, $request);
            $profileIds = $getFiteredProfileIds['profile_id'];
        }

        $applicants = surveyApplicants::where('survey_id', $id)
            // ->whereIn('profile_id', $profileIds, $boolean, $type)
            ->whereIn('profile_id', $profileIds)
            ->whereNull('deleted_at')
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
}
