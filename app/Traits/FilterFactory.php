<?php


namespace App\Traits;

use Illuminate\Support\Collection;

trait FilterFactory
{
    public function getFilters($filters, $collaborateId)
    {
        $gender = ['Male','Female','Other'];
        $age = ['< 18','18 - 35','35 - 55','55 - 70','> 70'];
        $currentStatus = [0,1,2,3];
        $applicants = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->get();
        $city = [];
        $profile = [];
        foreach ($applicants as $applicant)
        {
            if(isset($applicant->city))
            {
                if(!in_array($applicant->city,$city))
                    $city[] = $applicant->city;
            }
            $specializations = \DB::table('profiles')
                                 ->leftJoin('profile_specializations','profiles.id','=','profile_specializations.profile_id')
                                 ->leftJoin('specializations','specializations.id','=','profile_specializations.specialization_id')
                                ->where('profiles.id',$applicant->profile_id)
                                ->pluck('name');
            foreach($specializations as $specialization) {
                if(!in_array($specialization,$profile) && $specialization != null)
                    $profile[] = $specialization;
            }
        }
        //$profile = array_filter($profile);
        $sort_by = [
            "Latest First",
            "Oldest First",
            "Name A-Z",
            "Name Z-A"
        ];
        $data = [];
        if(count($filters))
        {
            foreach ($filters as $filter)
            {
                if($filter == 'gender')
                    $data['gender'] = $gender;
                if($filter == 'age')
                    $data['age'] = $age;
                if($filter == 'city')
                    $data['city'] = $city;
                if($filter == 'current_status')
                    $data['current_status'] = $currentStatus;
                if($filter == 'profile')
                    $data['profile'] = $profile;
                if($filter == 'sort_by')
                    $data['sort_by'] = $sort_by;
            }
        }
        else
        {
            $data = ['gender'=>$gender,'age'=>$age,'city'=>$city,'current_status'=>$currentStatus,'profile'=>$profile,'sort_by'=>$sort_by];
        }
        return $data;
    }

