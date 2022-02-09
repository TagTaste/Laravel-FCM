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

        $resp = $this->getFilteredProfile($filters, $collaborateId);


        foreach ($getQuestions as $questionList) {
            $decodeJsonOfQuestions = json_decode($questionList->questions, true);

            if (json_last_error() == JSON_ERROR_NONE && isset($decodeJsonOfQuestions["create_graph"]) && $decodeJsonOfQuestions["create_graph"]) {
                if (isset($decodeJsonOfQuestions["merge_graph"]) && $decodeJsonOfQuestions["merge_graph"] == true) {
                    $questionSet["merged"][] = ["id" => $questionList->id, "title" => $questionList->title, "is_intensity" => $decodeJsonOfQuestions["is_intensity"]];
                } else {
                    $questionSet[] = ["id" => $questionList->id, "title" => $questionList->title, "is_intensity" => $decodeJsonOfQuestions["is_intensity"]];
                }
            }
        }

        $optionList = $this->getOptions($questionSet, $collaborateId, $headerId);
        dd($optionList);
    }

    public function getOptions($questionArray = [], $collaborateId, $headerId)
    {
        $optionList = [];

        foreach ($questionArray as $key => $value) {
            if ($key === "merged" && is_array($value)) {
                $questionArray[$key]["options"] = [];
                foreach ($value as $mergedKey => $mergedValue) {

                    $getOptions = DB::table('collaborate_tasting_user_review')->where("question_id", $mergedValue["id"])->where("tasting_header_id", $headerId)->where("collaborate_id", $collaborateId)->where("current_status", 3)->select(["id", "key", "value", "value_id", "leaf_id", "intensity", "batch_id", "profile_id"])->get();
                    $opt = [];
                    foreach ($getOptions as $optionDetails) {
                        $opt[$optionDetails->value]["id"] = $optionDetails->leaf_id;
                        $opt[$optionDetails->value]["value"] = $optionDetails->value;
                        $opt[$optionDetails->value]["batch"][$optionDetails->batch_id]["id"] = $optionDetails->batch_id;
                        $opt[$optionDetails->value]["batch"][$optionDetails->batch_id]["response"][] = $optionDetails->profile_id;
                    }
                }
                $questionArray[$key]["options"][] = $opt;
                if (!empty($questionArray[$key]["options"])) {
                    $questionArray[$key]["options"] = array_merge(...$questionArray[$key]["options"]);
                }
            } else {
                $getOptions = DB::table('collaborate_tasting_user_review')->where("question_id", $value["id"])->where("tasting_header_id", $headerId)->where("collaborate_id", $collaborateId)->where("current_status", 3)->select(["id", "key", "value", "value_id", "leaf_id", "intensity", "batch_id", "profile_id"])->groupBy("value")->get()->toArray();

                $opt = [];
                foreach ($getOptions as $optionDetails) {
                    
                    $opt[$optionDetails->value]["id"] = $optionDetails->leaf_id;
                    $opt[$optionDetails->value]["value"] = $optionDetails->value;
                    $opt[$optionDetails->value]["batch"][$optionDetails->batch_id]["id"] = $optionDetails->batch_id;
                    $opt[$optionDetails->value]["batch"][$optionDetails->batch_id]["response"][] = $optionDetails->profile_id;
                }
                $questionArray[$key]["options"] = $opt;
            }
        }
        return $questionArray;
    }
    public function graph(Request $request, $collaborateId, $headerId)
    {
        $data = \DB::table("collaborate_tasting_questions")->select('collaborate_tasting_questions.id', 'questions', 'collaborate_tasting_header.header_type', 'collaborate_tasting_questions.header_type_id as headId', 'collaborate_tasting_questions.title')
            ->join('collaborate_tasting_header', 'collaborate_tasting_header.id', 'collaborate_tasting_questions.header_type_id')
            ->where('collaborate_tasting_questions.collaborate_id', $collaborateId)
            ->where('collaborate_tasting_questions.header_type_id', $headerId)
            ->get();
        $batches = \DB::table('collaborate_batches')->select('id', 'name')->where('collaborate_id', $collaborateId)->get();
        $filters = $request->input('filters');
        $resp = $this->getFilteredProfile($filters, $collaborateId);
        $profileIds = $resp; //dd($profileIds);

        $totalApplicants = [];
        foreach ($batches as $batch) {
            $totalApplicants[$batch->id] = \DB::table('collaborate_tasting_user_review')->where('value', '!=', '')->where('current_status', 3)->where('collaborate_id', $collaborateId);
            if (!empty($profileIds)) {
                $totalApplicants[$batch->id] = $totalApplicants[$batch->id]->whereIn('profile_id', $profileIds);
            }
            $totalApplicants[$batch->id] = $totalApplicants[$batch->id]->where('batch_id', $batch->id)->distinct()->get(['profile_id'])->count();
        }

        $merged['options'] = [];
        $aromaList = [];
        $dataset = [];
        // dd($data);
        foreach ($data as $value) {
            $single = [];
            $question = json_decode($value->questions);
            if (isset($question->create_graph) && $question->create_graph) {  //create_graph=1
                $ques = [];
                $ques['id'] = $value->id;
                $ques['title'] = $value->title;
                //not merged questions
                $single['header_name'] = $value->header_type;
                $single['header_id'] = $value->headId;
                $single['ques_list'] = $ques;



                if ($question->is_intensity) {
                    $single['is_intensity'] = true;
                } else {
                    $single['is_intensity'] = false;
                }

                $optionArray = [];
                $options = [];
                //get it from review only 


                if (isset($question->option) && $question->option) {    //normal options 
                    $options = $question->option;
                } else if (isset($question->is_nested_option) && $question->is_nested_option == 1) {   //nested options
                    $options =  \DB::table('collaborate_tasting_nested_options')->where('collaborate_id', $collaborateId)->where('header_type_id', $headerId)->where('question_id', $value->id)->get();
                    $intensityValue = explode(",", $question->intensity_value);
                    $intensityCount = count($intensityValue);
                }

                $single['options'] = [];
                $i =  1;
                foreach ($options as $option) {
                    if (isset($question->is_nested_option) && $question->is_nested_option == 1) {
                        $i = $option->id;
                    }
                    $optionArray['id'] = $i;
                    $optionArray['value'] = $option->value;
                    if (isset($question->option)) {     //for normal options ,calculate intensity values
                        if ($option->is_intensity) {
                            $intensityValue = explode(",", $option->intensity_value);
                            $intensityCount = count($intensityValue);
                        }
                    }
                    if (count($batches) > 0) {
                        $optionArray['product'] = [];
                        $batchArray = [];
                        foreach ($batches as $v) {
                            $batchArray['id'] = $v->id;
                            $batchArray['batch_name'] = $v->name;
                            $count = \DB::table('collaborate_tasting_user_review')->where('leaf_id', $i)->where('collaborate_id', $collaborateId)
                                ->where('batch_id', $v->id)->where('question_id', $value->id);
                            if (!empty($profileIds)) {
                                $count =  $count->whereIn('profile_id', $profileIds);
                            }
                            $count = $count->get()->pluck('intensity')->toArray();
                            if ($totalApplicants[$v->id] != 0 && count($count)) {  //if there are any responses
                                $batchArray['percentage'] = (count($count) / $totalApplicants[$v->id]) * 100;
                                $batchArray['responses'] = count($count);
                                if ($option->is_intensity) {
                                    $answer = array_count_values(array_filter($count));
                                    $intensities = array_flip($intensityValue);
                                    $sum = 0;
                                    foreach ($answer as $k => $vv) {
                                        $sum += $vv * $intensities[$k];
                                    }
                                    $sum += $intensityCount;
                                    $batchArray['intensity'] = $sum / $intensityCount;
                                }
                            } else {
                                $batchArray['percentage'] = 0;
                                $batchArray['responses'] = 0;
                                $batchArray['intensity'] = 0;
                            }
                            if (count($batchArray) > 0)  array_push($optionArray['product'], $batchArray);
                        }
                    }
                    if (count($optionArray) > 0) array_push($single['options'], $optionArray);
                    $i++;
                }
                if (isset($question->merge_graph) &&  $question->merge_graph) {   //for merged questions
                    $aroma = 0;
                    if (isset($question->is_nested_option) && $question->is_nested_option == 1) {
                        $aroma++;
                        $aromaList[$question->nested_option_list][] = $value->id;
                    }
                    $merged['header_name'] = $value->header_type;
                    $merged['header_id'] = $value->headId;
                    $merged['question_list'][] = $ques;
                    if ($question->is_intensity) {
                        $merged['is_intensity'] = true;
                    } else {
                        $merged['is_intensity'] = false;
                    }
                    if (count($single['options']) && $aroma == 0) {  //for questions having normal options ,adding options to merged array
                        array_push($merged['options'], $single['options']);
                    }
                } else { //for non merged questions,simply add individual node  to dataset

                    if (count($single) > 0)  array_push($dataset, $single);
                }
            }
        }
        if (count($aromaList) > 0) {   //if there are any questions having common aroma list
            $data['collaborate_id'] = $collaborateId;
            $data['header_id'] = $headerId;
            $data['profile'] = $resp;
            $data['batches'] = $batches;
            $merged2 = $this->graphAromaList($aromaList, $merged, $totalApplicants, $data);
            // dd($merged2);
        }

        if (count($aromaList)) {
            if (count($merged['options'])) { //if common aroma list and also merged questions with normal options
                $merged2['options'] = array_merge($merged2['options'], $merged['options'][0]);
            }
            array_push($dataset, $merged2);
        } else if (isset($merged['header_id'])) {  //no questions having common aroma list,simply add meged array to dataset
            array_push($dataset, $merged);
        }
        $this->model = $dataset;

        return $this->sendResponse();
    }

    public function graphAromaList($aromaList, $merged, $totalApplicants, $data)
    {
        $co = 0;
        foreach ($aromaList as $key => $value) {
            if ($co == 0) {
                $merged2['header_name'] = $merged['header_name'];
                $merged2['header_id'] = $merged["header_id"];
                $merged2['question_list'] = $merged["question_list"];
                if ($merged['is_intensity']) {
                    $merged2['is_intensity'] = true;
                } else {
                    $merged2['is_intensity'] = false;
                }
                $merged2['options'] = [];
                $co++;
            }
            $questions =  \DB::table('collaborate_tasting_questions')->select('questions')->where('collaborate_id', $data['collaborate_id'])->where('header_type_id', $data['header_id'])->where('id', $value[0])->first();
            //dd($questions);
            $question = json_decode($questions->questions);
            $options =  \DB::table('collaborate_tasting_nested_options')->where('collaborate_id', $data['collaborate_id'])->where('header_type_id',  $data['header_id'])->where('question_id', $value[0])->get();
            $intensityValue = explode(",", $question->intensity_value);
            $intensityCount = count($intensityValue);
            $optionArray = [];
            if (count($options) && $co > 0) {
                $merged2['options'] = [];
            }
            foreach ($options as $option) {
                $optionArray['id'] = $option->id;
                $optionArray['value'] = $option->value;

                if (!empty($data['batches'])) {
                    $optionArray['product'] = [];
                    $batchArray = [];
                    foreach ($data['batches'] as $v) {
                        $batchArray['id'] = $v->id;
                        $batchArray['batch_name'] = $v->name;
                        $count = \DB::table('collaborate_tasting_user_review')->where('leaf_id', $option->id)->where('collaborate_id', $data['collaborate_id'])
                            ->where('tasting_header_id', $data['header_id'])
                            ->where('batch_id', $v->id)->whereIn('question_id', $value)
                            ->whereIn('profile_id', $data['profile']['profile_id'], 'and', $data['profile']['type'])->get()->pluck('intensity')->toArray();

                        if ($totalApplicants[$v->id] != 0 && count($count)) {
                            $batchArray['percentage'] = (count($count) / $totalApplicants[$v->id]) * 100;
                            $batchArray['responses'] = count($count);
                            if ($option->is_intensity) {
                                $answer = array_count_values(array_filter($count));
                                $intensities = array_flip($intensityValue);
                                $sum = 0;
                                foreach ($answer as $k => $vv) {
                                    $sum += $vv * $intensities[$k];
                                }
                                $sum += $intensityCount;
                                $batchArray['intensity'] = $sum / $intensityCount;
                            }
                        } else {
                            $batchArray['percentage'] = 0;
                            $batchArray['responses'] = 0;
                            $batchArray['intensity'] = 0;
                        }


                        if (count($batchArray) > 0)  array_push($optionArray['product'], $batchArray);
                    }
                }
                if (count($optionArray) > 0) array_push($merged2['options'], $optionArray);
            }
        }
        return $merged2;
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
        if (!empty($batches)) {
            $dataset['batch'] = [];
            $batch = [];
            foreach ($batches as $v) {
                $batch['id'] = $v->id;
                $batch['batch_name'] = $v->name;
                $batch['is_intensity'] = $question->is_intensity;
                $options =  \DB::table('collaborate_tasting_nested_options')->where('collaborate_id', $collaborateId)->where('header_type_id', $value['id'])->where('question_id', $item->id)->get();
                if (!empty($options)) {
                    $optionArray = [];
                    $batch['options'] = [];
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
