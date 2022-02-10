<?php

namespace App\Http\Controllers\Api\Collaborate;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Collaborate\ReviewHeader;
use App\Traits\FilterFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GraphController extends Controller
{
    use FilterFactory;

    public function graphHeaders(Request $request, $id)
    {

        $headerList = DB::Table("collaborate_tasting_header")->where("collaborate_id", $id)->where('is_active', 1)->whereIn("header_selection_type", [config("constant.COLLABORATE_HEADER_SELECTION_TYPE.NORMAL"), config("constant.COLLABORATE_HEADER_SELECTION_TYPE.PRODUCT_EXPERIENCE")])->get();


        $headerResponse = [];
        $comb = [];
        foreach ($headerList as $headerValue) {
            $getQuestions = DB::table("collaborate_tasting_questions")->where('header_type_id', $headerValue->id)->where("collaborate_id", $id)->where('is_active', 1)->get();
            $graphActive = false;
            foreach ($getQuestions as $questionList) {
                $decodeJsonOfQuestions = json_decode($questionList->questions, true);

                if (json_last_error() == JSON_ERROR_NONE) {
                    if (
                        isset($decodeJsonOfQuestions["is_nested_option"]) && $decodeJsonOfQuestions["is_nested_option"] != 0
                        && isset($decodeJsonOfQuestions["create_graph"]) && $decodeJsonOfQuestions["create_graph"] == true
                    ) {

                        $comb[$decodeJsonOfQuestions["nested_option_list"]][] = ["id" => $headerValue->id, "que_id" => $questionList->id, "header_name" => $headerValue->header_type];
                    }
                    if (isset($decodeJsonOfQuestions["create_graph"]) && $decodeJsonOfQuestions["create_graph"] == true) {

                        $graphActive = true;
                    }

                    //nested option
                }
            }
            if ($graphActive) {
                $headerResponse[] = [
                    // "que_id" => $questionList->id,
                    "id" => $headerValue->id, "header_name" => $headerValue->header_type, "is_combination" => false
                ];
            }
        }

        $i = 1;
        foreach ($comb as $aromaList => $headerDetails) {
            $headerResponse[] = [
                "aroma_list" => $aromaList, "header_name" => "Combination -" . $i, "is_combination" => true, "combination_header_list" => $headerDetails
            ];
            $i++;
        }

        $this->model = $headerResponse;
        return $this->sendResponse();
    }

    public function graphFilters(Request $request, $collaborateId)
    {

        $filters = $request->input('filter');

        $gender = ['Male', 'Female', 'Other'];
        $age = ['< 18', '18 - 35', '35 - 55', '55 - 70', '> 70'];
        $userType = ['Expert', 'Consumer'];
        $sensoryTrained = ["Yes", "No"];
        $superTaster = ["SuperTaster", "Normal"];
        $applicants = \DB::table('collaborate_applicants')->where('collaborate_id', $collaborateId)->get();
        $city = [];
        $profile = \DB::table('specializations')->orderBy("order", "ASC")->get()->pluck('name')->toArray();
        foreach ($applicants as $applicant) {
            if (isset($applicant->city)) {
                if (!in_array($applicant->city, $city))
                    $city[] = $applicant->city;
            }
        }
        $data = [];
        if (count($filters)) {
            foreach ($filters as $filter) {
                if ($filter == 'gender')
                    $data['gender'] = $gender;
                if ($filter == 'age')
                    $data['age'] = $age;
                if ($filter == 'city')
                    $data['city'] = $city;
                if ($filter == 'profiles')
                    $data['profiles'] = $profile;
                if ($filter == 'super_taster')
                    $data['super_taster'] = $superTaster;
                if ($filter == 'user_type')
                    $data['user_type'] = $userType;
                if ($filter == 'sensory_trained')
                    $data['sensory_trained'] = $sensoryTrained;
            }
        } else {
            $data = ['gender' => $gender, 'age' => $age, 'city' => $city, "profiles" => $profile, "user_type" => $userType, "sensory_trained" => $sensoryTrained, "super_taster" => $superTaster];
        }
        $this->model = $data;

        return $this->sendResponse();
    }

    public function graphReports(Request $request, $collaborateId)
    {

        $reports = \DB::table('collaborate_reports')
            ->select(
                'collaborate_reports.id',
                'collaborate_reports.title',
                'collaborate_reports.description',
                'collaborate_reports.link',
                'collaborate_reports.created_at',
                'collaborate_reports.updated_at'
            )
            ->where('collaborate_id', $collaborateId)->get();

        $this->model = $reports;

        return $this->sendResponse();
    }


    public function createGraphs(Request $request, $collaborateId, $headerId)
    {
        $getQuestions = DB::table("collaborate_tasting_questions")->where('header_type_id', $headerId)->where("collaborate_id", $collaborateId)->where('is_active', 1)->get();

        $batches = DB::table('collaborate_batches')->select('id', 'name')->where('collaborate_id', $collaborateId)->get();

        $questionSet = [];

        $filters = $request->input('filters');

        $profileIds = $this->getFilteredProfile($filters, $collaborateId);

        $intensity_value = [];
        foreach ($getQuestions as $questionList) {
            $decodeJsonOfQuestions = json_decode($questionList->questions, true);

            //FOR TESTING ONLY - Remove before live
            $decodeJsonOfQuestions["create_graph"] = true;
            if($questionList->id%2!=0){
                $decodeJsonOfQuestions["merge_graph"] = true;
            }
            ////////////////////////////
            
            if (isset($decodeJsonOfQuestions["intensity_value"]) && !empty($decodeJsonOfQuestions["intensity_value"])) {
                $intensity_value = explode(",", $decodeJsonOfQuestions["intensity_value"]);
            }
            if (json_last_error() == JSON_ERROR_NONE && isset($decodeJsonOfQuestions["create_graph"]) && $decodeJsonOfQuestions["create_graph"]) {
                $option = [];

                if (isset($decodeJsonOfQuestions["option"]) && !empty($decodeJsonOfQuestions["option"])) {
                    $option = $decodeJsonOfQuestions["option"];
                }

                if (isset($decodeJsonOfQuestions["merge_graph"]) && $decodeJsonOfQuestions["merge_graph"] == true) {
                    $questionSet["merged"][] = ["id" => $questionList->id, "title" => $questionList->title, "is_intensity" => $decodeJsonOfQuestions["is_intensity"], "option" => $option, "intensity_value"];
                } else {
                    $questionSet[] = ["id" => $questionList->id, "title" => $questionList->title, "is_intensity" => $decodeJsonOfQuestions["is_intensity"], "option" => $option];
                }
            }
        }
        $i = 0;

        $questionResponse = [];
        foreach ($questionSet as $key => $value) {
            if ($key !== "merged") {
                $value = [$value];
            }
            $questionResponse[$i] = $this->getOptions($value, $collaborateId, $headerId, $profileIds, $intensity_value);
            // dd($question Set[$i]);
            $i++;
        }

        $this->model = $this->sortFinalGraphPayload($headerId, $questionResponse, $intensity_value);
        return $this->sendResponse();
    }

    public function sortFinalGraphPayload($headerId, &$questionResponse)
    {
        $headerDetails = DB::table("collaborate_tasting_header")->where("id", $headerId)->first();
        foreach ($questionResponse as $payloadKey => $payloadValue) {
            $questionResponse[$payloadKey]["header_id"] = $headerDetails->id;
            $questionResponse[$payloadKey]["header_name"] = $headerDetails->header_type;
            foreach ($questionResponse[$payloadKey]["options"] as $optionKey => $optionValue) {
                foreach ($questionResponse[$payloadKey]["options"][$optionKey]["batch"] as $batchKey => $batchValue) {
                    
                    $percentage = 0;
                    if ($questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["response"] != 0) {
                        $percentage = (($questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["response"] / $questionResponse[$payloadKey]["options"][$optionKey]["totalResponse"][$batchValue["id"]]) * 100);
                    }
                    $questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["percentage"] = (string)round($percentage, 2);

                    $intensity = 0;
                    if ($questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["intensity"] != 0 && !empty($intensity_value)) {
                        
                        $intensity = (array_sum($questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["intensity"]) / $questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["response"]);
                    }
                    $questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["intensity"] = round($intensity, 2);
                }
                unset($questionResponse[$payloadKey]["options"][$optionKey]["totalResponse"]);
            }
        }
        return $questionResponse;
    }

    public function getBatches($collaborateId)
    {
        return DB::table("collaborate_batches")->join("collaborate_batches_color", "collaborate_batches_color.id", "=", "collaborate_batches.color_id")->where("collaborate_id", $collaborateId)->select(["collaborate_batches_color.name as color_code", "collaborate_batches.id", "collaborate_batches.name as batch_name"])->get();
    }

    public function getIfDefaultOptionsExists($question, $batchDetails, &$optionCounter, $intensity_scale, &$intensityFlag, &$optValue, $getdbOptions)
    {


        foreach ($question["option"] as $optionValue) {

            $optValue[$optionCounter]["id"] = $optionValue["id"];
            $optValue[$optionCounter]["name"] = $optionValue["value"];
            $optValue[$optionCounter]["batch"] = [];
            if(isset($optionValue["intensity_value"]) && !empty($optionValue["intensity_value"])){
                $intensity_scale = explode(",",$optionValue["intensity_value"]);
            }
            $initialIntensity = ((isset($optionValue["initial_intensity"]) && !empty($optionValue["initial_intensity"])) ? $optionValue["initial_intensity"] : 1);
            $j = 0;
            foreach ($batchDetails as $batch) {
                $optValue[$optionCounter]["batch"][$j] = (array)$batch;
                if (!empty($getdbOptions)) {

                    foreach ($getdbOptions as $responseOption) {
                        if(!isset($optValue[$optionCounter]["totalResponse"][$responseOption->batch_id])){
                            $optValue[$optionCounter]["totalResponse"][$responseOption->batch_id] = 1;
                        }else{
                            $optValue[$optionCounter]["totalResponse"][$responseOption->batch_id]++;
                        }
                        if (isset($responseOption->leaf_id) && $responseOption->leaf_id == $optionValue["id"] && $responseOption->batch_id == $batch->id) { 
                            if (isset($optValue[$optionCounter]["batch"][$j]["response"])) {
                                $optValue[$optionCounter]["batch"][$j]["response"]++;
                            } else {
                                $optValue[$optionCounter]["batch"][$j]["response"] = 1;
                            }

                            if (isset($question["is_intensity"]) && $question["is_intensity"] == 1 && !empty($responseOption->intensity) && !empty($intensity_scale)) {

                                $intensityFlag = true;
                                $optValue[$optionCounter]["batch"][$j]["intensity"][] = $intensity_scale[$responseOption->intensity] + $initialIntensity;
                            }
                        }
                    }
                }
                if (!isset($optValue[$optionCounter]["batch"][$j]["response"])) {
                    $optValue[$optionCounter]["batch"][$j]["response"] = 0;
                }
                if (!isset($optValue[$optionCounter]["batch"][$j]["intensity"])) {
                    $optValue[$optionCounter]["batch"][$j]["intensity"] = 0;
                }
                $j++;
            }
            $optionCounter++;
        }
    }

    public function getIfDefaultOptionsDoesntExists($question, $batchDetails, &$optionCounter, $intensity_scale, &$intensityFlag, &$optValue, $getdbOptions)
    {
        $prepArray = [];
        if ($getdbOptions) {

            foreach ($getdbOptions as $responseOption) {
                $prepArray[$responseOption->leaf_id]["id"] = $responseOption->leaf_id;
                $prepArray[$responseOption->leaf_id]["name"] = $responseOption->value;
                $prepArray[$responseOption->leaf_id]["batch"] = [];
                $j = 0;
                foreach ($batchDetails as $batch) {
                    $prepArray[$responseOption->leaf_id]["batch"][$j] =  (array)$batch;
                    if (isset($prepArray[$responseOption->leaf_id]["batch"][$j]["response"])) {
                        $prepArray[$responseOption->leaf_id]["batch"][$j]["response"]++;
                    } else {
                        $prepArray[$responseOption->leaf_id]["batch"][$j]["response"] = 1;
                    }

                    if (isset($question["is_intensity"]) && $question["is_intensity"] == 1 && !empty($responseOption->intensity) && !empty($intensity_scale)) {

                        $intensityFlag = true;
                        $prepArray[$responseOption->leaf_id]["batch"][$j]["intensity"][] = $intensity_scale[$responseOption->intensity] + 1;
                    }

                    if (!isset($prepArray[$responseOption->leaf_id]["batch"][$j]["response"])) {
                        $prepArray[$responseOption->leaf_id]["batch"][$j]["response"] = 0;
                    }
                    if (!isset($prepArray[$responseOption->leaf_id]["batch"][$j]["intensity"])) {
                        $prepArray[$responseOption->leaf_id]["batch"][$j]["intensity"] = 0;
                    }
                    $j++;
                }
                $optValue[$optionCounter] = $prepArray[$responseOption->leaf_id];

                $optionCounter++;
            }
        }
    }
    public function getOptions($questionArray = [], $collaborateId, $headerId, $profileIds = [], $intensity_value)
    {
        $intensity_scale = [];
        if (!empty($intensity_value)) {
            $intensity_scale = array_flip($intensity_value);
        }
        $batchDetails = $this->getBatches($collaborateId);
        $questionResponse = [];
        $questionList = [];
        $ques = 0;
        // $batches ;
        $intensityFlag = false;
        $i = 0;
        $optValue = [];
        foreach ($questionArray as $question) {

            $questionList[$ques]["id"] = $question["id"];
            $questionList[$ques]["title"] = $question["title"];
            $ques++;

            $getOptions = DB::table('collaborate_tasting_user_review')->where("question_id", $question["id"])->where("tasting_header_id", $headerId)->where("collaborate_id", $collaborateId)->where("current_status", 3)->select(["id", "key", "value", "value_id", "leaf_id", "intensity", "batch_id", "profile_id", "option_type"]);
            if (!empty($profileIds)) {
                $getOptions = $getOptions->whereIn("profile_id", $profileIds);
            }
            $getOptions = $getOptions->get();
            $questionResponse["question_list"] =  $questionList;
            if (isset($question["option"]) && !empty($question["option"])) {
                $this->getIfDefaultOptionsExists($question, $batchDetails, $i, $intensity_scale, $intensityFlag, $optValue, $getOptions);
                $questionResponse["is_intensity"] =  $intensityFlag;
                $questionResponse["options"] =  $optValue;
            } else {
                $this->getIfDefaultOptionsDoesntExists($question, $batchDetails, $i, $intensity_scale, $intensityFlag, $optValue, $getOptions);
                $questionResponse["is_intensity"] =  $intensityFlag;
                $questionResponse["options"] =  $optValue;
            }
        }
        return $questionResponse;
    }
    
    public function graphCombination(Request $request, $collaborateId)
    {
        $combinationHeadList = $request["combination_header_list"];
        //dd($combinationHeadList);
        $batches = \DB::table('collaborate_batches')->select('id', 'name')->where('collaborate_id', $collaborateId)->get();
        $filters = $request->input('filters');
        $resp = $this->getFilteredProfile($filters, $collaborateId);
        $profileIds = $resp['profile_id']; //dd($profileIds);
        $type = $resp['type'];
        $boolean = 'and';
        foreach ($batches as $batch) {
            $totalApplicants[$batch->id] = \DB::table('collaborate_tasting_user_review')->where('value', '!=', '')->where('current_status', 3)->where('collaborate_id', $collaborateId)
                ->whereIn('profile_id', $profileIds, $boolean, $type)->where('batch_id', $batch->id)->distinct()->get(['profile_id'])->count();
        }
        $dataset = [];
        $ques = [];
        foreach ($combinationHeadList as $value) {
            $item =  \DB::table('collaborate_tasting_questions')
                ->select(
                    'collaborate_tasting_questions.id',
                    'collaborate_tasting_questions.title',
                    'collaborate_tasting_header.header_type',
                    'collaborate_tasting_questions.questions'
                )
                ->join('collaborate_tasting_header', 'collaborate_tasting_header.id', 'collaborate_tasting_questions.header_type_id')
                ->where('collaborate_tasting_questions.collaborate_id', $collaborateId)
                ->where('collaborate_tasting_questions.id', $value['que_id'])
                ->where('collaborate_tasting_questions.header_type_id', $value['id'])->first();
            //dd($item);

            $ques[] = $item->id;
            $question = json_decode($item->questions);
            unset($item->questions);
            $dataset["question_list"][] = $item;
        }
        $dataset1['aroma_list'] = $question->nested_option_list;
        $dataset1['question_list'] = $dataset['question_list'];
        $intensityValue = explode(",", $question->intensity_value);
        $intensityCount = count($intensityValue);
        $dataset['batch'] = [];
        if (!empty($batches)) {
            
            $batch = [];
            foreach ($batches as $v) {
                $batch['id'] = $v->id;
                $batch['batch_name'] = $v->name;
                $batch['is_intensity'] = $question->is_intensity;
                $options =  \DB::table('collaborate_tasting_nested_options')->where('collaborate_id', $collaborateId)->where('header_type_id', $value['id'])->where('question_id', $item->id)->get();
                $optionArray = [];
                $batch['options'] = [];
                if (!empty($options)) {
                   
                    foreach ($options as $option) {
                        $optionArray["id"] = $option->id;
                        $optionArray["value"] = $option->value;
                        $optionArray["header"] = [];
                        $headerArray = [];
                        foreach ($combinationHeadList as $header) {

                            $headerArray['id'] = $header['id'];
                            $headerArray['header'] = $header['header_name'];

                            $intensityArray = \DB::table('collaborate_tasting_user_review')->where('value', $option->value)->where('collaborate_id', $collaborateId)
                                ->where('tasting_header_id', $header['id'])
                                ->where('batch_id', $v->id)->whereIn('question_id', $ques)
                                ->whereIn('profile_id', $profileIds, 'and', $type)->get()->pluck('intensity')->toArray();

                            if ($totalApplicants[$v->id] != 0 && count($intensityArray)) {
                                $headerArray['percentage'] = (count($intensityArray) / $totalApplicants[$v->id]) * 100;
                                $headerArray['responses'] = count($intensityArray);
                                if ($option->is_intensity) {
                                    $answer = array_count_values(array_filter($intensityArray));
                                    $intensities = array_flip($intensityValue);
                                    $sum = 0;
                                    foreach ($answer as $k => $vv) {
                                        $sum += $vv * $intensities[$k];
                                    }
                                    $sum += $intensityCount;
                                    $headerArray['intensity'] = $sum / $intensityCount;
                                }
                            } else {
                                $headerArray['percentage'] = 0;
                                $headerArray['responses'] = 0;
                                $headerArray['intensity'] = 0;
                            }
                            array_push($optionArray['header'], $headerArray);
                        }


                        array_push($batch['options'], $optionArray);
                    }
                }


                array_push($dataset['batch'], $batch);
            }
        }

        $dataset1['batch'] = $dataset['batch'];

        $this->model = $dataset1;

        return $this->sendResponse();
    }
}