    public function getFilteredProfiles($filters, $collaborateId)
    {
        $profileIds = new Collection([]);
        if($profileIds->count() == 0 && isset($filters['profile_id']))
        {
            $filterProfile = [];
            foreach ($filters['profile_id'] as $filter)
            {
                $filterProfile[] = (int)$filter;
            }
            $profileIds = $profileIds->merge($filterProfile);
        }
        if(isset($filters['current_status']) && !is_null($batchId))
        {
            $currentStatusIds = new Collection([]);
            foreach ($filters['current_status'] as $currentStatus)
            {
                if($currentStatus == 0 || $currentStatus == 1)
                {
                    if($profileIds->count() > 0)
                        $ids = \DB::table('collaborate_batches_assign')->where('collaborate_id',$collaborateId)->where('batch_id', $batchId)
                            ->whereIn('profile_id',$profileIds)->where('begin_tasting',$currentStatus)->get()->pluck('profile_id');
                    else
                        $ids = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->where('batch_id', $batchId)
                            ->where('begin_tasting',$currentStatus)->get()->pluck('profile_id');
                }
                else
                {
                    if($profileIds->count() > 0)
                        $ids = \DB::table('collaborate_tasting_user_review')->where('collaborate_id',$collaborateId)->where('batch_id', $batchId)
                            ->whereIn('profile_id',$profileIds)->where('current_status',$currentStatus)->get()->pluck('profile_id');
                    else
                        $ids = \DB::table('collaborate_tasting_user_review')->where('collaborate_id',$collaborateId)->where('batch_id', $batchId)
                            ->where('current_status',$currentStatus)->get()->pluck('profile_id');
                }
                $currentStatusIds = $currentStatusIds->merge($ids);
            }
            $profileIds = $currentStatus;
        }
        if(isset($filters['city']))
        {
            $cityFilterIds = new Collection([]);
            foreach ($filters['city'] as $city)
            {
                if($profileIds->count() > 0)
                    $ids = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->where('city', 'LIKE', $city)
                        ->whereIn('profile_id',$profileIds)->get()->pluck('profile_id');
                else
                    $ids = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->where('city', 'LIKE', $city)->get()->pluck('profile_id');
                $cityFilterIds = $cityFilterIds->merge($ids);
            }
            $profileIds = $cityFilterIds;
        }
        if(isset($filters['age']))
        {
            $ageFilterIds = new Collection([]);
            foreach ($filters['age'] as $age)
            {
                $age = htmlspecialchars_decode($age);
                if($profileIds->count() > 0 )
                    $ids = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->where('age_group', 'LIKE', $age)
                        ->whereIn('profile_id',$profileIds)->get()->pluck('profile_id');
                else
                    $ids = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->where('age_group', 'LIKE', $age)
                        ->get()->pluck('profile_id');
                $ageFilterIds = $ageFilterIds->merge($ids);
            }
            $profileIds = $ageFilterIds;
        }
        if(isset($filters['gender']))
        {
            $genderFilterIds = new Collection([]);
            foreach ($filters['gender'] as $gender)
            {
                if($profileIds->count() > 0 )
                    $ids = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->where('gender', 'LIKE', $gender)
                        ->whereIn('profile_id',$profileIds)->get()->pluck('profile_id');
                else
                    $ids = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->where('gender', 'LIKE', $gender)
                        ->get()->pluck('profile_id');
                $genderFilterIds = $genderFilterIds->merge($ids);
            }
            $profileIds = $genderFilterIds;
        }
        if(isset($filters['profile'])) {
            $profileFilterIds = new Collection([]);
            foreach($filters['profile'] as $profile) {
                if($profileIds->count() > 0) {
                    $ids = \DB::table('collaborate_applicants')
                                    ->where('collaborate_id',$collaborateId)
                                    ->leftJoin('profile_specializations','collaborate_applicants.profile_id','=','profile_specializations.profile_id')
                                    ->leftJoin('specializations','profile_specializations.specialization_id','=','specializations.id')
                                    ->where('name','LIKE',$profile)
                                    ->whereIn('collaborate_applicants.profile_id',$profileIds)
                                    ->get()->pluck('collaborate_applicants.profile_id');
                } else {
                    $ids = \DB::table('collaborate_applicants')
                                    ->where('collaborate_id',$collaborateId)
                                    ->leftJoin('profile_specializations','collaborate_applicants.profile_id','=','profile_specializations.profile_id')
                                    ->leftJoin('specializations','profile_specializations.specialization_id','=','specializations.id')
                                    ->where('name','LIKE',$profile)
                                    ->pluck('collaborate_applicants.profile_id');
                }
                $profileFilterIds = $profileFilterIds->merge($ids);
            }

            $profileIds = $profileFilterIds;
        }
        return $profileIds;
    }
    public function getSearchedProfile($q,$collaborateId)
    {
            $searchByProfile = \DB::table('collaborate_applicants')
                            ->where('collaborate_id',$collaborateId)
                            ->whereNUll('company_id')
                            ->join('profiles','collaborate_applicants.profile_id','=','profiles.id')
                            ->join('users','profiles.user_id','=','users.id')
                            ->where('users.name','LIKE',$q.'%')
                            ->pluck('collaborate_applicants.id');

            $searchByCompany = \DB::table('collaborate_applicants')
                                ->where('collaborate_id',$collaborateId)
                                ->leftJoin('companies','collaborate_applicants.company_id','=','companies.id')
                                ->where('companies.name','LIKE',$q.'%')
                                ->pluck('collaborate_applicants.id');
            return $searchByProfile->merge($searchByCompany);
                                            

    }
    public function sortApplicants($sortBy,$applications,$collabId)
    {
        $key = array_keys($sortBy)[0];
        $value = $sortBy[$key];
        if($key == 'name') {
            $userNames = $this->getUserNames($collabId);
           $companyNames = $this->getCompanyNames($collabId);
            $users = $userNames->merge($companyNames);
            if($value == 'asc')
            $order = array_column($users->sortBy('name')->values()->all(),'id');
            else
            $order = array_column($users->sortByDesc('name')->values()->all(),'id');
            $placeholders = implode(',',array_fill(0, count($order), '?'));
            return $applications->orderByRaw("field(collaborate_applicants.id,{$placeholders})", $order)
                    ->select('collaborate_applicants.*');
        } 
        return $applications->orderBy('collaborate_applicants.created_at',$value)->select('collaborate_applicants.*');
    }
    private function getCompanyNames($id)
    {   
        return \App\Collaborate\Applicant::where('collaborate_id',$id)
        ->leftJoin('companies',function($q){
            $q->on('collaborate_applicants.company_id','=','companies.id')
            ;
        })->where('collaborate_applicants.company_id','!=',null)
        ->select('companies.name AS name','collaborate_applicants.id')
        ->get();
    }

    private function getUserNames($id)
    {   
        return \App\Collaborate\Applicant::where('collaborate_id',$id)
        ->leftJoin('profiles AS p',function($q){
            $q->on('collaborate_applicants.profile_id','=','p.id')
            ->where('collaborate_applicants.company_id','=',null);
        })->leftJoin('users','p.user_id','=','users.id')->where('users.name','!=',null)
        ->select('users.name as name','collaborate_applicants.id')
        ->get();
    }
}