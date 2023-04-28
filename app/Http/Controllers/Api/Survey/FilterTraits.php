<?php

namespace App\Http\Controllers\Api\Survey;

use App\surveyApplicants;
use Illuminate\Http\Request;
use App\Helper;

trait FilterTraits
{


    public function getProfileIdOfFilter($surveyDetails, Request $request)
    {

        $filters = $request->filters;
        $profileIds = collect([]);

        if ($profileIds->count() == 0 && isset($filters['profile_id'])) {
            $filterProfile = [];
            foreach ($filters['profile_id'] as $filter) {
                //$isFilterAble = true;
                $filterProfile[] = (int)$filter;
            }
            $profileIds = $profileIds->merge($filterProfile);
        }




        if (!empty($filters)) {
            $Ids = surveyApplicants::where('survey_id', $surveyDetails->id)
                ->whereNull('survey_applicants.deleted_at');
        }
        
        if (isset($filters['city'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['city'] as $city) {
                    $query->orWhere('city', 'LIKE', $city);
                }
            });
        }

        if (isset($filters['age'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['age'] as $age) {
                    $age = htmlspecialchars_decode($age);
                    $query->orWhere('age_group', 'LIKE', $age);
                }
            });
        }


        if (isset($filters['profile'])) {
            $Ids =   $Ids->leftJoin('profile_specializations', 'survey_applicants.profile_id', '=', 'profile_specializations.profile_id')
                ->leftJoin('specializations', 'profile_specializations.specialization_id', '=', 'specializations.id');

            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['profile'] as $profile) {
                    $query->orWhere('name', 'LIKE', $profile);
                }
            });
        }

        if (isset($filters['gender'])) {

            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['gender'] as $gender) {
                    $query->orWhere('survey_applicants.gender', 'LIKE', $gender);
                }
            });
        }

        if (isset($filters['application_status'])) {

            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['application_status'] as $status) {

                    $query->orWhere('survey_applicants.application_status', config("constant.SURVEY_APPLICANT_STATUS." . ucwords($status)));
                }
            });
        }

        if (isset($filters['sensory_trained']) || isset($filters['super_taster']) || isset($filters['user_type'])) {
            $Ids =   $Ids->leftJoin('profiles', 'survey_applicants.profile_id', '=', 'profiles.id');
        }
        if (isset($filters['sensory_trained'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['sensory_trained'] as $sensory) {
                    if ($sensory == 'Yes')
                        $sensory = 1;
                    else
                        $sensory = 0;
                    $query->orWhere('profiles.is_sensory_trained', $sensory);
                }
            });
        }

        if (isset($filters['super_taster'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['super_taster'] as $superTaster) {
                    if ($superTaster == 'SuperTaster')
                        $superTaster = 1;
                    else
                        $superTaster = 0;
                    $query->orWhere('profiles.is_tasting_expert', $superTaster);
                }
            });
        }

        if (isset($filters['user_type'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['user_type'] as $userType) {
                    if ($userType == 'Expert')
                        $userType = 1;
                    else
                        $userType = 0;
                    $query->orWhere('profiles.is_expert', $userType);
                }
            });
        }


        if ($profileIds->count() > 0 && isset($Ids)) {
            $Ids = $Ids->whereIn('profile_id', $profileIds);
        }

        if (isset($Ids)) {
            $isFilterAble = true;
            $Ids = $Ids->get()->pluck('profile_id');
            $profileIds = $profileIds->merge($Ids);
        }
        if ($profileIds->count() > 0 && isset($filters['exclude_profile_id'])) {
            $filterNotProfileIds = [];
            foreach ($filters['exclude_profile_id'] as $filter) {
                $isFilterAble = true;
                $filterNotProfileIds[] = (int)$filter;
            }
            $profileIds = $profileIds->diff($filterNotProfileIds);
        }
        if ($profileIds->count() == 0 && isset($filters['exclude_profile_id'])) {
            $isFilterAble = false;
            $excludeAble = false;
            $filterNotProfileIds = [];
            foreach ($filters['exclude_profile_id'] as $filter) {
                $isFilterAble = false;
                $excludeAble = true;
                $filterNotProfileIds[] = (int)$filter;
            }
            $profileIds = $profileIds->merge($filterNotProfileIds);
        }
        if (isset($isFilterAble) && $isFilterAble)
            return ['profile_id' => $profileIds, 'type' => false];
        else
            return ['profile_id' => $profileIds, 'type' => true];
    }

    public function getFilterParameters($survey_id, Request $request)
    {

        $filters = $request->input('filter');

        $gender = [['key' => 'Male', 'value' => 'Male'], ['key' => 'Female', 'value' => 'Female'], ['key' => 'Others', 'value' => 'Others']];
        $age = Helper::getGenerationFilter();

        // $age = [['key' => 'gen-z', 'value' => 'Gen-Z'], ['key' => 'gen-x', 'value' => 'Gen-X'], ['key' => 'millenials', 'value' => 'Millenials'], ['key' => 'yold', 'value' => 'YOld']];

        $application_status = [["key" => 0, "value" => 'invited'], ["key" => 1, "value" => 'incomplete'], ['key' => 2, 'value' => "completed"]];
        $userType = ['Expert', 'Consumer'];
        $sensoryTrained = ["Yes", "No"];
        $superTaster = ["SuperTaster", "Normal"];
        $applicants = \DB::table('survey_applicants')->where('survey_id', $survey_id)->get();
        $city = [];
        $i = 0;
        foreach ($applicants as $applicant) {
            if (isset($applicant->city)) {
                if (!in_array($applicant->city, $city))
                    $city[$i]['key'] = $applicant->city;
                $city[$i]['value'] = $applicant->city;
                $i++;
            }
        }
        $data = [];

        if (!empty($filters) && is_array($filters)) {
            foreach ($filters as $filter) {
                if ($filter == 'gender')
                    $data['gender'] = $gender;
                if ($filter == 'age')
                    $data['age'] = $age;
                if ($filter == 'city')
                    $data['city'] = $city;
                if ($filter == 'super_taster')
                    $data['super_taster'] = $superTaster;
                if ($filter == 'user_type')
                    $data['user_type'] = $userType;
                if ($filter == 'sensory_trained')
                    $data['sensory_trained'] = $sensoryTrained;
                // if($filter == 'application_status')
                // $data['application_status'] = $currentStatus;
            }
        } else {
            $data = [
                'gender' => $gender, 'age' => $age, 'city' => $city
                // ,'application_status'=>$currentStatus
            ];
        }
        $this->model = $data;

        return $this->sendResponse();
    }

    public function sortApplicants($sortBy, $applications, $surveyId)
    {
        $key = array_keys($sortBy)[0];
        $value = $sortBy[$key];
        if ($key == 'name') {
            $userNames = $this->getUserNames($surveyId);
            $companyNames = $this->getCompanyNames($surveyId);
            $users = $userNames->merge($companyNames);
            if ($value == 'asc')
                $order = array_column($users->sortBy('name')->values()->all(), 'id');
            else
                $order = array_column($users->sortByDesc('name')->values()->all(), 'id');
            $placeholders = implode(',', array_fill(0, count($order), '?'));
            return $applications->orderByRaw("field(survey_applicants.id,{$placeholders})", $order)
                ->select('survey_applicants.*');
        }
        return $applications->orderBy('survey_applicants.created_at', $value)->select('survey_applicants.*');
    }

    private function getCompanyNames($id)
    {
        return surveyApplicants::where('survey_id', $id)
            ->leftJoin('companies', function ($q) {
                $q->on('survey_applicants.company_id', '=', 'companies.id');
            })->where('survey_applicants.company_id', '!=', null)
            ->select('companies.name AS name', 'survey_applicants.id')
            ->get();
    }

    private function getUserNames($id)
    {
        return surveyApplicants::where('survey_id', $id)
            ->leftJoin('profiles AS p', function ($q) {
                $q->on('survey_applicants.profile_id', '=', 'p.id')
                    ->where('survey_applicants.company_id', '=', null);
            })->leftJoin('users', 'p.user_id', '=', 'users.id')->where('users.name', '!=', null)
            ->select('users.name as name', 'survey_applicants.id')
            ->get();
    }

    public function getSearchedProfile($q, $id)
    {
        $searchByProfile = \DB::table('survey_applicants')
            ->where('survey_id', $id)
            ->whereNUll('company_id')
            ->join('profiles', 'survey_applicants.profile_id', '=', 'profiles.id')
            ->join('users', 'profiles.user_id', '=', 'users.id')
            ->where('users.name', 'LIKE', '%' . $q . '%')
            ->pluck('survey_applicants.id');

        $searchByCompany = \DB::table('survey_applicants')
            ->where('survey_id', $id)
            ->leftJoin('companies', 'survey_applicants.company_id', '=', 'companies.id')
            ->where('companies.name', 'LIKE', $q . '%')
            ->pluck('survey_applicants.id');
        return $searchByProfile->merge($searchByCompany);
    }
}
