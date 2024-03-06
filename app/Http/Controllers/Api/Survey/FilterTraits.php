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
                        ($age['key'] == "not_defined") ? $query->orWhereNull('survey_applicants.generation')->orWhere('survey_applicants.generation','') : $query->orWhere('survey_applicants.generation', 'LIKE', $age['key']);
                    }else{
                    // $age = htmlspecialchars_decode($age);
                        $query->orWhere('survey_applicants.generation', 'LIKE', $age);
                    }
                }
            });
        }
        
        if (isset($filters['gender'])) {
            $Ids = $Ids->where(function ($query) use ($filters, $version_num) {
                foreach ($filters['gender'] as $gender) {
                    if (isset($version_num) && ($version_num == 'v1' || $version_num == 'v2')){
                        ($gender['key'] == "not_defined") ? $query->orWhereNull('survey_applicants.gender')->orWhere('survey_applicants.gender','') : $query->orWhere('survey_applicants.gender', 'LIKE', $gender['key']);
                    }else{
                        $query->orWhere('survey_applicants.gender', 'LIKE', $gender);
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
                    if ((isset($sensory['key']) && $sensory['key'] == 'Yes') || $sensory == 'Yes'){
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
                    if ((isset($superTaster['key']) && $superTaster['key'] == 'SuperTaster') || $superTaster == 'SuperTaster')
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
                    if ((isset($userType['key']) && $userType['key'] == 'Expert') || $userType == 'Expert')
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

    public function getProfileIdOfFilter($surveyDetails, Request $request, $version_num = '')
    {
        $filters = $request->filters;
        $profileIds = collect([]);

        if ($profileIds->count() == 0 && isset($filters['profile_id'])) {
            $filterProfile = [];
            foreach ($filters['profile_id'] as $filter) {
                //$isFilterAble = true;
                if (isset($version_num) && $version_num == 'v1'){
                    $filterProfile[] = (int)$filter['key'];
                } else {
                    $filterProfile[] = (int)$filter;
                }
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
                        ($age['key'] == "not_defined") ? $query->orWhereNull('survey_applicants.generation')->orWhere('survey_applicants.generation','') : $query->orWhere('survey_applicants.generation', 'LIKE', $age['key']);
                    }else{
                    // $age = htmlspecialchars_decode($age);
                        $query->orWhere('survey_applicants.generation', 'LIKE', $age);
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
                        $query->orWhere('name', 'LIKE', htmlspecialchars_decode($profile['key']));
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
                        ($gender['key'] == "not_defined") ? $query->orWhereNull('survey_applicants.gender')->orWhere('survey_applicants.gender','') : $query->orWhere('survey_applicants.gender', 'LIKE', $gender['key']);
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
                    if ((isset($sensory['key']) && $sensory['key'] == 'Yes') || $sensory == 'Yes'){
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
                    if ((isset($superTaster['key']) && $superTaster['key'] == 'SuperTaster') || $superTaster == 'SuperTaster')
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
                    if ((isset($userType['key']) && $userType['key'] == 'Expert') || $userType == 'Expert')
                        $userType = 1;
                    else
                        $userType = 0;
                    $query->orWhere('profiles.is_expert', $userType);
                }
            });
        }
       
        if ($profileIds->count() > 0 && isset($Ids)) {
            $Ids = $Ids->whereIn('survey_applicants.profile_id', $profileIds);
            
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
            
            $filteredProfileIds = array_keys($this->getProfileIdOfReportFilter($surveyData, $request, $version_num));
            $filters = $request->input('filters');
            $filteredProfileIds = isset($filters) && !empty($filters) ? $filteredProfileIds : $surveyAttemptedProfileIds;
            
            // gender data
            $genderData = [];
            $genderCounts = $this->getCount($surveyApplicants,'gender', $filteredProfileIds);
            $genderData['items'] = $this->addCountToField($gender, $genderCounts);
            $genderData = $this->addEmptyValue($genderData, $genderCounts);
            $genderData['key'] = 'gender';
            $genderData['value'] = 'Gender';

            // age data
            $ageData = [];
            $ageCounts = $this->getCount($surveyApplicants, 'generation', $filteredProfileIds);
            $ageData['items'] = $this->addCountToField($age, $ageCounts);
            $ageData = $this->addEmptyValue($ageData, $ageCounts);
            $ageData['key'] = 'age';
            $ageData['value'] = 'Generation';

            $profileModel = Profile::whereNull('deleted_at');
            
            // count of experts
            $userTypeCounts = $this->getCount($profileModel,'is_expert', $filteredProfileIds);
            $userType = $this->getProfileFieldPairedData($userTypeCounts, 'Expert', 'Consumer');
            $userType['key'] = 'user_type';
            $userType['value'] = 'User Type';

            // sensory trained or not
            $sensoryTrainedCounts = $this->getCount($profileModel,'is_sensory_trained', $filteredProfileIds);
            $sensoryTrained = $this->getProfileFieldPairedData($sensoryTrainedCounts, 'Yes', 'No');
            $sensoryTrained['key'] = 'sensory_trained';
            $sensoryTrained['value'] = 'Sensory Trained';

            // supar taster or not
            $superTasterCounts = $this->getCount($profileModel,'is_tasting_expert', $filteredProfileIds);
            $superTaster = $this->getProfileFieldPairedData($superTasterCounts, 'SuperTaster', 'Normal');
            $superTaster['key'] = 'super_taster';
            $superTaster['value'] = 'Super Taster';
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
            $dateData['type'] = 'date';
            $dateData['key'] = 'date';
            $dateData['value'] = 'Submission Date Range';

            $questionFilterData = [];
            $questionFilterData['items'] = $question_filter;
            $questionFilterData['type'] = 'question_filter';
            $questionFilterData['key'] = 'question_filter';
            $questionFilterData['value'] = 'Question Filter';

            $data = [$genderData, $ageData, $questionFilterData, $userType, $sensoryTrained, $superTaster, $dateData];
        }
        $this->model = $data;

        return $this->sendResponse();
    }

    public function getCount($model, $field, $profileIds)
    {
        $query = clone $model;
        $table = $query->getModel()->getTable();
        $query->selectRaw("CASE WHEN $field IS NULL THEN 'not_defined' ELSE $field END AS $field")->selectRaw('COUNT(*) as count');

        if($table == 'survey_applicants'){
            $query = $query->whereIn('survey_applicants.profile_id', $profileIds);
        } else {
            $query->whereIn('id', $profileIds);
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