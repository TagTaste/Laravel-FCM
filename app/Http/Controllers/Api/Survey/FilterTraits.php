<?php

namespace App\Http\Controllers\Api\Survey;

use App\surveyApplicants;
use Illuminate\Http\Request;
use App\Helper;
use Carbon\Carbon;
use App\Surveys;
use App\SurveyAnswers;
use App\SurveyAttemptMapping;

trait FilterTraits
{

    public function getProfileIdOfReportFilter($surveyDetails, Request $request, $version_num = '')
    {
        $filters = $request->filters;
        $profileIds = collect([]);

        if ($profileIds->count() == 0 && isset($filters['profile_ids'])) {
            $filterProfile = [];
            foreach ($filters['profile_ids'] as $filter) {
                //$isFilterAble = true;
                $filterProfile[] = (int)$filter;
            }
            $profileIds = $profileIds->merge($filterProfile);
        }

        if (!empty($filters)) {
            $Ids = surveyApplicants::where('survey_id', $surveyDetails->id)
                ->whereNull('survey_applicants.deleted_at');
        }
        
        if (isset($filters['age'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['age'] as $age) {
                    if (isset($version_num) && ($version_num == 'v1' || $version_num == 'v2')){
                        $query->orWhere('generation', 'LIKE', $age['key']);
                    }else{
                    // $age = htmlspecialchars_decode($age);
                        $query->orWhere('generation', 'LIKE', $age);
                    }
                }
            });
        }
        
        
        if (isset($filters['gender'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['gender'] as $gender) {
                    if (isset($version_num) && ($version_num == 'v1' || $version_num == 'v2')){
                        $query->orWhere('survey_applicants.gender', 'LIKE', $gender['key']);
                    }else{
                        $query->orWhere('survey_applicants.gender', 'LIKE', $gender);
                    }
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

        $profileCompleteAttempt = SurveyAttemptMapping::select(['profile_id', 'attempt'])->distinct()->where("survey_id", "=", $surveyDetails->id)->whereNull("deleted_at")->whereNotNull("completion_date")->whereIn('profile_id',$profileIds)->get();

        
        $idsAttemptMapping = [];
        foreach ($profileCompleteAttempt as $pattempt) {
            $idsAttemptMapping[$pattempt->profile_id][] = $pattempt->attempt;
        }

        //apply filter on question's options
        if (isset($filters['question_filter'])) {
            $ques_filter = ['profile_id'=>$request->user()->profile->id, 'surveys_id'=> $surveyDetails['id'], 'value'=> json_encode($filters['question_filter']), 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];

            \DB::table('survey_filters')->where('surveys_id', $surveyDetails['id'])->updateOrInsert(['surveys_id'=> $surveyDetails['id']], $ques_filter);
            
            $idsAttemptMapping = $this->getProfileOfQuestions($filters['question_filter'], $surveyDetails['id'], $idsAttemptMapping);
        }
        
        if(isset($filters['date'])){
            $start_date = '';
            $end_date = '';
            foreach ($filters['date'] as $date) {
                if($date['key'] == 'start_date' && !empty($date['value'])){
                    $start_date = $date['value'];
                    $start_date = Carbon::parse($start_date)->startOfDay();
                }else if($date['key'] == 'end_date' && !empty($date['value'])){
                    $end_date = $date['value'];
                    $end_date = Carbon::parse($end_date)->endOfDay();                   
                }
            }
            
            if($start_date != '' && $end_date != ''){
                $profileCompleteAttempt = SurveyAttemptMapping::select('profile_id','attempt')->distinct()->where('survey_id', $surveyDetails->id)->whereNull('deleted_at')->whereBetween('completion_date',[$start_date, $end_date])->get()->filter(function ($ans) use ($idsAttemptMapping) {
                    return isset($idsAttemptMapping[$ans->profile_id]) ? in_array($ans->attempt, $idsAttemptMapping[$ans->profile_id]) : false;
                });

                $idsAttemptMapping = [];
                foreach ($profileCompleteAttempt as $pattempt) {
                    $idsAttemptMapping[$pattempt->profile_id][] = $pattempt->attempt;
                };

            }else if($start_date != ''){
                $profileCompleteAttempt = SurveyAttemptMapping::select('profile_id','attempt')->distinct()->where('survey_id', $surveyDetails->id)->whereNull('deleted_at')->where('completion_date','>=',$start_date)->get()->filter(function ($ans) use ($idsAttemptMapping) {
                    return isset($idsAttemptMapping[$ans->profile_id]) ? in_array($ans->attempt, $idsAttemptMapping[$ans->profile_id]) : false;
                });

                $idsAttemptMapping = [];
                foreach ($profileCompleteAttempt as $pattempt) {
                    $idsAttemptMapping[$pattempt->profile_id][] = $pattempt->attempt;
                };

            }else if($end_date != ''){
                $profileCompleteAttempt = SurveyAttemptMapping::select('profile_id','attempt')->distinct()->where('survey_id', $surveyDetails->id)->whereNull('deleted_at')->where('completion_date','<=',$end_date)->get()->filter(function ($ans) use ($idsAttemptMapping) {
                    return isset($idsAttemptMapping[$ans->profile_id]) ? in_array($ans->attempt, $idsAttemptMapping[$ans->profile_id]) : false;
                });

                $idsAttemptMapping = [];
                foreach ($profileCompleteAttempt as $pattempt) {
                    $idsAttemptMapping[$pattempt->profile_id][] = $pattempt->attempt;
                };
                
            }           
        }
        
        return $idsAttemptMapping;
        
        // if (isset($isFilterAble) && $isFilterAble)
        //     return ['profile_id' => $profileIds, 'type' => false];
        // else
        //     return ['profile_id' => $profileIds, 'type' => true];
    }

    public function getProfileIdOfFilter($surveyDetails, Request $request, $version_num = '')
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
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['age'] as $age) {
                    if (isset($version_num) && $version_num == 'v1'){
                        $query->orWhere('generation', 'LIKE', $age['key']);
                    }else{
                    // $age = htmlspecialchars_decode($age);
                        $query->orWhere('generation', 'LIKE', $age);
                    }
                }
            });
        }

        if (isset($filters['profile'])) {
            $Ids =   $Ids->leftJoin('profile_specializations', 'survey_applicants.profile_id', '=', 'profile_specializations.profile_id')
                ->leftJoin('specializations', 'profile_specializations.specialization_id', '=', 'specializations.id');

            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['profile'] as $profile) {
                    if (isset($version_num) && $version_num == 'v1'){
                        $query->orWhere('name', 'LIKE', $profile['key']);
                    } else {
                        $query->orWhere('name', 'LIKE', htmlspecialchars_decode($profile));
                    }
                }
            });
        }
        
        if (isset($filters['gender'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['gender'] as $gender) {
                    if (isset($version_num) && $version_num == 'v1'){
                        $query->orWhere('survey_applicants.gender', 'LIKE', $gender['key']);
                    }else{
                        $query->orWhere('survey_applicants.gender', 'LIKE', $gender);
                    }
                }
            });
        }

        //apply filter on question's options
        
        if (isset($filters['question_filter'])) {
            $ques_filter = ['profile_id'=>$request->user()->profile->id, 'surveys_id'=> $surveyDetails['id'], 'value'=> json_encode($filters['question_filter']), 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];

            \DB::table('survey_filters')->where('surveys_id', $surveyDetails['id'])->updateOrInsert(['surveys_id'=> $surveyDetails['id']], $ques_filter);
            
            $queProfileIds = $this->getProfileOfQuestions($filters['question_filter'], $surveyDetails['id']);
            $Ids = $Ids->whereIn('profile_id', $queProfileIds);
        }
        
        if(isset($filters['date'])){
            $start_date = '';
            $end_date = '';
            foreach ($filters['date'] as $date) {
                if($date['key'] == 'start_date' && !empty($date['value'])){
                    $start_date = $date['value'];
                    $start_date = Carbon::parse($start_date)->startOfDay();
                }else if($date['key'] == 'end_date' && !empty($date['value'])){
                    $end_date = $date['value'];
                    $end_date = Carbon::parse($end_date)->endOfDay();                   
                }
            }
            
            if($start_date != '' && $end_date != ''){
                $dateProfileIds = SurveyAttemptMapping::where('survey_id', $surveyDetails->id)->whereNull('deleted_at')->whereBetween('completion_date',[$start_date, $end_date])->get()->pluck('profile_id')->unique();
                $Ids = $Ids->whereIn('profile_id', $dateProfileIds);
            }else if($start_date != ''){
                $dateProfileIds = SurveyAttemptMapping::where('survey_id', $surveyDetails->id)->whereNull('deleted_at')->where('completion_date','>=',$start_date)->get()->pluck('profile_id')->unique();
                $Ids = $Ids->whereIn('profile_id', $dateProfileIds);
            }else if($end_date != ''){
                $dateProfileIds = SurveyAttemptMapping::where('survey_id', $surveyDetails->id)->whereNull('deleted_at')->where('completion_date','<=',$end_date)->get()->pluck('profile_id')->unique();
                $Ids = $Ids->whereIn('profile_id', $dateProfileIds);
            }           
        }
        
        if (isset($filters['application_status'])) {

            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['application_status'] as $status) {
                    if (isset($version_num) && $version_num == 'v1'){
                        $query->orWhere('survey_applicants.application_status', config("constant.SURVEY_APPLICANT_STATUS." . ucwords($status['key'])));
                    } else {
                        $query->orWhere('survey_applicants.application_status', config("constant.SURVEY_APPLICANT_STATUS." . ucwords($status)));
                    }
                }
            });
        }

        if (isset($filters['sensory_trained']) || isset($filters['super_taster']) || isset($filters['user_type'])) {
            $Ids =   $Ids->leftJoin('profiles', 'survey_applicants.profile_id', '=', 'profiles.id');
        }
        if (isset($filters['sensory_trained'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['sensory_trained'] as $sensory) {
                    if ((isset($version_num) && $version_num == 'v1' && $sensory['key'] == 'Yes') || $sensory == 'Yes'){
                        $sensory = 1;
                    } else {
                        $sensory = 0;
                    }
                    $query->orWhere('profiles.is_sensory_trained', $sensory);
                }
            });
        }

        if (isset($filters['super_taster'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['super_taster'] as $superTaster) {
                    if ((isset($version_num) && $version_num == 'v1' && $superTaster['key'] == 'SuperTaster') || $superTaster == 'SuperTaster')
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
                    if ((isset($version_num) && $version_num == 'v1' && $userType['key'] == 'Expert') || $userType == 'Expert')
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
    
    function getProfileOfQuestions($filterForm, $survey_id, $idsAttemptMapping){

        // $profileCompleteAttempt = SurveyAttemptMapping::select(['profile_id', 'attempt'])->distinct()->where("survey_id", "=", $survey_id)->whereNull("deleted_at")->whereNotNull("completion_date")->get();

        // $idsAttemptMapping = [];
        // foreach ($profileCompleteAttempt as $pattempt) {
        //     $idsAttemptMapping[$pattempt->profile_id][] = $pattempt->attempt;
        // }


        // echo count($profileCompleteAttempt);

        // print_r($idsAttemptMapping);
        // $profileIds = SurveyAttemptMapping::select("profile_id", "attempt")->where("survey_id", "=", $id)->whereNull("deleted_at")->whereNull("completion_date")->get()
        
        // $profileIds = surveyApplicants::where('survey_id', $survey_id)->where('application_status', config("constant.SURVEY_APPLICANT_STATUS.Completed"))->whereNull('deleted_at')->get()->pluck('profile_id')->unique();
       
        foreach($filterForm as $form){
            if($form['element_type'] == 'section'){
                $questions = $form['questions'];

                foreach($questions as $question){
                    $options = $question['options'] ?? [];
                    $optionIds = [];
                    foreach($options as $option){
                        $optionIds[] = $option['id'];
                    }
                    
                    if (count($optionIds) > 0){
                        $profileCompleteAttempt = SurveyAnswers::select(['profile_id', 'attempt'])->distinct()->where('survey_id', $survey_id)->where('question_id', $question['id'])->whereIn('option_id',$optionIds)->whereNull('deleted_at')->get()->filter(function ($ans) use ($idsAttemptMapping) {
                            return isset($idsAttemptMapping[$ans->profile_id]) ? in_array($ans->attempt, $idsAttemptMapping[$ans->profile_id]) : false;
                        });

                        $idsAttemptMapping = [];
                        foreach ($profileCompleteAttempt as $pattempt) {
                            $idsAttemptMapping[$pattempt->profile_id][] = $pattempt->attempt;
                        }

                        // $profileCompleteAttempt = SurveyAnswers::where('survey_id', $survey_id)->where('question_id', $question['id'])->whereIn('option_id',$optionIds)->whereNull('deleted_at')->whereIn('profile_id', $profileIds)->get()->pluck('profile_id','attempt')->unique();
                    }
                }      
            }else{
                $options = $form['options'] ?? [];
                $optionIds = [];
                foreach($options as $option){
                    $optionIds[] = $option['id'];
                }

                if (count($optionIds) > 0){
                    // $profileIds = SurveyAnswers::where('survey_id', $survey_id)->where('question_id', $form['id'])->whereIn('option_id',$optionIds)->whereNull('deleted_at')->whereIn('profile_id', $profileIds)->get()->pluck('profile_id')->unique();

                    $profileCompleteAttempt = SurveyAnswers::select(['profile_id', 'attempt'])->distinct()->where('survey_id', $survey_id)->where('question_id', $form['id'])->whereIn('option_id',$optionIds)->whereNull('deleted_at')->get()->filter(function ($ans) use ($idsAttemptMapping) {
                        return isset($idsAttemptMapping[$ans->profile_id]) ? in_array($ans->attempt, $idsAttemptMapping[$ans->profile_id]) : false;
                    });

                    $idsAttemptMapping = [];
                    foreach ($profileCompleteAttempt as $pattempt) {
                        $idsAttemptMapping[$pattempt->profile_id][] = $pattempt->attempt;
                    }
                }
            }
        }
        return $idsAttemptMapping;
    }

    public function getFilterParameters($version_num = null, $survey_id, Request $request)
    {
        // $filters = $request->input('filter');

        $gender = [['key' => 'Male', 'value' => 'Male'], ['key' => 'Female', 'value' => 'Female'], ['key' => 'Other', 'value' => 'Other']];
        $age = Helper::getGenerationFilter();

        $surveyApplicants = surveyApplicants::where('survey_id', $survey_id)
            ->whereNull('survey_applicants.deleted_at')->whereNotNull('completion_date');

        if (isset($version_num) && $version_num == 'v2'){
            $surveyData = Surveys::where("id", "=", $survey_id)->first();
            
            $filteredProfileIds = array_keys($this->getProfileIdOfReportFilter($surveyData, $request, $version_num));
            $filters = $request->input('filters');
            $isFilterable = isset($filters) && !empty($filters) ? true : false;
            
            // gender data
            $genderData = [];
            $genderCounts = $this->getCount($surveyApplicants,'gender', $filteredProfileIds, $isFilterable);
            $genderData['items'] = $this->addCountToField($gender, $genderCounts);
            $genderData['key'] = 'gender';
            $genderData['value'] = 'Gender';

            // age data
            $ageData = [];
            $ageCounts = $this->getCount($surveyApplicants, 'generation', $filteredProfileIds, $isFilterable);
            $ageData['items'] = $this->addCountToField($gender, $genderCounts);
            $ageData['key'] = 'age';
            $ageData['value'] = 'Age';
        }

        // $age = [['key' => 'gen-z', 'value' => 'Gen-Z'], ['key' => 'gen-x', 'value' => 'Gen-X'], ['key' => 'millenials', 'value' => 'Millenials'], ['key' => 'yold', 'value' => 'YOld']];

        // $application_status = [["key" => 0, "value" => 'invited'], ["key" => 1, "value" => 'incomplete'], ['key' => 2, 'value' => "completed"]];
        // $userType = ['Expert', 'Consumer'];
        // $sensoryTrained = ["Yes", "No"];
        // $superTaster = ["SuperTaster", "Normal"];

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

        // if (!empty($filters) && is_array($filters)) {
        //     foreach ($filters as $filter) {
        //         if ($filter == 'gender')
        //             $data['gender'] = $gender;
        //         if ($filter == 'age')
        //             $data['age'] = $age;
        //         if ($filter == 'city')
        //             $data['city'] = $city;
        //         if ($filter == 'super_taster')
        //             $data['super_taster'] = $superTaster;
        //         if ($filter == 'user_type')
        //             $data['user_type'] = $userType;
        //         if ($filter == 'sensory_trained')
        //             $data['sensory_trained'] = $sensoryTrained;
        //         // if($filter == 'application_status')
        //         // $data['application_status'] = $currentStatus;
        //     }
        // } else {
            $data = [
                'gender' => $gender, 'age' => $age, 'city' => $city
                // ,'application_status'=>$currentStatus
            ];
        // }

        if (isset($version_num) && ($version_num == 'v1' || $version_num == 'v2')){
            $date = [['key'=>'start_date', 'value'=>''],['key'=>'end_date', 'value'=>'']];
            $data['date'] = $date;

            $count = $this->getFilteredQuestionCount($survey_id);
            if($count == 0){
                $question_filter = [['key'=>'question', 'value'=>'+ Add Questions','count'=>$count]];
            }else if($count == 1){
                $question_filter = [['key'=>'question', 'value'=>'Question','count'=>$count]];
            }else{
                $question_filter = [['key'=>'question', 'value'=>'Questions','count'=>$count]];
            }
            
            $data['question_filter'] = $question_filter;    
        }
        if (isset($version_num) && $version_num == 'v2'){
            $dateData = [];
            $dateData['items'] = $date;
            $dateData['key'] = 'date';
            $dateData['value'] = 'Date Range';

            $questionFilterData = [];
            $questionFilterData['items'] = $question_filter;
            $questionFilterData['key'] = 'question_filter';
            $questionFilterData['value'] = 'Question Filter';

            $data = [$genderData, $ageData, $dateData, $questionFilterData];
        }
        $this->model = $data;

        return $this->sendResponse();
    }

    public function getCount($model, $field, $profileIds, $isFilterable = false)
    {
        $query = clone $model;
        $table = $query->getModel()->getTable();
        $query = $query->select($field, \DB::raw('COUNT(*) as count'));

        if ($isFilterable == true) {
            ($table == 'survey_applicants') ? $query->whereIn('profile_id', $profileIds) : $query->whereIn('id', $profileIds);
        }  
        
        return $query->groupBy($field)->pluck('count', $field);
    }

    public function addCountToField($field, $fieldCounts)
    {
        foreach($field as $key => $val)
        {  
            $field[$key]['count'] = isset($fieldCounts[$val['key']]) ? $fieldCounts[$val['key']] : 0;
        }
        return $field;
    }

    public function getFieldPairedData($field, $fieldCounts)
    {
        if(empty($field))
        {
            $field['items'] = [];
            return $field;
        }

        foreach($field as $key => $val)
        {  
            $inner_arr['key'] = $val;
            $inner_arr['value'] = $val;
            $inner_arr['count'] = isset($fieldCounts[$val]) ? $fieldCounts[$val] : 0;
            unset($field[$key]);
            $field['items'][$key] = $inner_arr;
        }
        return $field;
    }

    public function getProfileFieldPairedData($fieldCounts, $val1, $val2)
    {
        $field = [];
        $field['items'] = [['key' => $val1, 'value' => $val1, 'count' => isset($fieldCounts[1]) ? $fieldCounts[1] : 0], ['key' => $val2, 'value' => $val2, 'count' => isset($fieldCounts[0]) ? $fieldCounts[0] : 0]];
        return $field;
    }

    function getFilteredQuestionCount($survey_id){
        $surveyDetail = Surveys::where("id", "=", $survey_id)->first();
        if (empty($surveyDetail)) {
            return 0;
        }
        $formJson = json_decode($surveyDetail['form_json'], true);

        $savedFilter = \DB::table('survey_filters')->where('surveys_id', $survey_id)->first(); 
        if(empty($savedFilter)){
            return 0;
        }

        $savedFormJson = json_decode($savedFilter->value, true);
        if(empty($savedFormJson)){
            return 0;
        }

        $count = 0;
        foreach($savedFormJson as $form){
            if($form['element_type'] == 'section'){
                $count += count($form['questions']);
            }else{
                $count += 1;
            }
        }
        return $count;
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