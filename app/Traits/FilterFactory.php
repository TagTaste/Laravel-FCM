<?php


namespace App\Traits;

use Illuminate\Support\Collection;
use App\Helper;
use Carbon\Carbon;
use App\Collaborate\Review;
use App\Collaborate\Applicant;
use App\Recipe\Profile;
use App\Profile\Allergen;

trait FilterFactory
{
    public function getFilters($filters, $collaborateId, $batchId = null)
    {
        $gender = ['Male', 'Female', 'Other'];
        $age = Helper::getGenerationFilter('string');
        $currentStatus = [0, 1, 2, 3];
        $userType = ['Expert', 'Consumer'];
        $sensoryTrained = ["Yes", "No"];
        $superTaster = ["SuperTaster", "Normal"];
        $collabApplicants = Applicant::where('collaborate_id', $collaborateId);
        $applicants = $collabApplicants->get();

        $city = array_unique(array_filter($applicants->pluck('city')->toArray()));
        $city = array_values($city);

        $hometown = array_unique(array_filter($applicants->pluck('hometown')->toArray()));
        $hometown = array_values($hometown);

        $current_city = array_unique(array_filter($applicants->pluck('current_city')->toArray()));
        $current_city = array_values($current_city);
        
        // $city = [];
        $profile = [];
        // $hometown = [];
        // $current_city = [];
        // foreach ($applicants as $applicant) {
        //     if (isset($applicant->city)) {
        //         if (!in_array($applicant->city, $city))
        //             $city[] = $applicant->city;
        //     }

        //     if (isset($applicant->hometown)) {
        //         if (!in_array($applicant->hometown, $hometown))
        //             $hometown[] = $applicant->hometown;
        //     }

        //     if (isset($applicant->current_city)) {
        //         if (!in_array($applicant->current_city, $current_city))
        //             $current_city[] = $applicant->current_city;
        //     }

        //     $specializations = \DB::table('profiles')
        //         ->leftJoin('profile_specializations', 'profiles.id', '=', 'profile_specializations.profile_id')
        //         ->leftJoin('specializations', 'specializations.id', '=', 'profile_specializations.specialization_id')
        //         ->where('profiles.id', $applicant->profile_id)
        //         ->pluck('name');
        //     foreach ($specializations as $specialization) {
        //         if (!in_array($specialization, $profile) && $specialization != null)
        //             $profile[] = $specialization;
        //     }
        // }

        // profile specializations
        $specializations = \DB::table('profiles')
        ->leftJoin('profile_specializations', 'profiles.id', '=', 'profile_specializations.profile_id')
        ->leftJoin('specializations', 'specializations.id', '=', 'profile_specializations.specialization_id');

        $query = clone $specializations;
        $profile = $query->whereIn('profiles.id', $applicants->pluck('profile_id'))->groupBy('name')->pluck('name')->toArray();
        $profile = array_values(array_filter($profile));

        $request = request();

        if($request->is('*/v1/*'))
        {
            // applied Filters
            if(isset($batchId)){ // product applicants filters
                $filteredProfileIds = $this->getFilterProfileIds($filters, $collaborateId, $batchId)['profile_id']->toArray();
        
                $current_status = $request->current_status; 
                $beginTasting = \DB::table('collaborate_batches_assign')->where('collaborate_id', $collaborateId)->where('batch_id', $batchId);
                // for completed and in-progress status
                $currentStatus = \DB::table('collaborate_tasting_user_review')->where('collaborate_id', $collaborateId)->where('batch_id', $batchId);

                $collabProfileIds = $collabApplicants->whereNotNull('shortlisted_at')->whereNull('rejected_at')->pluck('profile_id')->toArray();
                $collabProfileIds = array_values(array_intersect($collabProfileIds, $beginTasting->pluck('profile_id')->toArray()));

                if(isset($current_status) && ($current_status == config("constant.COLLABORATE_CURRENT_STATUS.TO_BE_NOTIFIED")) || ($current_status == config("constant.COLLABORATE_CURRENT_STATUS.NOTIFIED"))){
                    $ids1 = $beginTasting->where('begin_tasting', $current_status)->pluck('profile_id')->toArray();
                    $ids2 = $currentStatus->pluck('profile_id')->unique()->toArray();

                    $statusFilteredIds = array_diff($ids1, $ids2);
                    $collabProfileIds = array_values(array_intersect($collabProfileIds, $statusFilteredIds));
                } else if(isset($current_status) && ($current_status == config("constant.COLLABORATE_CURRENT_STATUS.INPROGRESS")) || ($current_status == config("constant.COLLABORATE_CURRENT_STATUS.COMPLETED"))){
                    $statusFilteredIds = $currentStatus->where('current_status', $current_status)->pluck('profile_id')->unique()->toArray();
                    $collabProfileIds = array_values(array_intersect($collabProfileIds, $statusFilteredIds));
                }

                $filteredProfileIds = array_values(array_intersect($collabProfileIds, $filteredProfileIds));
                $filteredProfileIds = isset($filters) && !empty($filters) ? $filteredProfileIds : $collabProfileIds;
            } else {
                $current_state = $request->state;
                if(isset($current_state) && $current_state == config("constant.COLLABORATE_APPLICANT_STATE.ACTIVE")){
                    $collabProfileIds = $collabApplicants->whereNotNull('shortlisted_at')->whereNull('rejected_at')->pluck('profile_id')->toArray();
                } else if(isset($current_state) && $current_state == config("constant.COLLABORATE_APPLICANT_STATE.REJECTED")){
                    $collabProfileIds = $collabApplicants->whereNotNull('collaborate_applicants.rejected_at')->pluck('profile_id')->toArray();
                } 

                $filteredProfileIds = $this->getFilteredProfile($filters, $collaborateId);
                $filteredProfileIds = array_values(array_intersect($collabProfileIds, $filteredProfileIds));
            }
            
            $genderCounts = $this->getCount($collabApplicants, 'gender', $filteredProfileIds);
            $gender = $this->getFieldPairedData($gender, $genderCounts);
            $gender = $this->addEmptyValue($gender, $genderCounts);
            $gender['key'] = 'gender';
            $gender['value'] = 'Gender';

            $ageCounts = $this->getCount($collabApplicants, 'generation', $filteredProfileIds);
            $age = $this->getFieldPairedData($age, $ageCounts);
            $age = $this->addEmptyValue($age, $ageCounts);
            $age['key'] = 'age';
            $age['value'] = 'Generation';

            $cityCounts = $this->getCount($collabApplicants, 'city', $filteredProfileIds);
            $city = $this->getFieldPairedData($city, $cityCounts);
            $city = $this->addEmptyValue($city, $cityCounts);
            $city['key'] = 'city';
            $city['value'] = 'Tasting City';

            // $hometownCounts = $this->getCount($collabApplicants, 'hometown', $filteredProfileIds);
            // $hometown = $this->getFieldPairedData($hometown, $hometownCounts);
            // $hometown['key'] = 'hometown';
            // $hometown['value'] = 'Hometown';

            // $currentCityCounts = $this->getCount($collabApplicants, 'current_city', $filteredProfileIds);
            // $current_city = $this->getFieldPairedData($current_city, $currentCityCounts);
            // $current_city['key'] = 'current_city';
            // $current_city['value'] = 'Current City';

            $profileModel = Profile::whereNull('deleted_at');

            //count of experts
            $userTypeCounts = $this->getCount($profileModel,'is_expert', $filteredProfileIds);
            $userType = $this->getProfileFieldPairedData('Expert', 'Consumer', $userTypeCounts);
            $userType['key'] = 'user_type';
            $userType['value'] = 'User Type';

            //sensory trained or not
            $sensoryTrainedCounts = $this->getCount($profileModel,'is_sensory_trained', $filteredProfileIds);
            $sensoryTrained =  $this->getProfileFieldPairedData('Yes', 'No', $sensoryTrainedCounts);
            $sensoryTrained['key'] = 'sensory_trained';
            $sensoryTrained['value'] = 'Sensory Trained';

            //super taster or not
            $superTasterCounts = $this->getCount($profileModel,'is_tasting_expert', $filteredProfileIds);
            $superTaster = $this->getProfileFieldPairedData('SuperTaster', 'Normal', $superTasterCounts);
            $superTaster['key'] = 'super_taster';
            $superTaster['value'] = 'Super Taster';

            // profile specializations
            $specializationsCount = $specializations->select('name', \DB::raw('COUNT(*) as count'))->whereIn('profiles.id', $filteredProfileIds)->groupBy('name')->pluck('count','name');

            $profile = $this->getFieldPairedData($profile, $specializationsCount);
            $profile['key'] = 'profile';
            $profile['value'] = 'Job Profile';

            // Date filter
            $date['items'] = [['key'=>'start_date', 'value'=>''],['key'=>'end_date', 'value'=>'']];
            $date['type'] = 'date';
            $date['key'] = 'show_interest_date';
            $date['value'] = 'Show Interest Date';
        }

        if(isset($batchId)){ // product applicants filters
            // for to be notified and notified status
            $beginTastingField = \DB::table('collaborate_batches_assign')->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->whereIn('profile_id', $filteredProfileIds)->pluck('begin_tasting','profile_id')->toArray();

            // for completed and in-progress status
            $currentStatusField = \DB::table('collaborate_tasting_user_review')->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->whereIn('profile_id', $filteredProfileIds)->pluck('current_status','profile_id')->toArray();

            //get the perfect status count
            foreach ($currentStatusField as $key => $value) {
                if (array_key_exists($key, $beginTastingField)) {
                    $beginTastingField[$key] = $value;
                }
            }
            $currentStatusCounts = array_count_values($beginTastingField);
            $currentStatus = ["To Be Notified", "Notified", "In Progress", "Completed"];
            foreach($currentStatus as $key => $val)
            {  
                $inner_arr['key'] = $key;
                $inner_arr['value'] = $val;
                $inner_arr['count'] = isset($currentStatusCounts[$key]) ? $currentStatusCounts[$key] : 0;
                unset($currentStatus[$key]);
                $currentStatus['items'][$key] = $inner_arr;
            }
            $currentStatus['key'] = 'current_status';
            $currentStatus['value'] = 'Status';

            // collab allergens
            $allergenData = \DB::table('collaborate_allergens')->join('allergens', 'collaborate_allergens.allergens_id', '=', 'allergens.id')->where('collaborate_allergens.collaborate_id', $collaborateId); 
            $allergenIds = $allergenData->pluck('id')->toArray();
            $allergenIdNames = array_combine($allergenIds, $allergenData->pluck('allergens.name')->toArray());

            $allergenCounts = \DB::table('profiles_allergens')->select('allergens_id', \DB::raw('COUNT(*) as count'))->whereIn('profile_id', $filteredProfileIds)->whereIn('allergens_id', $allergenIds)->groupBy('allergens_id')->pluck('count','allergens_id');
            $allergenItems = [];

            foreach($allergenIdNames as $key => $val)
            {  
                $inner_arr['key'] = $val;
                $inner_arr['value'] = $val;
                $inner_arr['count'] = isset($allergenCounts[$key]) ? $allergenCounts[$key] : 0;
                $allergenItems[$key] = $inner_arr;
            }

            $allergens = [];
            $allergens['key'] = 'allergens';
            $allergens['value'] = 'Allergens';
            $allergens['items'] = array_values($allergenItems);

            if(isset($current_status) && ($current_status == config("constant.COLLABORATE_CURRENT_STATUS.COMPLETED")) || !isset($current_status)){
                // Date filter key and value will be different for product filters
                $date['key'] = 'review_date';
                $date['value'] = 'Review Date';
            }
        }

        //$profile = array_filter($profile);
        $data = [];
        // if (count($filters)) {
        //     foreach ($filters as $filter) {
        //         if ($filter == 'gender')
        //             $data['gender'] = $gender;
        //         if ($filter == 'age')
        //             $data['age'] = $age;
        //         if ($filter == 'city')
        //             $data['city'] = $city;
        //         if ($filter == 'current_status')
        //             $data['current_status'] = $currentStatus;
        //         if ($filter == 'profile')
        //             $data['profile'] = $profile;
        //         if ($filter == 'hometown')
        //             $data['hometown'] = $hometown;
        //         if ($filter == 'current_city')
        //             $data['current_city'] = $current_city;
        //         if ($filter == 'super_taster')
        //             $data['super_taster'] = $superTaster;
        //         if ($filter == 'user_type')
        //             $data['user_type'] = $userType;
        //         if ($filter == 'sensory_trained')
        //             $data['sensory_trained'] = $sensoryTrained;
        //     }
        // } else {

            // product applicants filters
            if($request->is('*/v1/*') && isset($batchId)){
                if(isset($current_status) && ($current_status == config("constant.COLLABORATE_CURRENT_STATUS.COMPLETED")) || !isset($current_status)){
                    $data = [$gender, $age, $city, $currentStatus, $profile, $sensoryTrained,$userType, $allergens, $superTaster, $date];  
                } else {
                    $data = [$gender, $age, $city, $currentStatus, $profile, $sensoryTrained,$userType, $allergens, $superTaster];  
                }
            } else if($request->is('*/v1/*')){  // remove current status in new applicants filters
                $data = [$gender, $age, $city, $profile, $sensoryTrained, $userType, $superTaster, $date];
            } else {
                $data = ['gender' => $gender, 'age' => $age, 'city' => $city, 'current_status' => $currentStatus, 'profile' => $profile, 'hometown' => $hometown, 'current_city' => $current_city, "sensory_trained" => $sensoryTrained, "user_type" => $userType, "super_taster" => $superTaster];
            }
        // }
        return $data;
    }

