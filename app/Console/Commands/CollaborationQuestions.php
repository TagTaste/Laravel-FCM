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

class CollaborationQuestions extends Command implements ShouldQueue
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $header = [];
    protected $signature = 'Collaboration:Question {id} {global_question_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert question in collaborate_tasting_questions';

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
        $id = $this->argument('id');
        $collaborateId = $id;
        $globalQuestionId = $this->argument('global_question_id');
        $questions = \DB::table('global_questions')->where('id',$globalQuestionId)->first();
        $data = $questions->header_info;
        $questions = $questions->question_json;
        $questions = json_decode($questions,true);
        $data = json_decode($data,true);
        $header = [];
        foreach ($data as &$datum)
        {
            $headerInfo = isset($datum['header_info']) ? $datum['header_info'] : null;
            $header[] = ['header_type'=>$datum['header_name'],'is_active'=>1,'header_info'=>isset($headerInfo) ? json_encode($headerInfo,true) : null,'collaborate_id'=>$collaborateId, 'header_selection_type'=>$datum['header_selection_type']];
        }
        Collaborate\ReviewHeader::insert($header);
        foreach ($questions as $key=>$question)
        {
            $data = [];
            $header = \DB::table('collaborate_tasting_header')->select('id')->where('header_type','like',$key)
                ->where('collaborate_id',$collaborateId)->first();
            $headerId = $header->id;
            \Log::info("header id ".$headerId);
            foreach ($question as $item)
            {
                $subtitle = isset($item['subtitle']) ? $item['subtitle'] : null;
                $subquestions = isset($item['question']) ? $item['question'] : [];
                $isNested = isset($item['is_nested_question']) && $item['is_nested_question'] == 1 ? 1 : 0;
                $isMandatory = isset($item['is_mandatory']) && $item['is_mandatory'] == 1 ? 1 : 0;
                $option = isset($item['option']) ? $item['option'] : null;
                if(isset($item['select_type']) && !is_null($option))
                {
                    $value = $item['option'];
                    if(is_string($value))
                    {
                        $value = explode(',',$option);
                        $option = [];
                        $i = 1;
                        foreach($value as $v){
                            if(is_null($v) || empty($v))
                                continue;
                            $option[] = [
                                'id' => $i,
                                'value' => $v
                            ];
                            $i++;
                        }
                    }
                    else
                    {
                        $option = [];
                        $i = 1;
                        foreach($value as $v){
                            if(!isset($v['value']))
                            {
                                continue;
                            }
                            $option[] = [
                                'id' => $i,
                                'value' => $v['value'],
                                'colorCode'=> isset($v['color_code']) ? $v['color_code'] : null,
                                'is_intensity'=>isset($v['is_intensity']) ? $v['is_intensity'] : null,
                                'intensity_type'=>isset($v['intensity_type']) ? $v['intensity_type'] : null,
                                'intensity_value'=>isset($v['intensity_value']) ? $v['intensity_value'] : null,
                                'intensity_color'=>isset($v['intensity_color'])?$v['intensity_color'] : null
                            ];
                            $i++;
                        }
                    }
                }
                else
                {
                    $value = explode(',',$option);
                    $option = [];
                    $i = 1;
                    foreach($value as $v){
                        if(is_null($v) || empty($v))
                            continue;
                        $option[] = [
                            'id' => $i,
                            'value' => $v
                        ];
                        $i++;
                    }
                }
                if(count($option))
                    $item['option'] = $option;
                unset($item['question']);
                $data = ['title'=>$item['title'],'subtitle'=>$subtitle,'is_nested_question'=>$isNested,'questions'=>json_encode($item,true),'parent_question_id'=>null,
                        'header_type_id'=>$headerId,'is_mandatory'=>$isMandatory,'is_active','collaborate_id'=>$collaborateId];
                \Log::info("question ");
                \Log::info($data);
                $x = Collaborate\Questions::create($data);

                $nestedOption = json_decode($x->questions);
                $extraQuestion = [];
                if(isset($nestedOption->is_nested_option))
                {
                    if($nestedOption->is_nested_option)
                    {

                        if(isset($nestedOption->nested_option_list))
                        {
//                            echo $nestedOption->nested_option_list;
                            $extra = \Db::table('global_nested_option')->where('is_active',1)->where('type','like',$nestedOption->nested_option_list)->get();
                            foreach ($extra as $nested)
                            {
                                $parentId = $nested->parent_id == 0 ? null : $nested->parent_id;
                                $description = isset($nested->description) ? $nested->description : null;
                                $nestedOptionIntensity = isset($nested->is_intensity) ? $nested->is_intensity : $nestedOption->is_intensity;
                                $imageUrl = isset($nested->image_url)?$nested->image_url:null;
                                $extraQuestion[] = ["sequence_id"=>$nested->s_no,'parent_id'=>$parentId,'value'=>$nested->value,'question_id'=>$x->id,'is_active'=>$nested->is_active,
                                    'collaborate_id'=>$collaborateId,'header_type_id'=>$headerId,'description'=>$description,'is_intensity'=>$nestedOptionIntensity,'image_url'=>$imageUrl];
                            }
                        }
                        else if(isset($nestedOption->nested_option_array))
                        {
                            $extra = $nestedOption->nested_option_array;
                            foreach ($extra as $nested)
                            {
                                $parentId = $nested->parent_id == 0 ? null : $nested->parent_id;
                                $description = isset($nested->description) ? $nested->description : null;
                                $nestedOptionIntensity = isset($nested->is_intensity) ? $nested->is_intensity : $nestedOption->is_intensity;
                                $extraQuestion[] = ["sequence_id"=>$nested->s_no,'parent_id'=>$parentId,'value'=>$nested->value,'question_id'=>$x->id,'is_active'=>$nested->is_active,
                                    'collaborate_id'=>$collaborateId,'header_type_id'=>$headerId,'description'=>$description,'is_intensity'=>$nestedOptionIntensity];
                            }
                        }
                        else
                        {
                            echo "something wrong in nested option value";
                            return 0;
                        }
//                        print_r($extraQuestion);
                        \DB::table('collaborate_tasting_nested_options')->insert($extraQuestion);


                        $paths = \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)->whereNull('parent_id')->get();

                        foreach ($paths as $path)
                        {
                            \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)
                                ->where('id',$path->id)->update(['path'=>$path->value]);
                        }
                        $questions = \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)->get();
                        $tr = 0;
                        foreach ($questions as $question)
                        {
                            echo "count is ".$tr."\n";
                            $checknestedIds = \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)
                                ->where('parent_id',$question->sequence_id)->get()->pluck('id');
                            if(count($checknestedIds))
                            {
                                $pathname =  \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)
                                    ->where('sequence_id',$question->sequence_id)->first();
                                \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)
                                    ->whereIn('id',$checknestedIds)->update(['path'=>$pathname->path]);
                                \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)
                                    ->where('id',$question->id)->update(['is_nested_option'=>1]);
                            }
                            $tr++;
                        }
                        $paths = \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)->whereNull('parent_id')->get();

                        foreach ($paths as $path)
                        {
                            \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)
                                ->where('id',$path->id)->update(['path'=>null]);
                        }
                    }
                }

                foreach ($subquestions as $subquestion)
                {
                    $subtitle = isset($subquestion['subtitle']) ? $subquestion['subtitle'] : null;
                    $isNested = isset($subquestion['is_nested_question']) && $subquestion['is_nested_question'] == 1 ? 1 : 0;
                    $isMandatory = isset($subquestion['is_mandatory']) && $subquestion['is_mandatory'] == 1 ? 1 : 0;
                    // for sub questions
                    $option = isset($subquestion['option']) ? $subquestion['option'] : null;
                    if(isset($subquestion['select_type']) && !is_null($option))
                    {
                        $value = $subquestion['option'];
                        if(is_string($value))
                        {
                            $value = explode(',',$option);
                            $option = [];
                            $i = 1;
                            foreach($value as $v){
                                if(is_null($v) || empty($v))
                                    continue;
                                $option[] = [
                                    'id' => $i,
                                    'value' => $v
                                ];
                                $i++;
                            }
                        }
                        else
                        {
                            $option = [];
                            $i = 1;
                            foreach($value as $v){
                                if(!isset($v['value']))
                                {
                                    continue;
                                }
                                $option[] = [
                                    'id' => $i,
                                    'value' => $v['value'],
                                    'colorCode'=> isset($v['color_code']) ? $v['color_code'] : null,
                                    'is_intensity'=>isset($v['is_intensity']) ? $v['is_intensity'] : null,
                                    'intensity_type'=>isset($v['intensity_type']) ? $v['intensity_type'] : null,
                                    'intensity_value'=>isset($v['intensity_value']) ? $v['intensity_value'] : null
                                ];
                                $i++;
                            }
                        }
                    }
                    else
                    {
                        $value = explode(',',$option);
                        $option = [];
                        $i = 1;
                        foreach($value as $v){
                            if(is_null($v) || empty($v))
                                continue;
                            $option[] = [
                                'id' => $i,
                                'value' => $v
                            ];
                            $i++;
                        }
                    }
                    if(count($option))
                        $subquestion['option'] = $option;
                    unset($subquestion['question']);
                    $subData = ['title'=>$subquestion['title'],'subtitle'=>$subtitle,'is_nested_question'=>$isNested,'questions'=>json_encode($subquestion,true),'parent_question_id'=>$x->id,
                        'header_type_id'=>$headerId,'is_mandatory'=>$isMandatory,'is_active'=>1,'collaborate_id'=>$collaborateId];
                    \Log::info("question sub ");
                    \Log::info($data);
                    Collaborate\Questions::create($subData);

                }
            }
        }
    }
}

