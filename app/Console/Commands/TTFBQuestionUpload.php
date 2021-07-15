<?php

namespace App\Console\Commands;

use App\Collaborate;
use App\Company;
use App\Events\NewFeedable;
use App\Job;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class TTFBQuestionUpload extends Command implements ShouldQueue
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $header = [];
    protected $signature = 'TTFB:Question {id} {question_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert question in ttfb_tasting_questions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            \DB::connection('mysql_ttfb')->beginTransaction();
            $id = $this->argument('id');
            $productId = $id;
            $globalQuestionId = $this->argument('question_id');
            $questions = \DB::connection("mysql_ttfb")->table('ttfb_global_questions')->where('id', $globalQuestionId)->first();
            $data = $questions->header_info;
            $questions = $questions->question_json;
            $questions = json_decode($questions, true);
            $data = json_decode($data, true);
            $header = [];
            foreach ($data as &$datum) {
                $headerInfo = isset($datum['header_info']) ? $datum['header_info'] : null;
                $header[] = ['header_type' => $datum['header_name'], 'is_active' => 1, 'header_info' => isset($headerInfo) ? json_encode($headerInfo, true) : null, 'product_id' => $productId, 'header_selection_type' => $datum['header_selection_type']];
            }
            \DB::connection("mysql_ttfb")->table('ttfb_tasting_header')->insert($header);
            foreach ($questions as $key => $question) {
                $data = [];
                $header = \DB::connection("mysql_ttfb")->table('ttfb_tasting_header')->select('id')->where('header_type', 'like', $key)
                    ->where('product_id', $productId)->first();
                $headerId = $header->id;
                // \Log::info("header id " . $headerId);
                foreach ($question as $item) {
                    $subtitle = isset($item['subtitle']) ? $item['subtitle'] : null;
                    $subquestions = isset($item['question']) ? $item['question'] : [];
                    $isNested = isset($item['is_nested_question']) && $item['is_nested_question'] == 1 ? 1 : 0;
                    $isMandatory = isset($item['is_mandatory']) && $item['is_mandatory'] == 1 ? 1 : 0;
                    $option = isset($item['option']) ? $item['option'] : null;
                    $trackConsistency = isset($item['track_consistency']) ? $item['track_consistency'] : 0;
                    $info = isset($item['info']) ? $item['info'] : null;
                    if (isset($item['select_type']) && !is_null($option)) {
                        $value = $item['option'];
                        if (is_string($value)) {
                            $value = explode(',', $option);
                            $option = [];
                            $i = 1;
                            foreach ($value as $v) {
                                if (is_null($v) || empty($v))
                                    continue;
                                if ($v == 'any other') {
                                    $oT = 1;
                                } else if ($v == 'none') {
                                    $oT = 2;
                                } else {
                                    $oT = 0;
                                }
                                $option[] = [
                                    'id' => $i,
                                    'value' => $v,
                                    'option_type' => $oT
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
                                    'intensity_color' => isset($v['intensity_color']) ? $v['intensity_color'] : null,
                                    'option_type' => isset($v['option_type']) ? $v['option_type'] : 0,

                                    // 'track_consistency' => isset($v['track_consistency']) ? $v['track_consistency'] : null,
                                    // 'intensity_consistency' => isset($v['intensity_consistency']) && isset($v['intensity_consistency']) ? $v['intensity_consistency'] : null,
                                    'benchmark_score' => isset($v['benchmark_score']) ? $v['benchmark_score'] : null,
                                    'tolerance' => isset($v['tolerance']) ? $v['tolerance'] : null,
                                    'benchmark_intensity' => (isset($v['is_intensity']) && isset($v['benchmark_intensity'])) ? $v['benchmark_intensity'] : null,
                                    'intensity_tolerance' => (isset($v['is_intensity']) && isset($v['intensity_tolerance'])) ? $v['intensity_tolerance'] : null,

                                    'image_url' => isset($v['image_url']) ? $v['image_url'] : null,
                                    'initial_intensity' => isset($v['initial_intensity']) ? $v['initial_intensity'] : null
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
                            if ($v == 'any other') {
                                $oT = 1;
                            } else if ($v == 'none') {
                                $oT = 2;
                            } else {
                                $oT = 0;
                            }
                            $option[] = [
                                'id' => $i,
                                'value' => $v,
                                'option_type' => $oT
                            ];
                            $i++;
                        }
                    }
                    if (count($option)) {
                        $item['option'] = $option;
                    }
                    unset($item['question']);
                    $data = [
                        'title' => $item['title'], 'subtitle' => $subtitle, 'is_nested_question' => $isNested, 'questions' => json_encode($item, true), 'parent_question_id' => null,
                        'header_type_id' => $headerId, 'is_mandatory' => $isMandatory, 'is_active' => 1, 'product_id' => $productId, 'track_consistency' => $trackConsistency
                    ];
                    // \Log::info("question ");
                    // \Log::info($data);
                    $y = \DB::connection("mysql_ttfb")->table('ttfb_tasting_questions')->insertGetId($data);
                    $x = 0;
                    if ($y) {
                        $x = \DB::connection("mysql_ttfb")->table('ttfb_tasting_questions')->where("id", "=", $y)->first();
                    }


                    $nestedOption = json_decode($x->questions);
                    $extraQuestion = [];
                    if (isset($nestedOption->is_nested_option)) {
                        if ($nestedOption->is_nested_option) {

                            if (isset($nestedOption->nested_option_list)) {
                                //                            echo $nestedOption->nested_option_list;
                                $extra = \DB::connection("mysql_ttfb")->table('ttfb_global_nested_option')->where('is_active', 1)->where('type', 'like', $nestedOption->nested_option_list)->get();
                                foreach ($extra as $nested) {
                                    $parentId = $nested->parent_id == 0 ? null : $nested->parent_id;
                                    $description = isset($nested->description) ? $nested->description : null;
                                    $nestedOptionIntensity = isset($nested->is_intensity) ? $nested->is_intensity : $nestedOption->is_intensity;
                                    $imageUrl = isset($nested->image_url) ? $nested->image_url : null;
                                    $optionType = $nested->option_type;
                                    if (isset($nestedOption->track_consistency) && $nested->s_no == $nestedOption->nested_option_consistency) {
                                        $trackConsistency = 1;
                                        //$benchmarkConsistency = $nestedOption->benchmark_consistency;
                                    } else
                                        $trackConsistency = 0;
                                    $extraQuestion[] = [
                                        "sequence_id" => $nested->s_no, 'parent_id' => $parentId, 'value' => $nested->value, 'question_id' => $x->id, 'is_active' => $nested->is_active,
                                        'product_id' => $productId, 'header_type_id' => $headerId, 'description' => $description, 'is_intensity' => $nestedOptionIntensity, 'image_url' => $imageUrl, 'option_type' => $optionType, 'track_consistency' => $trackConsistency
                                    ];
                                }
                            } else if (isset($nestedOption->nested_option_array)) {
                                $extra = $nestedOption->nested_option_array;
                                foreach ($extra as $nested) {
                                    $parentId = $nested->parent_id == 0 ? null : $nested->parent_id;
                                    $description = isset($nested->description) ? $nested->description : null;
                                    $nestedOptionIntensity = isset($nested->is_intensity) ? $nested->is_intensity : $nestedOption->is_intensity;
                                    $extraQuestion[] = [
                                        "sequence_id" => $nested->s_no, 'parent_id' => $parentId, 'value' => $nested->value, 'question_id' => $x->id, 'is_active' => $nested->is_active,
                                        'product_id' => $productId, 'header_type_id' => $headerId, 'description' => $description, 'is_intensity' => $nestedOptionIntensity
                                    ];
                                }
                            } else {
                                echo "something wrong in nested option value";
                                return false;
                            }
                            //                        print_r($extraQuestion);
                            \DB::connection("mysql_ttfb")->table('ttfb_tasting_nested_options')->insert($extraQuestion);


                            $paths = \DB::connection("mysql_ttfb")->table('ttfb_tasting_nested_options')->where('question_id', $x->id)->where('product_id', $productId)->whereNull('parent_id')->get();

                            foreach ($paths as $path) {
                                \DB::connection("mysql_ttfb")->table('ttfb_tasting_nested_options')->where('question_id', $x->id)->where('product_id', $productId)
                                    ->where('id', $path->id)->update(['path' => $path->value, 'parent_sequence_id' => $path->sequence_id]);
                            }
                            $questions = \DB::connection("mysql_ttfb")->table('ttfb_tasting_nested_options')->where('question_id', $x->id)->where('product_id', $productId)->get();
                            $tr = 0;
                            foreach ($questions as $question) {
                                //echo "count is ".$tr."\n";
                                $checknestedIds = \DB::connection("mysql_ttfb")->table('ttfb_tasting_nested_options')->where('question_id', $x->id)->where('product_id', $productId)
                                    ->where('parent_id', $question->sequence_id)->get()->pluck('id');
                                if (count($checknestedIds)) {
                                    $pathname =  \DB::connection("mysql_ttfb")->table('ttfb_tasting_nested_options')->where('question_id', $x->id)->where('product_id', $productId)
                                        ->where('sequence_id', $question->sequence_id)->first();
                                    \DB::connection("mysql_ttfb")->table('ttfb_tasting_nested_options')->where('question_id', $x->id)->where('product_id', $productId)
                                        ->whereIn('id', $checknestedIds)->update(['path' => $pathname->path, 'parent_sequence_id' => $pathname->parent_sequence_id]);
                                    \DB::connection("mysql_ttfb")->table('ttfb_tasting_nested_options')->where('question_id', $x->id)->where('product_id', $productId)
                                        ->where('id', $question->id)->update(['is_nested_option' => 1]);
                                }
                                $tr++;
                            }
                            $paths = \DB::connection("mysql_ttfb")->table('ttfb_tasting_nested_options')->where('question_id', $x->id)->where('product_id', $productId)->whereNull('parent_id')->get();

                            foreach ($paths as $path) {
                                \DB::connection("mysql_ttfb")->table('ttfb_tasting_nested_options')->where('question_id', $x->id)->where('product_id', $productId)
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
                                    if ($v == 'any other') {
                                        $oT = 1;
                                    } else if ($v == 'none') {
                                        $oT = 2;
                                    } else {
                                        $oT = 0;
                                    }
                                    $option[] = [
                                        'id' => $i,
                                        'value' => $v,
                                        'optionType' => $oT,
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
                                        'image_url' => isset($v['image_url']) ? $v['image_url'] : null,
                                        'initial_intensity' => isset($v['initial_intensity']) ? $v['initial_intensity'] : null
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
                                if ($v == 'any other') {
                                    $oT = 1;
                                } else if ($v == 'none') {
                                    $oT = 2;
                                } else {
                                    $oT = 0;
                                }
                                $option[] = [
                                    'id' => $i,
                                    'value' => $v,
                                    'option_type' => $oT,
                                    'image_url' => isset($v['image_url']) ? $v['image_url'] : null
                                ];
                                $i++;
                            }
                        }
                        if (count($option))
                            $subquestion['option'] = $option;
                        unset($subquestion['question']);
                        $subData = [
                            'title' => $subquestion['title'], 'subtitle' => $subtitle, 'is_nested_question' => $isNested, 'questions' => json_encode($subquestion, true), 'parent_question_id' => $x->id,
                            'header_type_id' => $headerId, 'is_mandatory' => $isMandatory, 'is_active' => 1, 'product_id' => $productId
                        ];
                        // \Log::info("question sub ");
                        // \Log::info($data);
                        \DB::connection("mysql_ttfb")->table('ttfb_tasting_questions')->insert($subData);
                    }
                }
            }
            \DB::connection('mysql_ttfb')->commit();
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage() . " " . $e->getLine() . " " . $e->getFile();
            \DB::connection('mysql_ttfb')->rollback();
            return false;
        }
    }
}
