<?php

namespace App\Http\Controllers\Api\Survey;

use App\surveyApplicants;
use Illuminate\Http\Request;

trait FilterTraits
{


    public function getProfileIdOfFilter($surveyDetails, Request $request)
    {

        $filters = $request->filters;
        $profileIds = collect([]);

        if ($profileIds->count() == 0 && isset($filters['include_profile_id'])) {
            $filterProfile = [];
            foreach ($filters['include_profile_id'] as $filter) {
                //$isFilterAble = true;
                $filterProfile[] = (int)$filter;
            }
            $profileIds = $profileIds->merge($filterProfile);
        }

        if (isset($filters['application_status'])) {
            $currentStatusIds = collect([]);
            foreach ($filters['application_status'] as $currentStatus) {
                $ids = surveyApplicants::where('survey_id', $surveyDetails->id)->where('application_status', $currentStatus)->get()->pluck('profile_id');
            }


            $currentStatusIds = $currentStatusIds->merge($ids);

            $isFilterAble = true;
            $profileIds = $profileIds->merge($currentStatusIds);
        }


        if (isset($filters['city']) || isset($filters['age']) || isset($filters['gender'])) {
            $Ids = surveyApplicants::where('survey_id', $surveyDetails->id);
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

        if (isset($filters['gender'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['gender'] as $gender) {
                    $query->orWhere('gender', 'LIKE', $gender);
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
        if ($isFilterAble)
            return ['profile_id' => $profileIds, 'type' => false];
        else
            return ['profile_id' => $profileIds, 'type' => true];
    }

    public function getFilterParameters($survey_id, Request $request)
    {

        $filters = $request->input('filter');

        $gender = [['key' => 'Male', 'value' => 'Male'], ['key' => 'Female', 'value' => 'Female'], ['key' => 'Others', 'value' => 'Others']];
        $age = [['key' => 'gen-z', 'value' => 'Gen-Z'], ['key' => 'gen-x', 'value' => 'Gen-X'], ['key' => 'millenials', 'value' => 'Millenials'], ['key' => 'yold', 'value' => 'YOld']];

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
}
