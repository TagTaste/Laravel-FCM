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
                //FOR TESTING ONLY - Remove before live
                $decodeJsonOfQuestions["create_graph"] = true;
                if ($questionList->id % 2 != 0) {
                    $decodeJsonOfQuestions["merge_graph"] = true;
                }
                ////////////////////////////
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
            if(count($headerDetails) < 2){
                continue;
            }
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
        $getQuestions = DB::table("collaborate_tasting_questions")->where('header_type_id', $headerId)->where("collaborate_id", $collaborateId)->where('is_active', 1);

        if($request->has('question_id') && !empty($request->question_id) && is_array($request->question_id)){
            $getQuestions = $getQuestions->whereIn('id',$request->question_id);
        }

        $getQuestions = $getQuestions->get();

        $questionSet = [];

        $filters = $request->input('filters');

        $profileIds = $this->getFilteredProfile($filters, $collaborateId);

        $intensity_value = [];
        foreach ($getQuestions as $questionList) {
            $decodeJsonOfQuestions = json_decode($questionList->questions, true);
            if (in_array($decodeJsonOfQuestions["select_type"], [3, 4, 6])) {
                continue;
            }
            //FOR TESTING ONLY - Remove before live
            $decodeJsonOfQuestions["create_graph"] = true;
            if ($questionList->id % 2 != 0) {
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

                $initialIntensity = 1;
                if (isset($decodeJsonOfQuestions["is_intensity"])) {
                    $initialIntensity = (empty($decodeJsonOfQuestions["is_intensity"]) ? 1 : $decodeJsonOfQuestions["is_intensity"]);
                }
                if (isset($decodeJsonOfQuestions["merge_graph"]) && $decodeJsonOfQuestions["merge_graph"] == true) {
                    $questionSet["merged"][] = ["id" => $questionList->id, "title" => $questionList->title, "is_intensity" => $decodeJsonOfQuestions["is_intensity"], "option" => $option, "initial_intensity" => $initialIntensity];
                } else {
                    $questionSet[] = ["id" => $questionList->id, "title" => $questionList->title, "is_intensity" => $decodeJsonOfQuestions["is_intensity"], "option" => $option, "initial_intensity" => $initialIntensity];
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

        $this->model = $this->sortFinalGraphPayload($headerId, $questionResponse);
        return $this->sendResponse();
    }

    public function sortFinalGraphPayload($headerId, &$questionResponse)
    {
        // return ($questionResponse);
        $headerDetails = DB::table("collaborate_tasting_header")->where("id", $headerId)->first();
        foreach ($questionResponse as $payloadKey => $payloadValue) {
            $questionResponse[$payloadKey]["header_id"] = $headerDetails->id;
            $questionResponse[$payloadKey]["header_name"] = $headerDetails->header_type;
            foreach ($questionResponse[$payloadKey]["options"] as $optionKey => $optionValue) {
                foreach ($questionResponse[$payloadKey]["options"][$optionKey]["batch"] as $batchKey => $batchValue) {

                    $percentage = 0;
                    $questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["response"] = (!empty($questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["response"]) ? count($questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["response"]) : 0);
                    if ($questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["response"] != 0) {
                        $percentage = (($questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["response"] / count($questionResponse[$payloadKey]["options"][$optionKey]["totalResponse"][$batchValue["id"]])) * 100);
                    }
                    $questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["percentage"] = (string)number_format(round($percentage, 2), 2, '.', '');

                    $intensity = 0;

                    if (isset($questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["intensity"]) && !empty($questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["intensity"])) {

                        $intensity = (array_sum($questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["intensity"]) / $questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["response"]);
                    }
                    $questionResponse[$payloadKey]["options"][$optionKey]["batch"][$batchKey]["intensity"] = (string)number_format(round($intensity, 2), 2, '.', '');
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
            if (isset($optionValue["intensity_value"]) && !empty($optionValue["intensity_value"])) {
                $intensity_scale = explode(",", $optionValue["intensity_value"]);
                $intensity_scale = array_flip($intensity_scale);
            }
            $initialIntensity = ((isset($optionValue["initial_intensity"]) && !empty($optionValue["initial_intensity"])) ? $optionValue["initial_intensity"] : 1);
            $j = 0;
            foreach ($batchDetails as $batch) {
                $optValue[$optionCounter]["batch"][$j] = (array)$batch;
                if (!empty($getdbOptions)) {

                    foreach ($getdbOptions as $responseOption) {

                        if (!isset($optValue[$optionCounter]["totalResponse"][$responseOption->batch_id][$responseOption->profile_id])) {
                            $optValue[$optionCounter]["totalResponse"][$responseOption->batch_id][$responseOption->profile_id] = 1;
                        }

                        if (isset($responseOption->leaf_id) && $responseOption->leaf_id == $optionValue["id"] && $responseOption->batch_id == $batch->id) {
                            if (!isset($optValue[$optionCounter]["batch"][$j]["response"][$responseOption->profile_id])) {
                                $optValue[$optionCounter]["batch"][$j]["response"][$responseOption->profile_id] = 1;
                            }

                            if (isset($optionValue["is_intensity"]) && $optionValue["is_intensity"] == 1 && !empty($responseOption->intensity) && !empty($intensity_scale)) {
                                
                                $intensityFlag = true;
                                $optValue[$optionCounter]["batch"][$j]["intensity"][] = $intensity_scale[$responseOption->intensity] + $initialIntensity;
                            }
                        }
                    }
                }

                $j++;
            }

            $optionCounter++;
        }
    }

    public function getIfDefaultOptionsDoesntExists($question, $batchDetails, &$optionCounter, $intensity_scale, &$intensityFlag, &$optValue, $getdbOptions)
    {

        $prepArray = [];
        $arr = [];
        if ($getdbOptions) {

            $ifExists = [];
            // dd($getdbOptions);
            $previousOpt = "";
            foreach ($getdbOptions as $responseOption) {
                

                $prepArray[$responseOption->leaf_id]["id"] = $responseOption->leaf_id;
                $prepArray[$responseOption->leaf_id]["name"] = $responseOption->value;
                $prepArray[$responseOption->leaf_id]["batch"] = [];
                $j = 0;
                foreach ($batchDetails as $batch) {
                    if (!isset($prepArray[$responseOption->leaf_id]["totalResponse"][$responseOption->batch_id[$responseOption->profile_id]])) {
                        $prepArray[$responseOption->leaf_id]["totalResponse"][$responseOption->batch_id][$responseOption->profile_id] = 1;
                    }
                    $prepArray[$responseOption->leaf_id]["batch"][$j] =  (array)$batch;
                    if ($responseOption->batch_id == $batch->id) {
                        if (!isset($prepArray[$responseOption->leaf_id]["batch"][$j]["response"][$responseOption->profile_id])) {
                            $prepArray[$responseOption->leaf_id]["batch"][$j]["response"][$responseOption->profile_id] = 1;
                        }

                        if (isset($question["is_intensity"]) && $question["is_intensity"] == 1 && !empty($responseOption->intensity) && !empty($intensity_scale)) {

                            $intensityFlag = true;
                            $prepArray[$responseOption->leaf_id]["batch"][$j]["intensity"][] = $intensity_scale[$responseOption->intensity] + $question["initial_intensity"];
                        }
                    }
                    if (!isset($prepArray[$responseOption->leaf_id]["batch"][$j]["response"])) {
                        $prepArray[$responseOption->leaf_id]["batch"][$j]["response"] = 0;
                    }
                    if (!isset($prepArray[$responseOption->leaf_id]["batch"][$j]["intensity"])) {
                        $prepArray[$responseOption->leaf_id]["batch"][$j]["intensity"] = 0;
                    }
                    $j++;
                }

                if (!in_array($responseOption->leaf_id, $ifExists)) {
                    $ifExists[] = $responseOption->leaf_id;
                    $optValue[$optionCounter] = $prepArray[$responseOption->leaf_id];
                    $optionCounter++;
                } else {
                    $optValue[$optionCounter] = $prepArray[$responseOption->leaf_id];
                }
            }
            // dd($optValue);
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
            // if($question["id"]==57667){
            //     dd($optValue);
            // }
        }
        
        return $questionResponse;
    }

    public function graphCombination(Request $request, $collaborateId)
    {
        $combinationHeadList = $request["combination_header_list"];
        $batches = $this->getBatches($collaborateId);
        $filters = $request->input('filters');
        $profileIds = $this->getFilteredProfile($filters, $collaborateId)->toArray();
        foreach ($batches as $batch) {
            $totalApplicants[$batch->id] = \DB::table('collaborate_tasting_user_review')->where('value', '!=', '')->where('current_status', 3)->where('collaborate_id', $collaborateId)->where('batch_id', $batch->id)->distinct()->get(['profile_id'])->count();
        }
        $dataset = [];
        $dataset1 = [];
        $ques = [];
        foreach ($combinationHeadList as $value) {
            $item =  \DB::table('collaborate_tasting_questions')
                ->select(
                    'collaborate_tasting_questions.id',
                    'collaborate_tasting_questions.title',
                    'collaborate_tasting_questions.questions'
                )
                ->where('collaborate_tasting_questions.collaborate_id', $collaborateId)
                ->where('collaborate_tasting_questions.id', $value['que_id'])
                ->where('collaborate_tasting_questions.header_type_id', $value['id'])->first();
            if (!empty($item)) {
                $item->header_name  =  $value['header_name'];
                $ques[] = $item->id;
                $question = json_decode($item->questions);
                unset($item->questions);
                $dataset["question_list"][] = $item;
            }
        }
        if (!empty($ques)) {
            $dataset1['aroma_list'] = $question->nested_option_list;
            $dataset1['question_list'] = $dataset['question_list'];
            if (isset($question->intensity_value)) {
                $intensityValue = explode(",", $question->intensity_value);
            }
            $dataset['batch'] = [];
            if (!empty($batches)) {
                $batch = [];
                foreach ($batches as $singlebatch) {
                    $batch['id'] = $singlebatch->id;
                    $batch['batch_name'] = $singlebatch->batch_name;
                    $batch['is_intensity'] = isset($question->is_intensity)?true:false;
                    $options =  \DB::table('collaborate_tasting_user_review')->select('leaf_id','value')->where('batch_id',$singlebatch->id)->where('collaborate_id', $collaborateId)->whereIn('question_id', $ques);
                    if (!empty($profileIds) || (empty($profileIds) && !empty($filters))) {
                        $options = $options->whereIn('profile_id', $profileIds);
                    }
                    $options = $options->groupBy('value')->get();
                    $optionArray = [];
                    $batch['options'] = [];
                    if (!empty($options)) {

                        foreach ($options as $option) {
                            $optionArray["id"] = $option->leaf_id;
                            $optionArray["value"] = $option->value;
                            $optionArray["headers"] = [];
                            $headerArray = [];
                            foreach ($combinationHeadList as $header) {

                                $headerArray['id'] = (int)$header['id'];
                                $headerArray['header'] = $header['header_name'];

                                $response = \DB::table('collaborate_tasting_user_review')->where('value', $option->value)->where('collaborate_id', $collaborateId)
                                    ->where('tasting_header_id', $header['id'])
                                    ->where('batch_id', $singlebatch->id)->where('question_id', $header['que_id']);
                                if (!empty($profileIds)  || (empty($profileIds) && !empty($filters))) {
                                    $response = $response->whereIn('profile_id', $profileIds);
                                }
                                $responseCount = $response->pluck('value')->count();
                                $intensityArrray = $response->pluck('intensity')->toArray();

                                if ($totalApplicants[$singlebatch->id] != 0 && $responseCount) {     //if response exists ,and total applicants for batch is not 0
                                    $headerArray['percentage'] = (string)number_format(round((($responseCount / $totalApplicants[$singlebatch->id]) * 100), 2), 2, '.', '');
                                    $headerArray['responses'] = $responseCount;
                                    if ($question->is_intensity) {
                                        $answer = array_count_values(array_filter($intensityArrray));
                                        $intensities = array_flip($intensityValue);
                                        $sum = 0;
                                        foreach ($answer as $intensityName => $counOfIntensity) {
                                            $sum += $counOfIntensity * ($intensities[$intensityName]) + ($counOfIntensity * (isset($question->initial_intensity) ? $question->initial_intensity : 1));
                                        }
                                        
                                        $headerArray['intensity'] = (string)number_format(round(($sum / $totalApplicants[$singlebatch->id]), 2), 2, '.', '');
                                    }
                                } else {
                                    $headerArray['percentage'] = "0.00";
                                    $headerArray['responses'] = 0;
                                    $headerArray['intensity'] = "0.00";
                                }
                                $headerArray['color_code'] = $singlebatch->color_code;
                                array_push($optionArray['headers'], $headerArray);
                            }

                            array_push($batch['options'], $optionArray);
                        }
                    }
                    if(count($batch['options'])){
                    array_push($dataset['batch'], $batch);
                    }
                }
            }
            $dataset1['batch'] = $dataset['batch'];
        }



        $this->model = $dataset1;

        return $this->sendResponse();
    }
}