    public function dashboardFilters($filters, $collaborateId, $version_num, $filterType, $batchId = null)
    {
        $gender = ['Male', 'Female', 'Other'];
        $age = Helper::getGenerationFilter('string');
        $userType = ['Expert', 'Consumer'];
        $sensoryTrained = ["Yes", "No"];
        $superTaster = ["SuperTaster", "Normal"];
        $applicants = Applicant::query()->join('collaborate_tasting_user_review as c1', 'collaborate_applicants.collaborate_id', '=', 'c1.collaborate_id')
            ->join('collaborate_tasting_user_review as c2', 'collaborate_applicants.profile_id', '=', 'c2.profile_id')
            ->select('collaborate_applicants.*')
            ->where('collaborate_applicants.collaborate_id', $collaborateId)
            ->where('c1.current_status', 3)
            ->distinct()->get();
        $city = $applicants->pluck('city')->toArray();
        $city = array_unique(array_filter($city));
        $city = array_values($city);

        // profile specializations
        $specializations = \DB::table('profiles')
        ->leftJoin('profile_specializations', 'profiles.id', '=', 'profile_specializations.profile_id')
        ->leftJoin('specializations', 'specializations.id', '=', 'profile_specializations.specialization_id');

        $query = clone $specializations;
        $profile = $query->whereIn('profiles.id', $applicants->pluck('profile_id'))->groupBy('name')->pluck('name')->toArray();
        $profile = array_values(array_filter($profile));
        
        $data = [];
        if (isset($version_num) && !empty($version_num) && ($filterType == 'dashboard_filters') || ($filterType == 'dashboard_product_filters'))
        {
            $savedFilter = \DB::table('collaborate_question_filters')->where('collaborate_id', $collaborateId)->whereNull('deleted_at')->first();
            $questions_count = 0;
            if(!is_null($savedFilter))
            {   
                $headers = json_decode($savedFilter->value, true);
                foreach($headers as $header)
                {
                    $questions_count += count($header['questions']);
                }
            }

            switch ($questions_count) {
                case 0:
                    $que_val = '+ Add Questions';
                    break;
                case 1:
                    $que_val = 'Question';
                    break;
                default:
                    $que_val = 'Questions';
                    break;
            }
            $question_filter = [['value' => $que_val, 'count' => $questions_count]];
            if($version_num == 'v2')
            {
                $question_filter = [['key' => 'question','value' => $que_val, 'count' => $questions_count]];
            }
        }

        if (isset($version_num) && (($version_num == 'v2' && $filterType == 'dashboard_filters') || ($version_num == 'v1' && $filterType == 'graph_filters') || $filterType == 'dashboard_product_filters'))
        {
            if($filterType == 'dashboard_product_filters')
            {
                $filteredData = $this->getFilterProfileIds($filters, $collaborateId);
                $filteredProfileIds = $filteredData['profile_id']->toArray();
                $completedProfileIds = Review::where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->where('current_status', 3)->distinct()->pluck('profile_id')->toArray();
                
                if($filteredData['type'] == true)
                {
                    $profileIds = array_values(array_diff($completedProfileIds, $filteredProfileIds));
                }
                else
                {
                    $filteredProfileIds = array_values(array_intersect($completedProfileIds, $filteredProfileIds));
                    $profileIds = isset($filters) && !empty($filters) ? $filteredProfileIds : $completedProfileIds;
                }

                $collabApplicants = Applicant::where('collaborate_id', $collaborateId);
                $profileModel = Profile::whereNull('deleted_at');

                // get counts of fields
                $genderCounts = $this->getCount($collabApplicants, 'gender', $profileIds);
                $ageCounts = $this->getCount($collabApplicants, 'generation', $profileIds);
                $cityCounts = $this->getCount($collabApplicants, 'city', $profileIds);
                $userTypeCounts = $this->getCount($profileModel,'is_expert', $profileIds);
                $sensoryTrainedCounts = $this->getCount($profileModel,'is_sensory_trained', $profileIds, 'true');
                $superTasterCounts = $this->getCount($profileModel,'is_tasting_expert', $profileIds, 'true');

                // get values of fields
                $genderData = $this->getFieldPairedData($gender, $genderCounts);
                $genderData = $this->addEmptyValue($genderData, $genderCounts);
                $ageData = $this->getFieldPairedData($age, $ageCounts);
                $ageData = $this->addEmptyValue($ageData, $ageCounts);
                $cityData = $this->getFieldPairedData($city, $cityCounts);
                $cityData = $this->addEmptyValue($cityData, $cityCounts);
                $userTypeData = $this->getProfileFieldPairedData('Expert', 'Consumer', $userTypeCounts);
                $sensoryTrainedData =  $this->getProfileFieldPairedData('Yes', 'No', $sensoryTrainedCounts);
                $superTasterData = $this->getProfileFieldPairedData('SuperTaster', 'Normal', $superTasterCounts);

                // Date filter
                $date['items'] = [['key'=>'start_date', 'value'=>''],['key'=>'end_date', 'value'=>'']];
                $date['type'] = 'date';
                $date['key'] = 'review_date';
                $date['value'] = 'Review Date';
            } else {
                // get values of fields
                $genderData = $this->getFieldPairedData($gender);
                $genderData = $this->addEmptyValue($genderData);
                $ageData = $this->getFieldPairedData($age);
                $ageData = $this->addEmptyValue($ageData);
                $cityData = $this->getFieldPairedData($city);
                $cityData = $this->addEmptyValue($cityData);
                $userTypeData = $this->getProfileFieldPairedData('Expert', 'Consumer');
                $sensoryTrainedData =  $this->getProfileFieldPairedData('Yes', 'No');
                $superTasterData = $this->getProfileFieldPairedData('SuperTaster', 'Normal');
            }

            $genderData['key'] = 'gender';
            $genderData['value'] = 'Gender';  

            $ageData['key'] = 'age';
            $ageData['value'] = 'Generation';

            $cityData['key'] = 'city';
            $cityData['value'] = 'Tasting City';

            $userTypeData['key'] = 'user_type';
            $userTypeData['value'] = 'User Type';

            $sensoryTrainedData['key'] = 'sensory_trained';
            $sensoryTrainedData['value'] = 'Sensory Trained';

            $superTasterData['key'] = 'super_taster';
            $superTasterData['value'] = 'Super Taster';

            if($filterType == 'dashboard_filters' || $filterType == 'dashboard_product_filters'){
                $question_filter_values = $question_filter;
                $question_filter = [];
                $question_filter['type'] = 'question_filter';
                $question_filter['key'] = 'question_filter';
                $question_filter['value'] = 'Question Filter';  
                $question_filter['items'] = $question_filter_values;
            }  
            else if($filterType == 'graph_filters'){
                $profile = $this->getFieldPairedData($profile);
                $profile['key'] = 'profile';
                $profile['value'] = 'Profile';
            }
        }
        
        if ($filterType == 'dashboard_filters') {
            if(isset($version_num) && $version_num == 'v1'){
                $data = ['question_filter' =>  $question_filter, 'gender' => $gender, 'age' => $age, 'city' => $city, "user_type" => $userType, "sensory_trained" => $sensoryTrained, "super_taster" => $superTaster];
            } else if(isset($version_num) && $version_num == 'v2') {
                $data = [$question_filter, $genderData, $ageData, $cityData, $userTypeData, $sensoryTrainedData, $superTasterData];
            } else {
                $data = ['gender' => $gender, 'age' => $age, 'city' => $city, "user_type" => $userType, "sensory_trained" => $sensoryTrained, "super_taster" => $superTaster];
            }
        } 
        
        if($filterType == 'graph_filters'){
            if(isset($version_num) && $version_num == 'v1'){
                $data = [$genderData, $ageData, $cityData, $profile, $userTypeData, $sensoryTrainedData, $superTasterData];
            } else {
                $data = ['gender' => $gender, 'age' => $age, 'city' => $city, "user_type" => $userType, 'profile' => $profile, "sensory_trained" => $sensoryTrained, "super_taster" => $superTaster];
            }
        }

        if ($filterType == 'dashboard_product_filters') {
            $data = [$question_filter, $genderData, $ageData, $cityData, $userTypeData, $sensoryTrainedData, $superTasterData, $date];
        }
        
        return $data;
    }

