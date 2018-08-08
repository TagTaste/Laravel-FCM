<?php

namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate\Questions;
use App\Collaborate\Review;
use App\Collaborate\ReviewHeader;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class QuestionController extends Controller
{

    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Questions $model)
    {
        $this->model = $model;
    }



    public function headers(Request $request, $id)
    {
        $this->model = \DB::table('collaborate_tasting_header')->where('collaborate_id',$id)->orderBy('id')->get();

        return $this->sendResponse();
    }

    public function reviewQuestions(Request $request, $collaborateId, $id)
    {
        $loggedInProfileId = $request->user()->profile->id;
        if(!$request->has('batch_id'))
        {
            return $this->sendError("No sample id found");
        }
        $headerId = $id;
        $batchId = $request->input('batch_id');
        $checkAssign = \DB::table('collaborate_batches_assign')->where('batch_id',$batchId)
            ->where('profile_id',$loggedInProfileId)->where('begin_tasting',1)->exists();

        if(!$checkAssign)
        {
            return $this->sendError("Wrong sample assigned");
        }
        $withoutNest = \DB::table('collaborate_tasting_questions')->where('collaborate_id',$collaborateId)
            ->whereNull('parent_question_id')->where('header_type_id',$id)->orderBy('id')->get();
        $withNested = \DB::table('collaborate_tasting_questions')->where('collaborate_id',$collaborateId)
            ->whereNotNull('parent_question_id')->where('header_type_id',$id)->orderBy('id')->get();

        foreach ($withoutNest as &$data)
        {
            if(isset($data->questions)&&!is_null($data->questions))
            {
                $data->questions = json_decode($data->questions);
            }
        }
        foreach ($withoutNest as &$data)
        {
            $i = 0;
            foreach ($withNested as $item)
            {
                if($item->parent_question_id == $data->id)
                {
                    $item->questions = json_decode($item->questions);
                    $item->questions->id = $item->id;
                    $item->questions->is_nested = $item->is_nested;
                    $item->questions->is_mandatory = $item->is_mandatory;
                    $item->questions->is_active = $item->is_active;
                    $item->questions->parent_question_id = $item->parent_question_id;
                    $item->questions->header_type_id = $item->header_type_id;
                    $item->questions->collaborate_id = $item->collaborate_id;
                    $data->questions->questions{$i} = $item->questions;
                    $i++;
                }
            }
        }

        $model = [];
        foreach ($withoutNest as $data)
        {
            if(isset($data->questions)&&!is_null($data->questions))
            {
                $data->questions->id = $data->id;
                $data->questions->is_nested = $data->is_nested;
                $data->questions->is_mandatory = $data->is_mandatory;
                $data->questions->is_active = $data->is_active;
                $data->questions->parent_question_id = $data->parent_question_id;
                $data->questions->header_type_id = $data->header_type_id;
                $data->questions->collaborate_id = $data->collaborate_id;
                if(isset($data->questions->nested_option))
                    $data->questions->option = \DB::table('collaborate_tasting_nested_options')->where('header_type_id',$headerId)
                        ->where('question_id',$data->id)->whereNull('parent_id')->get();
                $model[] = $data->questions;
            }
            else
            {
                $model[] = $data;
            }
        }



        $this->model = [];
        $this->model['question'] = $model;
        $this->model['answer'] = $this->userAnswer($loggedInProfileId,$collaborateId,$batchId,$id);
        return $this->sendResponse();
    }

    public function getNestedQuestions(Request $request, $collaborateId, $headerId, $questionId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $value = $request->input('value');
        if(!$request->has('batch_id'))
        {
            return $this->sendError("No sample id found");
        }
        $batchId = $request->input('batch_id');
        $checkAssign = \DB::table('collaborate_batches_assign')->where('batch_id',$batchId)->where('collaborate_id',$collaborateId)
            ->where('profile_id',$loggedInProfileId)->where('begin_tasting',1)->exists();

        if(!$checkAssign)
        {
            return $this->sendError("Wrong sample assigned");
        }
        $id = $request->has('id') ? $request->input('id') : null;
        $this->model = [];
        $answers = [];
        if(is_null($id))
        {
            $this->model['question'] = \DB::select("SELECT B.* FROM collaborate_tasting_nested_options as A , 
                                      collaborate_tasting_nested_options as B where A.sequence_id = B.parent_id AND A.value LIKE '$value' 
                                      AND A.parent_id IS NULL AND A.collaborate_id = $collaborateId AND B.question_id = $questionId");

        }
        else
        {
            $squence = \DB::table('collaborate_tasting_nested_options')->where('question_id',$questionId)
                ->where('collaborate_id',$collaborateId)->where('id',$id)->first();
            $this->model['question'] = \DB::table('collaborate_tasting_nested_options')->where('question_id',$questionId)
                ->where('collaborate_id',$collaborateId)->where('parent_id',$squence->sequence_id)->get();
            $leafIds = $this->model['question']->pluck('id');
            $answerModels = Review::where('profile_id',$loggedInProfileId)->where('collaborate_id',$collaborateId)
                ->where('batch_id',$batchId)->where('tasting_header_id',$headerId)->whereIn('leaf_id',$leafIds)
                ->where('question_id',$questionId)->get()->groupBy('question_id');
            foreach ($answerModels as $answerModel)
            {
                $data = [];
                $comment = null;
                foreach ($answerModel as $item)
                {
                    if($item->key == 'comment')
                    {
                        $comment = $item->value;
                        continue;
                    }
                    $questionId = $item->question_id;
                    $data[] = ['value'=>$item->value,'intensity'=>$item->intensity,'id'=>$item->leaf_id];
                }
                $answers[] = ['question_id'=>$questionId,'option'=>$data,'comment'=>$comment];
            }
        }

        $this->model['answer'] = $answers;
        return $this->sendResponse();

    }

    public function getNestedOptionSearch(Request $request, $collaborateId, $headerId, $questionId)
    {
        $this->model = [];
        $term = $request->input('term');
        if(!$request->has('batch_id'))
        {
            return $this->sendError("No sample id found");
        }
        $this->model['option'] = \DB::table('collaborate_tasting_nested_options')->where('question_id',$questionId)
            ->where('collaborate_id',$collaborateId)->where('value','like',"%$term%")->get();
        return $this->sendResponse();
    }

    public function userAnswer($loggedInProfileId,$collaborateId,$batchId,$id)
    {
        $answerModels = Review::where('profile_id',$loggedInProfileId)->where('collaborate_id',$collaborateId)
            ->where('batch_id',$batchId)->where('tasting_header_id',$id)->get()->groupBy('question_id');
        $answers = [];
        foreach ($answerModels as $answerModel)
        {
            $data = [];
            $comment = null;
            $questionId = null;
            foreach ($answerModel as $item)
            {
                $questionId = $item->question_id;
                if($item->key == 'comment')
                {
                    $comment = $item->value;
                    continue;
                }
                $data[] = ['value'=>$item->value,'intensity'=>$item->intensity,'id'=>$item->leaf_id];
            }
            $answers[] = ['question_id'=>$questionId,'option'=>$data,'comment'=>$comment];
        }

        return $answers;
    }

    public function insertHeaders(Request $request, $id)
    {
        $inputs = $request->input('header_type');
        $data = [];
        foreach ($inputs as $input)
        {
            $data[] = ['header_type'=>$input,'is_active'=>1,'collaborate_id'=>$id];
        }
        $this->model = ReviewHeader::insert($data);

        return $this->sendResponse();
    }

    public function aromQuestions(Request $request, $collaborateId, $headerId, $questionId)
    {
        $filename = str_random(32) . ".xlsx";
        $path = "images/collaborate/$collaborateId/questions";
        $file = $request->file('file')->storeAs($path,$filename,['visibility'=>'public']);
        //$fullpath = env("STORAGE_PATH",storage_path('app/')) . $path . "/" . $filename;
        //$fullpath = \Storage::url($file);

        //load the file
        $data = [];
        try {
            $fullpath = $request->file->store('temp', 'local');
            \Excel::load("storage/app/" . $fullpath, function($reader) use (&$data){
                $data = $reader->toArray();
            })->get();
            if(empty($data)){
                return $this->sendError("Empty file uploaded.");
            }
            \Storage::disk('local')->delete($file);
        } catch (\Exception $e){
            \Log::info($e->getMessage());
            return $this->sendError($e->getMessage());

        }
        $questions = [];
        $extra = [];
        foreach ($data as $item)
        {

            foreach ($item as $datum)
            {
                if(is_null($datum['parent_id'])||is_null($datum['categories']))
                    break;
                $extra[] = $datum;
                $parentId = $datum['parent_id'] == 0 ? null : $datum['parent_id'];
                $questions[] = ["sequence_id"=>$datum['no'],'parent_id'=>$parentId,'value'=>$datum['categories'],'question_id'=>$questionId,'is_active'=>1,
                    'collaborate_id'=>$collaborateId,'header_type_id'=>$headerId];
            }
        }
        $this->model = \DB::table('collaborate_tasting_nested_options')->insert($questions);

        $questions = \DB::table('collaborate_tasting_nested_options')->where('question_id',$questionId)->where('collaborate_id',$collaborateId)->get();

        foreach ($questions as $question)
        {
            $checknested = \DB::table('collaborate_tasting_nested_options')->where('question_id',$questionId)->where('collaborate_id',$collaborateId)
                ->where('parent_id',$question->sequence_id)->exists();
            if($checknested)
            {
                \DB::table('collaborate_tasting_nested_options')->where('question_id',$questionId)->where('collaborate_id',$collaborateId)
                    ->where('id',$question->id)->update(['nested_option'=>1]);
            }

        }

        return $this->sendResponse();
    }

    public function insertQuestions(Request $request, $collaborateId, $headerId)
    {
        $title = $request->input('title');
        $subTitle = $request->has('subtitle') ? !is_null($request->input('subtitle')) ? $request->input('subtitle') : null : null;
        $isNested = $request->input('is_nested');
        $parentQueId = $request->has('parent_question_id') ? !is_null($request->input('parent_question_id'))
            ? $request->input('parent_question_id') : null : null ;

        $questions = $request->input('questions');

        $this->model = \DB::table('collaborate_tasting_questions')->insert(['title'=>$title,'subtitle'=>$subTitle,'is_nested'=>$isNested,
            'parent_question_id'=>$parentQueId,'is_active'=>1,'is_mandatory'=>1,'questions'=>$questions,'collaborate_id'=>$collaborateId,'header_type_id'=>$headerId]);
        return $this->sendResponse();
    }

}
