<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PublicReviewProduct\Questions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tagtaste\Api\SendsJsonResponse;

class QuestionnaireController extends Controller
{
    use SendsJsonResponse;
    public function index($type, Request $request)
    {

        if ($type == "public") {

            $validator = Validator::make($request->all(), [
                'track_consistency' => 'required',
                'title' => 'required',
                'description' => 'nullable',
                'keyword' => 'required',
                'header_info' => 'required|array',
                'question_json' => 'required|array'
            ]);
            if ($validator->fails()) {
                $this->errors = $validator->messages();
                return $this->sendResponse();
            }


            $data = [
                'name' => 'Taste_appreciation_pears', 'keywords' => "Taste_appreciation_pears", 'description' => null,

                'question_json' => json_encode($request->question_json, JSON_NUMERIC_CHECK), 'header_info' => json_encode($request->header_info, JSON_NUMERIC_CHECK)
            ];
            DB::table('public_review_global_questions')->insert($data);

            $globalQuestion = DB::table('public_review_global_questions')->orderBy('id', 'desc')->first();

            DB::table('public_review_global_questions')->insert($data);

            $headerData = [];
            // header_selection_type
            // for instruction = 0  , overall preferance = 2 others = 1
            $header = json_decode($request->header_info, true);
            foreach ($header as $item) {
                $headerData[] = [
                    'header_type' => $item['header_name'], 'is_active' => 1, 'header_selection_type' => $item['header_selection_type'],
                    'global_question_id' => $globalQuestion->id, 'header_info' => isset($item['header_info']) ? json_encode($item['header_info']) : null
                ];
            }
            Log::info($headerData);
            DB::table('public_review_question_headers')->insert($headerData);

            $questions = $request->question_json;
            $questions = json_decode($questions, true);

            foreach ($questions as $key => $question) {
                $data = [];
                $header = DB::table('public_review_question_headers')->select('id')->where('header_type', 'like', $key)
                    ->where('global_question_id', $globalQuestion->id)->first();
                $headerId = $header->id;
                Log::info("header id " . $headerId);
                foreach ($question as $item) {
                    $subtitle = isset($item['subtitle']) ? $item['subtitle'] : null;
                    $subquestions = isset($item['question']) ? $item['question'] : [];
                    $isNested = isset($item['is_nested_question']) && $item['is_nested_question'] == 1 ? 1 : 0;
                    $isMandatory = isset($item['is_mandatory']) && $item['is_mandatory'] == 1 ? 1 : 0;
                    $option = isset($item['option']) ? $item['option'] : null;

                    if (isset($item['select_type']) && !is_null($option)) {
                        $value = $item['option'];
                        if (is_string($value)) {
                            $value = explode(',', $option);
                            $option = [];
                            $i = 1;
                            foreach ($value as $v) {
                                if (is_null($v) || empty($v))
                                    continue;
                                if ($v == 'Any other' || $v == 'any other')
                                    $option_type = 1;
                                else if ($v == 'none' || $v == 'None')
                                    $option_type = 2;
                                else
                                    $option_type = 0;
                                $option[] = [
                                    'id' => $i,
                                    'value' => $v,
                                    'option_type' => $option_type
                                ];
                                $i++;
                            }
                        } else {
                            $option = [];
                            $i = 1;
                            foreach ($value as $v) {
                                if (!isset($v['value'])) {
                                    continue;
                                }
                                $option[] = [
                                    'id' => $i,
                                    'value' => $v['value'],
                                    'colorCode' => isset($v['color_code']) ? $v['color_code'] : null,
                                    'is_intensity' => isset($v['is_intensity']) ? $v['is_intensity'] : null,
                                    'intensity_type' => isset($v['intensity_type']) ? $v['intensity_type'] : null,
                                    'intensity_value' => isset($v['intensity_value']) ? $v['intensity_value'] : null,
                                    'option_type' => isset($v['option_type']) ? $v['option_type'] : 0,
                                    'image_url' => isset($v['image_url']) ? $v['image_url'] : null
                                ];
                                $i++;
                            }
                        }
                    } else {
                        $value = explode(',', $option);
                        $option = [];
                        $i = 1;
                        foreach ($value as $v) {
                            if (is_null($v) || empty($v))
                                continue;
                            if ($v == 'Any other' || $v == 'any other')
                                $option_type = 1;
                            else if ($v == 'none' || $v == 'None')
                                $option_type = 2;
                            else
                                $option_type = 0;
                            $option[] = [
                                'id' => $i,
                                'value' => $v,
                                'option_type' => $option_type
                            ];
                            $i++;
                        }
                    }
                    if (count($option))
                        $item['option'] = $option;
                    unset($item['question']);
                    $data = [
                        'title' => $item['title'], 'subtitle' => $subtitle, 'is_nested_question' => $isNested,
                        'questions' => json_encode($item, true), 'parent_question_id' => null,
                        'header_id' => $headerId, 'is_mandatory' => $isMandatory, 'is_active', 'global_question_id' => $globalQuestion->id
                    ];
                    Log::info("question ");
                    Log::info($data);
                    $x = Questions::create($data);

                    $nestedOption = json_decode($x->questions);
                    $extraQuestion = [];
                    if (isset($nestedOption->is_nested_option)) {
                        if ($nestedOption->is_nested_option) {

                            if (isset($nestedOption->nested_option_list)) {
                                echo $nestedOption->nested_option_list;
                                $extra = DB::table('public_review_global_nested_option')->where('is_active', 1)->where('type', 'like', $nestedOption->nested_option_list)->get();
                                foreach ($extra as $nested) {
                                    $parentId = $nested->parent_id == 0 ? null : $nested->parent_id;
                                    $description = isset($nested->description) ? $nested->description : null;
                                    $option_type = isset($nested->option_type) ? $nested->option_type : 0;
                                    $extraQuestion[] = [
                                        "sequence_id" => $nested->s_no, 'parent_id' => $parentId, 'value' => $nested->value, 'question_id' => $x->id,
                                        'is_active' => 1, 'global_question_id' => $globalQuestion->id, 'header_id' => $headerId, 'description' => $description, 'is_intensity' => $nested->is_intensity, 'option_type' => $option_type
                                    ];
                                }
                            } else if (isset($nestedOption->nested_option_array)) {
                                $extra = $nestedOption->nested_option_array;
                                foreach ($extra as $nested) {
                                    $parentId = $nested->parent_id == 0 ? null : $nested->parent_id;
                                    $description = isset($nested->description) ? $nested->description : null;
                                    $extraQuestion[] = [
                                        "sequence_id" => $nested->s_no, 'parent_id' => $parentId, 'value' => $nested->value, 'question_id' => $x->id,
                                        'is_active' => $nested->is_active, 'global_question_id' => $globalQuestion->id, 'header_id' => $headerId,
                                        'description' => $description, 'is_intensity' => $nested->is_intensity, 'option_type' => 0
                                    ];
                                }
                            } else {
                                echo "something wrong in nested option value";
                                return 0;
                            }
                            // print_r($extraQuestion);
                            DB::table('public_review_nested_options')->insert($extraQuestion);


                            $paths = DB::table('public_review_nested_options')->where('question_id', $x->id)->where('global_question_id', $globalQuestion->id)
                                ->whereNull('parent_id')->get();

                            foreach ($paths as $path) {
                                DB::table('public_review_nested_options')->where('question_id', $x->id)->where('global_question_id', $globalQuestion->id)
                                    ->where('id', $path->id)->update(['path' => $path->value, 'parent_sequence_id' => $path->sequence_id]);
                            }
                            $questions = DB::table('public_review_nested_options')->where('question_id', $x->id)->where(
                                'global_question_id',
                                $globalQuestion->id
                            )->get();

                            foreach ($questions as $question) {
                                $checknestedIds = DB::table('public_review_nested_options')->where('question_id', $x->id)
                                    ->where('global_question_id', $globalQuestion->id)
                                    ->where('parent_id', $question->sequence_id)->get()->pluck('id');

                                if (count($checknestedIds)) {
                                    $pathname =  DB::table('public_review_nested_options')->where('question_id', $x->id)
                                        ->where('global_question_id', $globalQuestion->id)
                                        ->where('sequence_id', $question->sequence_id)->first();
                                    DB::table('public_review_nested_options')->where('question_id', $x->id)->where('global_question_id', $globalQuestion->id)
                                        ->whereIn('id', $checknestedIds)->update(['path' => $pathname->path, 'parent_sequence_id' => $pathname->parent_sequence_id]);
                                    DB::table('public_review_nested_options')->where('question_id', $x->id)->where('global_question_id', $globalQuestion->id)
                                        ->where('id', $question->id)->update(['is_nested_option' => 1]);
                                }
                            }
                            $paths = DB::table('public_review_nested_options')->where('question_id', $x->id)
                                ->where('global_question_id', $globalQuestion->id)->whereNull('parent_id')->get();

                            foreach ($paths as $path) {
                                DB::table('public_review_nested_options')->where('question_id', $x->id)->where('global_question_id', $globalQuestion->id)
                                    ->where('id', $path->id)->update(['path' => null, 'parent_sequence_id' => null]);
                            }
                        }
                    }

                    foreach ($subquestions as $subquestion) {
                        $subtitle = isset($subquestion['subtitle']) ? $subquestion['subtitle'] : null;
                        $isNested = isset($subquestion['is_nested_question']) && $subquestion['is_nested_question'] == 1 ? 1 : 0;
                        $isMandatory = isset($subquestion['is_mandatory']) && $subquestion['is_mandatory'] == 1 ? 1 : 0;
                        // for sub questions
                        $option = isset($subquestion['option']) ? $subquestion['option'] : null;
                        if (isset($subquestion['select_type']) && !is_null($option)) {
                            $value = $subquestion['option'];
                            if (is_string($value)) {
                                $value = explode(',', $option);
                                $option = [];
                                $i = 1;
                                foreach ($value as $v) {
                                    if (is_null($v) || empty($v))
                                        continue;
                                    if ($v == 'Any other' || $v == 'any other')
                                        $option_type = 1;
                                    else if ($v == 'none' || $v == 'None')
                                        $option_type = 2;
                                    else
                                        $option_type = 0;
                                    $option[] = [
                                        'id' => $i,
                                        'value' => $v,
                                        'option_type' => $option_type,
                                        'image_url' => isset($v['image_url']) ? $v['image_url'] : null
                                    ];
                                    $i++;
                                }
                            } else {
                                $option = [];
                                $i = 1;
                                foreach ($value as $v) {
                                    if (!isset($v['value'])) {
                                        continue;
                                    }
                                    $option[] = [
                                        'id' => $i,
                                        'value' => $v['value'],
                                        'colorCode' => isset($v['color_code']) ? $v['color_code'] : null,
                                        'is_intensity' => isset($v['is_intensity']) ? $v['is_intensity'] : null,
                                        'intensity_type' => isset($v['intensity_type']) ? $v['intensity_type'] : null,
                                        'intensity_value' => isset($v['intensity_value']) ? $v['intensity_value'] : null,
                                        'option_type' => isset($v['option_type']) ? $v['option_type'] : 0,
                                        'image_url' => isset($v['image_url']) ? $v['image_url'] : null
                                    ];
                                    $i++;
                                }
                            }
                        } else {
                            $value = explode(',', $option);
                            $option = [];
                            $i = 1;
                            foreach ($value as $v) {
                                if (is_null($v) || empty($v))
                                    continue;
                                if ($v == 'Any other' || $v == 'any other')
                                    $option_type = 1;
                                else if ($v == 'none' || $v == 'None')
                                    $option_type = 2;
                                else
                                    $option_type = 0;
                                $option[] = [
                                    'id' => $i,
                                    'value' => $v,
                                    'option_type' => $option_type,
                                    'image_url' => isset($v['image_url']) ? $v['image_url'] : null
                                ];
                                $i++;
                            }
                        }
                        if (count($option))
                            $subquestion['option'] = $option;
                        unset($subquestion['question']);
                        $subData = [
                            'title' => $subquestion['title'], 'subtitle' => $subtitle, 'is_nested_question' => $isNested,
                            'questions' => json_encode($subquestion, true), 'parent_question_id' => $x->id,
                            'header_id' => $headerId, 'is_mandatory' => $isMandatory, 'is_active' => 1, 'global_question_id' => $globalQuestion->id
                        ];
                        Log::info("question sub ");
                        Log::info($subData);
                        Questions::create($subData);
                    }
                }
            }
        } else if ($type == "private") {
            $validator = Validator::make($request->all(), [
                'track_consistency' => 'required',
                'title' => 'required',
                'description' => 'nullable',
                'keyword' => 'required',
                'header_info' => 'required|array',
                'question_json' => 'required|array'
            ]);
            if ($validator->fails()) {
                $this->errors = $validator->messages();
                return $this->sendResponse();
            }


            $data = [
                'name' => $request->title, 'keywords' => $request->keyword, 'description' => $request->description ?? null,
                'question_json' => json_encode($request->question_json, JSON_NUMERIC_CHECK), 'header_info' => json_encode($request->header_info, JSON_NUMERIC_CHECK), 'track_consistency' => $request->track_consistency
            ];
            $this->model = DB::table('global_questions')->insert($data);
            return $this->sendResponse();
        }


        return $this->sendError("Invalid Type");
    }
}
