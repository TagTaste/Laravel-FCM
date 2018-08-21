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
    protected $signature = 'Collaboration:Question {id}';

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
        $questions = \DB::table('global_questions')->where('id',3)->first();
        $questions = $questions->question_json;
        $questions = json_decode($questions,true);
        $data = [];
        $data[] = ['header_type'=>'INSTRUCTION','is_active'=>1,'collaborate_id'=>$id,'header_info'=>'In information technology, header refers to supplemental data placed at the '];
        $data[] = ['header_type'=>'APPEARANCE','is_active'=>1,'collaborate_id'=>$id,'header_info'=>'In information technology, header refers to supplemental data placed at the '];
        $data[] = ['header_type'=>'AROMA','is_active'=>1,'collaborate_id'=>$id,'header_info'=>'In information technology, header refers to supplemental data placed at the '];
        $data[] = ['header_type'=>'TASTE','is_active'=>1,'collaborate_id'=>$id,'header_info'=>'In information technology, header refers to supplemental data placed at the '];
        $data[] = ['header_type'=>'AROMATICS','is_active'=>1,'collaborate_id'=>$id,'header_info'=>'In information technology, header refers to supplemental data placed at the '];
        $data[] = ['header_type'=>'TEXTURE','is_active'=>1,'collaborate_id'=>$id,'header_info'=>'In information technology, header refers to supplemental data placed at the beginning of a block of data being stored or transmitted. In data transmission, the data following the header are sometimes called the payload or body.'];
        $data[] = ['header_type'=>'OVERALL PREFERENCE','is_active'=>1,'collaborate_id'=>$id,'header_info'=>'In information technology, header refers to supplemental data placed at the beginning of a block of data being stored or transmitted. In data transmission, the data following the header are sometimes called the payload or body.'];


        $this->model = Collaborate\ReviewHeader::insert($data);
        $collaborateId = $id;
        foreach ($questions as $key=>$question)
        {
            $data = [];
            $header = \DB::table('collaborate_tasting_header')->select('id')->where('header_type','=',$key)
                ->where('collaborate_id',$collaborateId)->first();
            $headerId = $header->id;
            foreach ($question as $item)
            {
                $subtitle = isset($item['subtitle']) ? $item['subtitle'] : null;
                $subquestions = isset($item['question']) ? $item['question'] : [];
                $isNested = isset($item['is_nested']) && $item['is_nested'] == 1 ? 1 : 0;
                $isMandatory = isset($item['is_mandatory']) && $item['is_mandatory'] == 1 ? 1 : 0;
                $option = isset($item['option']) ? $item['option'] : null;
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
                if(count($option))
                    $item['option'] = $option;
                unset($item['question']);
                $data = ['title'=>$item['title'],'subtitle'=>$subtitle,'is_nested'=>$isNested,'questions'=>json_encode($item,true),'parent_question_id'=>null,
                        'header_type_id'=>$headerId,'is_mandatory'=>$isMandatory,'is_active','collaborate_id'=>$collaborateId];

                $x = Collaborate\Questions::create($data);

                $nestedOption = json_decode($x->questions);
                $extraQuestion = [];
                if(isset($nestedOption->nested_option))
                {
                    if($nestedOption->nested_option)
                    {
                        $extra = \Db::table('global_nested_option')->where('type','=','Aroma')->get();
                        foreach ($extra as $nested)
                        {
                            $parentId = $nested->parent_id == 0 ? null : $nested->parent_id;
                            $extraQuestion[] = ["sequence_id"=>$nested->s_no,'parent_id'=>$parentId,'value'=>$nested->value,'question_id'=>$x->id,'is_active'=>1,
                                'collaborate_id'=>$collaborateId,'header_type_id'=>$headerId];
                        }
                        $this->model = \DB::table('collaborate_tasting_nested_options')->insert($extraQuestion);


                        $paths = \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)->whereNull('parent_id')->get();

                        foreach ($paths as $path)
                        {
                            \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)
                                ->where('id',$path->id)->update(['path'=>$path->value]);
                        }
                        $questions = \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)->get();

                        foreach ($questions as $question)
                        {
                            $checknested = \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)
                                ->where('parent_id',$question->sequence_id)->exists();

                            if($checknested)
                            {
                                \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)
                                    ->where('id',$question->id)->update(['nested_option'=>1]);
                            }
                            $getPath = \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)
                                ->where('parent_id',$question->sequence_id)->get()->pluck('id');
                            $pathname =  \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)
                                ->where('sequence_id',$question->sequence_id)->first();
                            \DB::table('collaborate_tasting_nested_options')->where('question_id',$x->id)->where('collaborate_id',$collaborateId)
                                ->whereIn('id',$getPath)->update(['path'=>$pathname->path]);
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
                    $isNested = isset($subquestion['is_nested']) && $subquestion['is_nested'] == 1 ? 1 : 0;
                    $isMandatory = isset($subquestion['is_mandatory']) && $subquestion['is_mandatory'] == 1 ? 1 : 0;
                    // for sub questions
                    $option = isset($subquestion['option']) ? $subquestion['option'] : null;
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
                    if(count($option))
                        $subquestion['option'] = $option;
                    unset($subquestion['question']);
                    $subData = ['title'=>$subquestion['title'],'subtitle'=>$subtitle,'is_nested'=>$isNested,'questions'=>json_encode($subquestion,true),'parent_question_id'=>$x->id,
                        'header_type_id'=>$headerId,'is_mandatory'=>$isMandatory,'is_active','collaborate_id'=>$collaborateId];
                    Collaborate\Questions::create($subData);

                }
            }
        }
    }
}