    public function getFilteredProfile($filters, $collaborateId, $batchId = null)
    {
        $version_num = '';
        if(request()->is('*/v1/*')){
            $version_num = 'v1';
        } 

        $profileIds = new Collection([]);
        if ($profileIds->count() == 0 && isset($filters['profile_id'])) {
            $filterProfile = [];
            foreach ($filters['profile_id'] as $filter) {
                if (isset($version_num) && $version_num == 'v1'){
                    $filterProfile[] = (int)$filter['key'];
                } else {
                    $filterProfile[] = (int)$filter;
                }
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

            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['profile'] as $profile) {
                    if (isset($version_num) && $version_num == 'v1'){
                        $query->orWhere('name', 'LIKE', $profile['key']);
                    } else {
                        $query->orWhere('name', 'LIKE', $profile);
                    }
                }
            });
        }

        if (isset($filters['city'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['city'] as $city) {
                    if (isset($version_num) && $version_num == 'v1'){
                        ($city['key'] == "not_defined") ? $query->orWhereNull('collaborate_applicants.city')->orWhere('collaborate_applicants.city','') : $query->orWhere('collaborate_applicants.city', 'LIKE', $city['key']);
                    } else {
                        $query->orWhere('collaborate_applicants.city', 'LIKE', $city);
                    }
                }
            });
        }

        if (isset($filters['age'])) {
            $Ids = $Ids->where(function ($query) use ($filters,  $version_num) {
                foreach ($filters['age'] as $age) {
                    if (isset($version_num) && $version_num == 'v1'){
                        $age = htmlspecialchars_decode($age['key']);
                        ($age == "not_defined") ? $query->orWhereNull('collaborate_applicants.generation')->orWhere('collaborate_applicants.generation','') : $query->orWhere('collaborate_applicants.generation', 'LIKE', $age);
                    } else {
                        $age = htmlspecialchars_decode($age);
                        $query->orWhere('collaborate_applicants.generation', 'LIKE', $age);
                    }
                }
            });
        }

        if (isset($filters['gender'])) {
            $Ids = $Ids->where(function ($query) use ($filters,  $version_num) {
                foreach ($filters['gender'] as $gender) {
                    if (isset($version_num) && $version_num == 'v1'){
                        ($gender['key'] == "not_defined") ? $query->orWhereNull('collaborate_applicants.gender')->orWhere('collaborate_applicants.gender','') : $query->orWhere('collaborate_applicants.gender', 'LIKE', $gender['key']);
                    } else {
                        $query->orWhere('collaborate_applicants.gender', 'LIKE', $gender);
                    }
                }
            });
        }

        if (isset($filters['hometown'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['hometown'] as $hometown) {
                    if (isset($version_num) && $version_num == 'v1'){
                        $query->orWhere('collaborate_applicants.hometown', 'LIKE', $hometown['key']);
                    } else {
                        $query->orWhere('collaborate_applicants.hometown', 'LIKE', $hometown);
                    }
                }
            });
        }

        if (isset($filters['current_city'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['current_city'] as $current_city) {
                    if (isset($version_num) && $version_num == 'v1'){
                        $query->orWhere('collaborate_applicants.current_city', 'LIKE', $current_city['key']);
                    } else {
                        $query->orWhere('collaborate_applicants.current_city', 'LIKE', $current_city);
                    }
                }
            });
        }

        if(isset($filters['show_interest_date'])){
            $start_date = '';
            $end_date = '';
            foreach ($filters['show_interest_date'] as $date) {
                if($date['key'] == 'start_date' && !empty($date['value'])){
                    $start_date = Carbon::parse($date['value'])->startOfDay();
                }else if($date['key'] == 'end_date' && !empty($date['value'])){
                    $end_date = Carbon::parse($date['value'])->endOfDay();                   
                }
            }

            if($start_date != '' && $end_date != ''){
                $Ids = $Ids->whereBetween('collaborate_applicants.shortlisted_at',[$start_date, $end_date]);
            } else if($start_date != '') {
                $Ids = $Ids->where('collaborate_applicants.shortlisted_at','>=',$start_date);
            } else if($end_date != '') {
                $Ids = $Ids->where('collaborate_applicants.shortlisted_at','<=',$end_date);  
            }
        }

        if (isset($filters['sensory_trained']) || isset($filters['super_taster']) || isset($filters['user_type'])) {
            $Ids =   $Ids->leftJoin('profiles', 'collaborate_applicants.profile_id', '=', 'profiles.id');
        }

        if (isset($filters['sensory_trained'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['sensory_trained'] as $sensory) {
                    if ((isset($version_num) && $version_num == 'v1' && $sensory['key'] == 'Yes') || $sensory == 'Yes')
                        $sensory = 1;
                    else
                        $sensory = 0;

                    $query->orWhere('profiles.is_sensory_trained', $sensory);
                }
            });
        }

        if (isset($filters['super_taster'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['super_taster'] as $superTaster) {
                    if ((isset($version_num) && $version_num == 'v1' && $superTaster['key'] == 'SuperTaster') || $superTaster == 'SuperTaster'){
                        $superTaster = 1;
                    } else {
                        $superTaster = 0;
                    }
                    $query->orWhere('profiles.is_tasting_expert', $superTaster);
                }
            });
        }

        if (isset($filters['user_type'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['user_type'] as $userType) {
                    if ((isset($version_num) && $version_num == 'v1' && $userType['key'] == 'Expert') || $userType == 'Expert')
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

    public function getFilterProfileIds($filters, $collaborateId, $batchId = null)
    {
        $version_num = '';
        if(request()->is('*/v2/*')){
            $version_num = 'v2';
        } else if(request()->is('*/v1/*')){
            $version_num = 'v1';
        }

        $profileIds = new Collection([]);
        $isFilterAble = false;
        if ($profileIds->count() == 0 && isset($filters['include_profile_id'])) {
            $filterProfile = [];
            foreach ($filters['include_profile_id'] as $filter) {
                $isFilterAble = true;
                $filterProfile[] = (is_string($filter) && !isset($filter['key'])) ? (int)$filter : (int)$filter['key'];
            }
            $profileIds = $profileIds->merge($filterProfile);
        }
        
        if (isset($filters['city']) || isset($filters['age']) || isset($filters['gender'])  || isset($filters['sensory_trained']) || isset($filters['super_taster']) || isset($filters['user_type']) || isset($filters['current_status']) || isset($filters['question_filter']) || isset($filters['current_city']) || isset($filters['hometown']) || isset($filters['allergens']) || isset($filters['allergens']) || isset($filters['profile']) || isset($filters['include_profile_id']) || isset($filters['review_date'])) {
            $Ids = \DB::table('collaborate_applicants')->where('collaborate_applicants.collaborate_id', $collaborateId);
        }


        if(isset($filters['review_date'])){
            $start_date = '';
            $end_date = '';
            foreach ($filters['review_date'] as $date) {
                if($date['key'] == 'start_date' && !empty($date['value'])){
                    $start_date = Carbon::parse($date['value'])->startOfDay();
                }else if($date['key'] == 'end_date' && !empty($date['value'])){
                    $end_date = Carbon::parse($date['value'])->endOfDay();                   
                }
            }
            $Ids = $Ids->leftJoin('collaborate_batches_assign', function($join) {
                $join->on('collaborate_applicants.profile_id', '=', 'collaborate_batches_assign.profile_id')
                     ->on('collaborate_applicants.collaborate_id', '=', 'collaborate_batches_assign.collaborate_id');
            })->where('collaborate_batches_assign.batch_id', $batchId)->where('current_status', 3);

            if($start_date != '' && $end_date != ''){
                $Ids = $Ids->whereBetween('collaborate_batches_assign.end_review',[$start_date, $end_date]);
            } else if($start_date != '') {
                $Ids = $Ids->where('collaborate_batches_assign.end_review','>=',$start_date);
            } else if($end_date != '') {
                $Ids = $Ids->where('collaborate_batches_assign.end_review','<=',$end_date);  
            }
        }
        
        if (isset($filters['city'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['city'] as $city) {
                    $city = (is_string($city) && !isset($city['key'])) ? $city : $city['key'];
                    ($city == "not_defined") ? $query->orWhereNull('collaborate_applicants.city')->orWhere('collaborate_applicants.city','') : $query->orWhere('collaborate_applicants.city', 'LIKE', $city);
                }
            });
        }
        
        if (isset($filters['age'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['age'] as $age) {
                    $age = (is_string($age) && !isset($age['key'])) ? htmlspecialchars_decode($age) : htmlspecialchars_decode($age['key']);
                    ($age == "not_defined") ? $query->orWhereNull('collaborate_applicants.generation')->orWhere('collaborate_applicants.generation','') : $query->orWhere('collaborate_applicants.generation', 'LIKE', $age);
                }
            });
        }

        if (isset($filters['gender'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['gender'] as $gender) {
                    $gender = (is_string($gender) && !isset($gender['key'])) ? $gender : $gender['key'];
                    ($gender == "not_defined") ? $query->orWhereNull('collaborate_applicants.gender')->orWhere('collaborate_applicants.gender','') : $query->orWhere('collaborate_applicants.gender', 'LIKE', $gender);
                }
            });
        }

        if (isset($filters['profile'])) {
            $Ids =   $Ids->leftJoin('profile_specializations', 'collaborate_applicants.profile_id', '=', 'profile_specializations.profile_id')
                ->leftJoin('specializations', 'profile_specializations.specialization_id', '=', 'specializations.id');

            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['profile'] as $profile) {
                    $query->orWhere('name', 'LIKE', (is_string($profile) && !isset($profile['key'])) ? $profile : $profile['key']);
                }
            });
        }

        if (isset($filters['hometown'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['hometown'] as $hometown) {
                    // if (isset($version_num) && ($version_num == 'v1' || $version_num == 'v2')){
                        $query->orWhere('collaborate_applicants.hometown', 'LIKE', (is_string($hometown) && !isset($hometown['key'])) ? $hometown : $hometown['key']);
                    // } else {
                    //     $query->orWhere('collaborate_applicants.hometown', 'LIKE', $hometown);
                    // }
                }
            });
        }

        if (isset($filters['current_city'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['current_city'] as $current_city) {
                    // if (isset($version_num) && ($version_num == 'v1' || $version_num == 'v2')){
                        $query->orWhere('collaborate_applicants.current_city', 'LIKE', (is_string($current_city) && !isset($current_city['key'])) ? $current_city : $current_city['key']);
                    // } else {
                    //     $query->orWhere('collaborate_applicants.current_city', 'LIKE', $current_city);
                    // }
                }
            });
        }

        if (isset($filters['sensory_trained']) || isset($filters['super_taster']) || isset($filters['user_type'])) {
            $Ids =   $Ids->leftJoin('profiles', 'collaborate_applicants.profile_id', '=', 'profiles.id');
        }

        if (isset($filters['sensory_trained'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['sensory_trained'] as $sensory) {
                    if ((isset($sensory['key']) && $sensory['key'] == 'Yes') || (is_string($sensory) && !isset($sensory['key']) && $sensory == 'Yes'))
                        $sensory = 1;
                    else
                        $sensory = 0;
                    $query->orWhere('profiles.is_sensory_trained', $sensory);
                }
            });
        }

        if (isset($filters['super_taster'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['super_taster'] as $superTaster) {
                    if ((isset($superTaster['key']) && $superTaster['key']  == 'SuperTaster') || (is_string($superTaster) && !isset($superTaster['key']) && $superTaster == 'SuperTaster'))
                        $superTaster = 1;
                    else
                        $superTaster = 0;
                    $query->orWhere('profiles.is_tasting_expert', $superTaster);
                }
            });
        }

        if (isset($filters['user_type'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['user_type'] as $userType) {
                    if ((isset($userType['key']) && $userType['key'] == 'Expert') || (is_string($userType) && !isset($userType['key']) && $userType == 'Expert'))
                        $userType = 1;
                    else
                        $userType = 0;
                    $query->orWhere('profiles.is_expert', $userType);
                }
            });
        }

        //apply filter on question's options
        if (isset($version_num) && isset($filters['question_filter']))
        {
            $ques_filter = ['profile_id' => request()->user()->profile->id, 'collaborate_id'=> $collaborateId, 'value'=> json_encode($filters['question_filter']), 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];

            \DB::table('collaborate_question_filters')->where('collaborate_id', $collaborateId)->updateOrInsert(['collaborate_id'=> $collaborateId], $ques_filter); 

            if(!empty($filters['question_filter']))
            {
                $header_ids = collect($filters['question_filter'])->pluck('id')->toArray();
                $entered_count = 0;
    
                foreach($header_ids as $key => $header)
                {
                    foreach($filters['question_filter'][$key]['questions'] as $question)
                    {
                        $question_filtered_data = Review::whereIn('tasting_header_id',$header_ids)->where('collaborate_id',$collaborateId)->whereIn('profile_id', $Ids->distinct()->pluck('collaborate_applicants.profile_id')->toArray())->where('question_id',$question["id"]);

                        if(!empty($question["option"]))
                        {
                            $option_ids = collect($question["option"])->pluck('id');
                            $question_filtered_data = $question_filtered_data->whereIn('collaborate_tasting_user_review.leaf_id',$option_ids);
                        }
                        if($entered_count == 0)
                        {   
                            $profile_ids = $question_filtered_data->distinct()->pluck('profile_id')->toArray();
                        }
                        else
                        {
                            $profile_ids = array_intersect($question_filtered_data->distinct()->pluck('profile_id')->toArray(), $profile_ids);
                        }  
                        $entered_count++;
                    }
                }

                $Ids = $Ids->whereIn('collaborate_applicants.profile_id', $profile_ids);
            }
        }

        if (isset($filters['current_status']) && !is_null($batchId)) {
            $currentStatusIds = new Collection([]);
            foreach ($filters['current_status'] as $currentStatus) {
                $currentStatus = is_string($currentStatus) && !isset($currentStatus['key']) ? $currentStatus : $currentStatus['key'];
                if ($currentStatus == 0 || $currentStatus == 1) {
                    // $ids = \DB::table('collaborate_batches_assign')->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->where('begin_tasting', $currentStatus)->get()->pluck('profile_id')->unique();
                    $ids = \DB::table('collaborate_batches_assign as c1')->select('c1.profile_id')->join('collaborate_applicants as c2', function ($join) {
                        $join->on('c1.collaborate_id', '=', 'c2.collaborate_id')
                            ->on('c1.profile_id', '=', 'c2.profile_id');
                    })->where('c1.collaborate_id', $collaborateId)->where('c1.batch_id', $batchId)->where('c1.begin_tasting', $currentStatus)->whereNotNull('c2.shortlisted_at')->distinct()->pluck('c1.profile_id');
                    $ids2 = \DB::table('collaborate_tasting_user_review')->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->get()->pluck('profile_id')->unique();                   
                    $ids = $ids->diff($ids2);
                } else {
                    $ids = \DB::table('collaborate_tasting_user_review')->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->where('current_status', $currentStatus)->get()->pluck('profile_id')->unique();
                }
                $currentStatusIds = $currentStatusIds->merge($ids);
            }
            $Ids = $Ids->whereIn('collaborate_applicants.profile_id', $currentStatusIds);
        }

        if (isset($filters['allergens'])) {
            $allergen = Allergen::where('name', $filters['allergens'])->first();
            $allergenProfileIds = $allergen->profile()->whereIn('profile_id', $Ids->get()->pluck('profile_id'))->pluck('profile_id');
            $Ids = $Ids->whereIn('collaborate_applicants.profile_id', $allergenProfileIds);
        }
        
        // if ($profileIds->count() > 0 && isset($Ids)) {
        //     $Ids = $Ids->whereIn('collaborate_applicants.profile_id', $profileIds);
        // }

        if (isset($Ids)) {
            $isFilterAble = true;
            $Ids = $Ids->get()->pluck('profile_id');
            $profileIds = $profileIds->merge($Ids);
        }

        if ($profileIds->count() > 0 && isset($filters['exclude_profile_id'])) {
            $filterNotProfileIds = [];
            foreach ($filters['exclude_profile_id'] as $filter) {
                $isFilterAble = true;
                $filterNotProfileIds[] = (isset($version_num) && $version_num == 'v2') ? (int)$filter['key'] : (int)$filter;
            }
            $profileIds = $profileIds->diff($filterNotProfileIds);
        } else if (isset($filters['exclude_profile_id'])) {
            $isFilterAble = false;
            $excludeAble = false;
            $filterNotProfileIds = [];
            foreach ($filters['exclude_profile_id'] as $filter) {
                $isFilterAble = false;
                $excludeAble = true;
                $filterNotProfileIds[] = (is_string($filter) && !isset($filter['key'])) ? (int)$filter : (int)$filter['key'];
            }
            $profileIds = $profileIds->merge($filterNotProfileIds);
        }

        if ($isFilterAble)
            return ['profile_id' => $profileIds, 'type' => false]; //data for these profile ids only 
        else
            return ['profile_id' => $profileIds, 'type' => true]; //these profile ids will be excluded from total completed reviews

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

    public function getCount($model, $field, $profileIds)
    {
        $query = clone $model;
        $table = $query->getModel()->getTable();
        $query->selectRaw("CASE WHEN $field IS NULL THEN 'not_defined' ELSE $field END AS $field")->selectRaw('COUNT(*) as count');
        
        if($table == 'collaborate_applicants'){
            $query = $query->whereIn('profile_id', $profileIds);
        } else {
            $query->whereIn('id', $profileIds);
        }

        return $query->groupBy($field)->pluck('count', $field);
    }

    public function getFieldPairedData($field, $fieldCounts = null)
    {
        if(empty($field))
        {
            $field['items'] = [];
            return $field;
        }

        foreach($field as $key => $val)
        {  
            unset($field[$key]);
            $inner_arr['key'] = $val;
            $inner_arr['value'] = $val;
            if(isset($fieldCounts) && !empty($fieldCounts)){
                $inner_arr['count'] = isset($fieldCounts[$val]) ? $fieldCounts[$val] : 0;
            }
            $field['items'][$key] = $inner_arr;
        }
       
        return $field;
    }

    public function addEmptyValue($field, $fieldCounts = null){
        $inner_arr['key'] = "not_defined";
        $inner_arr['value'] = "Didn't mention";
        if(isset($fieldCounts) && !empty($fieldCounts)){
            $inner_arr['count'] = isset($fieldCounts["not_defined"]) ? $fieldCounts["not_defined"] : (isset($fieldCounts[""]) ? $fieldCounts[""] : 0);
        }
        array_push($field['items'], $inner_arr);
        return $field;
    }

    public function getProfileFieldPairedData($val1, $val2, $fieldCounts = null)
    {
        $field = [];
        $field['items'] = [['key' => $val1, 'value' => $val1], ['key' => $val2, 'value' => $val2]];
        if(isset($fieldCounts) && !empty($fieldCounts)){
            $field['items'] = [['key' => $val1, 'value' => $val1, 'count' => isset($fieldCounts[1]) ? $fieldCounts[1] : 0], ['key' => $val2, 'value' => $val2, 'count' => isset($fieldCounts[0]) ? $fieldCounts[0] : 0]];
        }
        
        return $field;
    }
   
}
