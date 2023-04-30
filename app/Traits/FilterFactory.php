<?php


namespace App\Traits;

use Illuminate\Support\Collection;
use App\Helper;

trait FilterFactory
{
    public function getFilters($filters, $collaborateId)
    {
        $gender = ['Male', 'Female', 'Other'];
        $age = Helper::getGenerationFilter('string');
        $currentStatus = [0, 1, 2, 3];
        $userType = ['Expert', 'Consumer'];
        $sensoryTrained = ["Yes", "No"];
        $superTaster = ["SuperTaster", "Normal"];
        $applicants = \DB::table('collaborate_applicants')->where('collaborate_id', $collaborateId)->get();
        $city = [];
        $profile = [];
        $hometown = [];
        $current_city = [];
        foreach ($applicants as $applicant) {
            if (isset($applicant->city)) {
                if (!in_array($applicant->city, $city))
                    $city[] = $applicant->city;
            }

            if (isset($applicant->hometown)) {
                if (!in_array($applicant->hometown, $hometown))
                    $hometown[] = $applicant->hometown;
            }

            if (isset($applicant->current_city)) {
                if (!in_array($applicant->current_city, $current_city))
                    $current_city[] = $applicant->current_city;
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
        if (count($filters)) {
            foreach ($filters as $filter) {
                if ($filter == 'gender')
                    $data['gender'] = $gender;
                if ($filter == 'age')
                    $data['age'] = $age;
                if ($filter == 'city')
                    $data['city'] = $city;
                if ($filter == 'current_status')
                    $data['current_status'] = $currentStatus;
                if ($filter == 'profile')
                    $data['profile'] = $profile;
                if ($filter == 'hometown')
                    $data['hometown'] = $hometown;
                if ($filter == 'current_city')
                    $data['current_city'] = $current_city;
                if ($filter == 'super_taster')
                    $data['super_taster'] = $superTaster;
                if ($filter == 'user_type')
                    $data['user_type'] = $userType;
                if ($filter == 'sensory_trained')
                    $data['sensory_trained'] = $sensoryTrained;
            }
        } else {
            $data = ['gender' => $gender, 'age' => $age, 'city' => $city, 'current_status' => $currentStatus, 'profile' => $profile, 'hometown' => $hometown, 'current_city' => $current_city, "sensory_trained" => $sensoryTrained, "user_type" => $userType, "super_taster" => $superTaster];
        }
        return $data;
    }

    public function getFilteredProfile($filters, $collaborateId, $batchId = null)
    {
        $profileIds = new Collection([]);
        if ($profileIds->count() == 0 && isset($filters['profile_id'])) {
            $filterProfile = [];
            foreach ($filters['profile_id'] as $filter) {
                $filterProfile[] = (int)$filter;
            }
            $profileIds = $profileIds->merge($filterProfile);
        }
        if (isset($filters['current_status']) && !is_null($batchId)) {
            $currentStatusIds = new Collection([]);
            foreach ($filters['current_status'] as $currentStatus) {
                if ($currentStatus == 0 || $currentStatus == 1) {
                    if ($profileIds->count() > 0)
                        $ids = \DB::table('collaborate_batches_assign')->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)
                            ->whereIn('profile_id', $profileIds)->where('begin_tasting', $currentStatus)->get()->pluck('profile_id');
                    else
                        $ids = \DB::table('collaborate_applicants')->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)
                            ->where('begin_tasting', $currentStatus)->get()->pluck('profile_id');
                } else {
                    if ($profileIds->count() > 0)
                        $ids = \DB::table('collaborate_tasting_user_review')->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)
                            ->whereIn('profile_id', $profileIds)->where('current_status', $currentStatus)->get()->pluck('profile_id');
                    else
                        $ids = \DB::table('collaborate_tasting_user_review')->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)
                            ->where('current_status', $currentStatus)->get()->pluck('profile_id');
                }
                $currentStatusIds = $currentStatusIds->merge($ids);
            }
            $profileIds = $profileIds->merge($currentStatusIds);
        }

        $Ids = \DB::table('collaborate_applicants')->where('collaborate_id', $collaborateId);


        if (isset($filters['profile'])) {
            $Ids =   $Ids->leftJoin('profile_specializations', 'collaborate_applicants.profile_id', '=', 'profile_specializations.profile_id')
                ->leftJoin('specializations', 'profile_specializations.specialization_id', '=', 'specializations.id');

            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['profile'] as $profile) {
                    $query->orWhere('name', 'LIKE', $profile);
                }
            });
        }

        if (isset($filters['sensory_trained']) || isset($filters['super_taster']) || isset($filters['user_type'])) {
            $Ids =   $Ids->leftJoin('profiles', 'collaborate_applicants.profile_id', '=', 'profiles.id');
        }

        if (isset($filters['city'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['city'] as $city) {
                    $query->orWhere('collaborate_applicants.city', 'LIKE', $city);
                }
            });
        }

        if (isset($filters['age'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['age'] as $age) {
                    $query->orWhere('collaborate_applicants.generation', 'LIKE', $age);
                }
            });
        }

        if (isset($filters['gender'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['gender'] as $gender) {
                    $query->orWhere('collaborate_applicants.gender', 'LIKE', $gender);
                }
            });
        }

        if (isset($filters['hometown'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['hometown'] as $hometown) {
                    $query->orWhere('collaborate_applicants.hometown', 'LIKE', $hometown);
                }
            });
        }

        if (isset($filters['current_city'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['current_city'] as $current_city) {
                    $query->orWhere('collaborate_applicants.current_city', 'LIKE', $current_city);
                }
            });
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


        if ($profileIds->count() > 0) {
            $Ids = $Ids->whereIn('collaborate_applicants.profile_id', $profileIds);
        }

        $Ids = $Ids->get()->pluck('profile_id');
        $profileIds = $profileIds->merge($Ids);

        return $profileIds->toArray();
    }

    public function getSearchedProfile($q, $collaborateId)
    {
        $searchByProfile = \DB::table('collaborate_applicants')
            ->where('collaborate_id', $collaborateId)
            ->whereNUll('company_id')
            ->join('profiles', 'collaborate_applicants.profile_id', '=', 'profiles.id')
            ->join('users', 'profiles.user_id', '=', 'users.id')
            ->where('users.name', 'LIKE', '%' . $q . '%')
            ->pluck('collaborate_applicants.id');

        $searchByCompany = \DB::table('collaborate_applicants')
            ->where('collaborate_id', $collaborateId)
            ->leftJoin('companies', 'collaborate_applicants.company_id', '=', 'companies.id')
            ->where('companies.name', 'LIKE', $q . '%')
            ->pluck('collaborate_applicants.id');
        return $searchByProfile->merge($searchByCompany);
    }
    public function sortApplicants($sortBy, $applications, $collabId)
    {
        $key = array_keys($sortBy)[0];
        $value = $sortBy[$key];
        if ($key == 'name') {
            $userNames = $this->getUserNames($collabId);
            $companyNames = $this->getCompanyNames($collabId);
            $users = $userNames->merge($companyNames);
            if ($value == 'asc')
                $order = array_column($users->sortBy('name')->values()->all(), 'id');
            else
                $order = array_column($users->sortByDesc('name')->values()->all(), 'id');
            $placeholders = implode(',', array_fill(0, count($order), '?'));
            return $applications->orderByRaw("field(collaborate_applicants.id,{$placeholders})", $order)
                ->select('collaborate_applicants.*');
        }
        return $applications->orderBy('collaborate_applicants.created_at', $value)->select('collaborate_applicants.*');
    }
    private function getCompanyNames($id)
    {
        return \App\Collaborate\Applicant::where('collaborate_id', $id)
            ->leftJoin('companies', function ($q) {
                $q->on('collaborate_applicants.company_id', '=', 'companies.id');
            })->where('collaborate_applicants.company_id', '!=', null)
            ->select('companies.name AS name', 'collaborate_applicants.id')
            ->get();
    }

    private function getUserNames($id)
    {
        return \App\Collaborate\Applicant::where('collaborate_id', $id)
            ->leftJoin('profiles AS p', function ($q) {
                $q->on('collaborate_applicants.profile_id', '=', 'p.id')
                    ->where('collaborate_applicants.company_id', '=', null);
            })->leftJoin('users', 'p.user_id', '=', 'users.id')->where('users.name', '!=', null)
            ->select('users.name as name', 'collaborate_applicants.id')
            ->get();
    }


   
}
