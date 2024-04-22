<?php

namespace App\Http\Controllers\Api\Survey;

use App\surveyApplicants;
use Illuminate\Http\Request;
use App\Helper;
use Carbon\Carbon;
use App\Surveys;
use App\Recipe\Profile;
use App\SurveyAnswers;
use App\SurveyAttemptMapping;

trait FilterTraits
{

    public function getProfileIdOfReportFilter($surveyDetails, Request $request, $ignoreField = '')
    {
        $filters = $request->filters;
        if(isset($ignoreField) && isset($filters[$ignoreField])){
            unset($filters[$ignoreField]);
        }

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
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['age'] as $age) {
                    $age = (is_string($age) && !isset($age['key'])) ? $age : $age['key'];
                    // if (isset($version_num) && ($version_num == 'v1' || $version_num == 'v2')){
                        ($age == "not_defined") ? $query->orWhereNull('survey_applicants.generation')->orWhere('survey_applicants.generation','') : $query->orWhere('survey_applicants.generation', 'LIKE', $age);
                    // }else{
                    //     $age = htmlspecialchars_decode($age);
                    //     $query->orWhere('survey_applicants.generation', 'LIKE', $age);
                    // }
                }
            });
        }
        
        if (isset($filters['hometown'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['hometown'] as $hometown) {
                    $hometown = (is_string($hometown) && !isset($hometown['key'])) ? $hometown : $hometown['key'];
                        
                    ($hometown == "not_defined") ? $query->orWhereNull('survey_applicants.hometown')->orWhere('survey_applicants.hometown','') : $query->orWhere('survey_applicants.hometown', 'LIKE', $hometown);
                }
            });
        }

        if (isset($filters['current_city'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['current_city'] as $current_city) {
                    $current_city = (is_string($current_city) && !isset($current_city['key'])) ? $current_city : $current_city['key'];
                        
                    ($current_city == "not_defined") ? $query->orWhereNull('survey_applicants.current_city')->orWhere('survey_applicants.current_city','') : $query->orWhere('survey_applicants.current_city', 'LIKE', $current_city);
                }
            });
        }

        if (isset($filters['gender'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['gender'] as $gender) {
                    $gender = (is_string($gender) && !isset($gender['key'])) ? $gender : $gender['key'];
                    // if (isset($version_num) && ($version_num == 'v1' || $version_num == 'v2')){
                        ($gender == "not_defined") ? $query->orWhereNull('survey_applicants.gender')->orWhere('survey_applicants.gender','') : $query->orWhere('survey_applicants.gender', 'LIKE', $gender);
                    // }else{
                    //     $query->orWhere('survey_applicants.gender', 'LIKE', $gender);
                    // }
                }
            });
        }

        if (isset($filters['sensory_trained']) || isset($filters['super_taster']) || isset($filters['user_type'])) {
            $Ids =   $Ids->leftJoin('profiles', 'survey_applicants.profile_id', '=', 'profiles.id');
        }
        if (isset($filters['sensory_trained'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['sensory_trained'] as $sensory) {
                    $sensory = (is_string($sensory) && !isset($sensory['key'])) ? $sensory : $sensory['key'];
                    if ($sensory == 'Yes'){
                        $sensory = 1;
                    } else {
                        $sensory = 0;
                    }
                    $query->orWhere('profiles.is_sensory_trained', $sensory);
                }
            });
        }

        if (isset($filters['super_taster'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['super_taster'] as $superTaster) {
                    $superTaster = (is_string($superTaster) && !isset($superTaster['key'])) ? $superTaster : $superTaster['key'];
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
                    $userType = (is_string($userType) && !isset($userType['key'])) ? $userType : $userType['key'];
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

    public function getProfileIdOfFilter($surveyDetails, Request $request, $ignoreField = '')
    {
        $filters = $request->filters;
        if(isset($ignoreField) && isset($filters[$ignoreField])){
            unset($filters[$ignoreField]);
        }
        $profileIds = collect([]);

        if ($profileIds->count() == 0 && isset($filters['profile_id'])) {
            $filterProfile = [];
            foreach ($filters['profile_id'] as $filter) {
                //$isFilterAble = true;
                $filter = (is_string($filter) && !isset($filter['key'])) ? $filter : $filter['key'];
                // if (isset($version_num) && $version_num == 'v1'){
                //     $filterProfile[] = (int)$filter['key'];
                // } else {
                    $filterProfile[] = (int)$filter;
                // }
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
                    $age = (is_string($age) && !isset($age['key'])) ? $age : $age['key'];
                    // if (isset($version_num) && $version_num == 'v1'){
                        ($age == "not_defined") ? $query->orWhereNull('survey_applicants.generation')->orWhere('survey_applicants.generation','') : $query->orWhere('survey_applicants.generation', 'LIKE', $age);
                    // }else{
                    //     $age = htmlspecialchars_decode($age);
                    //     $query->orWhere('survey_applicants.generation', 'LIKE', $age);
                    // }
                }
            });
        }

        if (isset($filters['hometown'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['hometown'] as $hometown) {
                    $hometown = (is_string($hometown) && !isset($hometown['key'])) ? $hometown : $hometown['key'];
                        
                    ($hometown == "not_defined") ? $query->orWhereNull('survey_applicants.hometown')->orWhere('survey_applicants.hometown','') : $query->orWhere('survey_applicants.hometown', 'LIKE', $hometown);
                }
            });
        }

        if (isset($filters['current_city'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['current_city'] as $current_city) {
                    $current_city = (is_string($current_city) && !isset($current_city['key'])) ? $current_city : $current_city['key'];
                        
                    ($current_city == "not_defined") ? $query->orWhereNull('survey_applicants.current_city')->orWhere('survey_applicants.current_city','') : $query->orWhere('survey_applicants.current_city', 'LIKE', $current_city);
                }
            });
        }

        if (isset($filters['profile'])) {
            $Ids =   $Ids->leftJoin('profile_specializations', 'survey_applicants.profile_id', '=', 'profile_specializations.profile_id')
                ->leftJoin('specializations', 'profile_specializations.specialization_id', '=', 'specializations.id');

            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['profile'] as $profile) {
                    $profile = (is_string($profile) && !isset($profile['key'])) ? $profile : $profile['key'];
                    // if (isset($version_num) && $version_num == 'v1'){
                    //     $query->orWhere('name', 'LIKE', htmlspecialchars_decode($profile['key']));
                    // } else {
                        $query->orWhere('name', 'LIKE', htmlspecialchars_decode($profile));
                    // }
                }
            });
        }
        
        if (isset($filters['gender'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['gender'] as $gender) {
                    $gender = (is_string($gender) && !isset($gender['key'])) ? $gender : $gender['key'];
                    // if (isset($version_num) && $version_num == 'v1'){
                        ($gender == "not_defined") ? $query->orWhereNull('survey_applicants.gender')->orWhere('survey_applicants.gender','') : $query->orWhere('survey_applicants.gender', 'LIKE', $gender);
                    // }else{
                    //     $query->orWhere('survey_applicants.gender', 'LIKE', $gender);
                    // }
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
                $Ids = $Ids->whereIn('survey_applicants.profile_id', $dateProfileIds);
            }else if($start_date != ''){
                $dateProfileIds = SurveyAttemptMapping::where('survey_id', $surveyDetails->id)->whereNull('deleted_at')->where('completion_date','>=',$start_date)->get()->pluck('profile_id')->unique();
                $Ids = $Ids->whereIn('survey_applicants.profile_id', $dateProfileIds);
            }else if($end_date != ''){
                $dateProfileIds = SurveyAttemptMapping::where('survey_id', $surveyDetails->id)->whereNull('deleted_at')->where('completion_date','<=',$end_date)->get()->pluck('profile_id')->unique();
                $Ids = $Ids->whereIn('survey_applicants.profile_id', $dateProfileIds);
            }           
        }
        
        if (isset($filters['application_status'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['application_status'] as $status) {
                    $status = (is_string($status) && !isset($status['key'])) ? $status : $status['key'];
                    // if (isset($version_num) && $version_num == 'v1'){
                    //     $query->orWhere('survey_applicants.application_status', config("constant.SURVEY_APPLICANT_STATUS." . ucwords($status['key'])));
                    // } else {
                        $query->orWhere('survey_applicants.application_status', config("constant.SURVEY_APPLICANT_STATUS." . ucwords($status)));
                    // }
                }
            });
        }

        if (isset($filters['sensory_trained']) || isset($filters['super_taster']) || isset($filters['user_type'])) {
            $Ids =   $Ids->leftJoin('profiles', 'survey_applicants.profile_id', '=', 'profiles.id');
        }
        if (isset($filters['sensory_trained'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['sensory_trained'] as $sensory) {
                    $sensory = (is_string($sensory) && !isset($sensory['key'])) ? $sensory : $sensory['key'];
                    if ($sensory == 'Yes'){
                        $sensory = 1;
                    } else {
                        $sensory = 0;
                    }
                    $query->orWhere('profiles.is_sensory_trained', $sensory);
                }
            });
        }

        if (isset($filters['super_taster'])) {
            $Ids = $Ids->where(function ($query) use ($filters) {
                foreach ($filters['super_taster'] as $superTaster) {
                    $superTaster = (is_string($superTaster) && !isset($superTaster['key'])) ? $superTaster : $superTaster['key'];
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
                    $userType = (is_string($userType) && !isset($userType['key'])) ? $userType : $userType['key'];
                    if ($userType == 'Expert')
                        $userType = 1;
                    else
                        $userType = 0;
                    $query->orWhere('profiles.is_expert', $userType);
                }
            });
        }
       
        if ($profileIds->count() > 0 && isset($Ids)) {
            $Ids = $Ids->whereIn('survey_applicants.profile_id'); 
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

        $surveyAttemptedProfileIds = SurveyAttemptMapping::select('profile_id')->distinct()->where("survey_id", "=", $survey_id)->whereNotNull("completion_date")->whereNull("deleted_at")->pluck('profile_id');
    
        $surveyApplicants = surveyApplicants::where('survey_id', $survey_id)->whereNull('survey_applicants.deleted_at');

        if (isset($version_num) && $version_num == 'v2'){
            $surveyData = Surveys::where("id", "=", $survey_id)->first();
            
            $filteredProfileIds = array_keys($this->getProfileIdOfReportFilter($surveyData, $request));
            $filters = $request->input('filters');
            $filteredProfileIds = isset($filters) && !empty($filters) ? $filteredProfileIds : $surveyAttemptedProfileIds;
            
            $surveyApplicants = $surveyApplicants->whereIn('survey_applicants.profile_id', $filteredProfileIds);
            // gender data
            $genderData = [];
            $genderCounts = $this->getCount($surveyApplicants,'gender');
            $genderData['items'] = $this->addCountToField($gender, $genderCounts);
            $genderData = $this->addEmptyValue($genderData, $genderCounts);
            $genderData['key'] = 'gender';
            $genderData['value'] = 'Gender';
            $genderData['type'] =  config("constant.FILTER_TYPE.MULTI_SELECT");


            // age data
            $ageData = [];
            $ageCounts = $this->getCount($surveyApplicants, 'generation');
            $ageData['items'] = $this->addCountToField($age, $ageCounts);
            $ageData = $this->addEmptyValue($ageData, $ageCounts);
            $ageData['key'] = 'age';
            $ageData['value'] = 'Generation';
            $ageData['type'] =  config("constant.FILTER_TYPE.MULTI_SELECT");

            // Hometown
            $homeTown['items'] = [];
            if(isset($filters['hometown'])){
                $hometownCounts = $this->getCount($surveyApplicants, 'hometown');
                $homeTown = $this->getFieldPairedData(array_column($filters['hometown'], 'key'), $hometownCounts);
            }
            $homeTown['type'] =  config("constant.FILTER_TYPE.DROPDOWN_SEARCH");
            $homeTown['key'] = 'hometown';
            $homeTown['value'] = 'Hometown';
 
            // Current City
            $currentCity['items'] = [];
            if(isset($filters['current_city'])){
                $currentCityCounts = $this->getCount($surveyApplicants, 'current_city');
                $currentCity = $this->getFieldPairedData(array_column($filters['current_city'], 'key'), $currentCityCounts);
            }
            $currentCity['key'] = 'current_city';
            $currentCity['value'] = 'Current City';
            $currentCity['type'] =  config("constant.FILTER_TYPE.DROPDOWN_SEARCH");

            $profileModel = Profile::select('id', 'is_expert', 'is_sensory_trained', 'is_tasting_expert')->whereNull('deleted_at')->whereIn('id', $filteredProfileIds);
            
            // count of experts
            $userTypeCounts = $this->getCount($profileModel,'is_expert');
            $userType = $this->getProfileFieldPairedData($userTypeCounts, 'Expert', 'Consumer');
            $userType['key'] = 'user_type';
            $userType['value'] = 'User Type';
            $userType['type'] =  config("constant.FILTER_TYPE.MULTI_SELECT");

            // sensory trained or not
            $sensoryTrainedCounts = $this->getCount($profileModel,'is_sensory_trained');
            $sensoryTrained = $this->getProfileFieldPairedData($sensoryTrainedCounts, 'Yes', 'No');
            $sensoryTrained['key'] = 'sensory_trained';
            $sensoryTrained['value'] = 'Sensory Trained';
            $sensoryTrained['type'] =  config("constant.FILTER_TYPE.MULTI_SELECT");

            // supar taster or not
            $superTasterCounts = $this->getCount($profileModel,'is_tasting_expert');
            $superTaster = $this->getProfileFieldPairedData($superTasterCounts, 'SuperTaster', 'Normal');
            $superTaster['key'] = 'super_taster';
            $superTaster['value'] = 'Super Taster';
            $superTaster['type'] =  config("constant.FILTER_TYPE.MULTI_SELECT");

        }

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
            $dateData['type'] = config("constant.FILTER_TYPE.DATE");
            $dateData['key'] = 'date';
            $dateData['value'] = 'Submission Date Range';

            $questionFilterData = [];
            $questionFilterData['items'] = $question_filter;
            $questionFilterData['type'] = config("constant.FILTER_TYPE.QUESTION_FILTER");
            $questionFilterData['key'] = 'question_filter';
            $questionFilterData['value'] = 'Question Filter';

            $data = [$genderData, $ageData, $homeTown, $currentCity, $questionFilterData, $userType, $sensoryTrained, $superTaster, $dateData];
        }
        $this->model = $data;

        return $this->sendResponse();
    }

    public function getCount($model, $field)
    {
        $query = clone $model;
        // $table = $query->getModel()->getTable();
        $query->selectRaw("CASE 
            WHEN $field IS NULL THEN 'not_defined'
            WHEN $field = '' AND $field != '0' THEN 'not_defined'
            ELSE $field END AS $field")->selectRaw('COUNT(*) as count')
            ->groupBy(\DB::raw("CASE 
                WHEN $field IS NULL THEN 'not_defined'
                WHEN $field = '' AND $field != '0' THEN 'not_defined'
                ELSE $field END"));

        // if($table == 'survey_applicants'){
        //     $query = $query->whereIn('survey_applicants.profile_id', $profileIds);
        // } else {
        //     $query->whereIn('id', $profileIds);
        // }
        
        return $query->pluck('count', $field);
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

    public function getFieldList($request, $field, $surveyId){
        $current_state = $request->state;
        $current_status = $request->current_status;
        $search_val = $request->q;
        $page = $request->page;
        $filters = $request->filters;
        if(isset($field) && isset($filters[$field])){
            unset($filters[$field]);
        }

        $surveyData = Surveys::where("id", "=", $surveyId)->first();
        $surveyApplicants = surveyApplicants::where('survey_id', $surveyId)
        ->whereNull('survey_applicants.deleted_at');

        if(isset($current_status) && !empty($current_status) && $current_status = config("constant.SURVEY_STATUS.COMPLETED")){ // report section filters
            $surveyAttemptedProfileIds = SurveyAttemptMapping::select('profile_id')->distinct()->where("survey_id", "=", $surveyId)->whereNotNull("completion_date")->whereNull("deleted_at")->pluck('profile_id')->toArray();
            $filteredProfileIds = array_keys($this->getProfileIdOfReportFilter($surveyData, $request, $field));
            $filteredProfileIds = !empty($filters) ? array_values(array_intersect($filteredProfileIds, $surveyAttemptedProfileIds)) : $surveyAttemptedProfileIds;
        } else { // manage section filters
            $filteredProfileIds = !empty($filters) ? $this->getProfileIdOfFilter($surveyData, $request, $field)['profile_id']->toArray() : $surveyApplicants->pluck('profile_id')->toArray();
        }

        // for manage section
        if(isset($current_state) && $current_state == config("constant.SURVEY_APPLICANT_STATE.ACTIVE")){
            $surveyApplicants = $surveyApplicants->whereNull('survey_applicants.rejected_at');
        } else if(isset($current_state) && $current_state == config("constant.SURVEY_APPLICANT_STATE.REJECTED")){
            $surveyApplicants = $surveyApplicants->whereNotNull('survey_applicants.rejected_at');
        }

        if (isset($search_val) && $search_val != null) {
            $surveyApplicants = $surveyApplicants->where($field, 'LIKE', '%'.$search_val.'%');
        }
        $surveyApplicants = $surveyApplicants->whereIn('survey_applicants.profile_id', $filteredProfileIds);

        return $this->getFieldListData($page, $surveyApplicants, $field);
    }

    public function getFieldListData($page, $fieldApplicants, $field){
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $fieldApplicants = $fieldApplicants->skip($skip)->take($take);

        $fieldWithCounts = $this->getCount($fieldApplicants, $field);
        $field = [];
        if($fieldWithCounts->isNotEmpty()){
            if(isset($fieldWithCounts['not_defined'])){
                $inner_arr['key'] = "not_defined";
                $inner_arr['value'] = "Didn't mention";
                $inner_arr['count'] = $fieldWithCounts['not_defined'];
                $field[] = $inner_arr;
                unset($fieldWithCounts['not_defined']);
            }
            foreach($fieldWithCounts as $key => $val)
            {  
                $inner_arr['key'] = $key;
                $inner_arr['value'] = $key;
                $inner_arr['count'] = isset($val) ? $val : 0;
                $field[] = $inner_arr;
            }
        }
        return $field;
    }

    public function addEmptyValue($field, $fieldCounts){
        $inner_arr['key'] = "not_defined";
        $inner_arr['value'] = "Didn't mention";
        $inner_arr['count'] = isset($fieldCounts["not_defined"]) ? $fieldCounts["not_defined"] : (isset($fieldCounts[""]) ? $fieldCounts[""] : 0);
        
        array_push($field['items'], $inner_arr);
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