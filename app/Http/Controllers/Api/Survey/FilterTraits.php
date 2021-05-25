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

    public function getFilterParameters($survey_id,Request $request){
        
        $filters = $request->input('filter');

        $gender = [['key'=>'Male', 'value' => 'Male'],['key' => 'Female', 'value' =>'Female'],['key'=>'Others', 'value' =>'Others']];
        $age = [['key'=>'gen-z', 'value' => 'Gen-Z'],['key'=> 'gen-x', 'value' => 'Gen-X'],['key'=>'millenials', 'value' => 'Millenials'],['key'=>'yold', 'value' =>'YOld']];

        $currentStatus = [["key" => 1, "value" => 'incomplete'],['key'=>2 , 'value' => "completed"]];
        $applicants = \DB::table('survey_applicants')->where('survey_id',$survey_id)->get();
        $city = [];
        $i = 0;
        foreach ($applicants as $applicant)
        {
            if(isset($applicant->city))
            {
                if(!in_array($applicant->city,$city))
                    $city[$i]['key'] = $applicant->city;
                    $city[$i]['value'] = $applicant->city;
                    $i++;
            }
        }
        $data = [];

        if(!empty($filters) && is_array($filters))
        {
            foreach ($filters as $filter)
            {
                if($filter == 'gender')
                    $data['gender'] = $gender;
                if($filter == 'age')
                    $data['age'] = $age;
                if($filter == 'city')
                    $data['city'] = $city;
                // if($filter == 'application_status')
                    // $data['application_status'] = $currentStatus;
            }
        }
        else
        {
            $data = ['gender'=>$gender,'age'=>$age,'city'=>$city
            // ,'application_status'=>$currentStatus
        ];
        }
        $this->model = $data;

        return $this->sendResponse();

    }
}
