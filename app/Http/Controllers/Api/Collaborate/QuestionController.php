<?php

namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate;
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
        $this->model = ReviewHeader::where('is_active',1)->where('collaborate_id',$id)->orderBy('id')->get();

        return $this->sendResponse();
    }

    public function reportHeader(Request $request,$collaborateId,$batchId)
    {
        $this->model = $this->getHeaderRating($collaborateId,$batchId);
        return $this->sendResponse();
    }

    private function getHeaderRating($collaborateId,$batchId)
    {
        $headers = ReviewHeader::where('collaborate_id',$collaborateId)->where('header_selection_type','!=',3)->skip(1)->take(10)->get();
//        $overallPreferances = \DB::table('collaborate_tasting_user_review')->where('collaborate_id',$collaborateId)->where('batch_id',$batchId)->where('current_status',3)->get();

        $headerRating = [];
        foreach ($headers as $header)
        {
            $userCount = 0;
            $headerRatingSum = 0;
            $question = Questions::where('header_type_id',$header->id)->where('questions->select_type',5)->first();
            $overallPreferances = \DB::table('collaborate_tasting_user_review')->where('collaborate_id',$collaborateId)->where('batch_id',$batchId)->where('current_status',3)->where('question_id',$question->id)->get();
            foreach ($overallPreferances as $overallPreferance)
            {
                if($overallPreferance->tasting_header_id == $header->id)
                {
                    $headerRatingSum += $overallPreferance->leaf_id;
                    $userCount++;
                }
            }
            $headerRating[] = ['header_info'=>$header,'meta'=>$this->getRatingMeta($userCount,$headerRatingSum,$question)];
        }

        return $headerRating;
    }

    protected function getRatingMeta($userCount,$headerRatingSum,$question)
    {
        $meta = [];
        $question = json_decode($question->questions);
        $option = isset($question->option) ? $question->option : [];
        $meta['max_rating'] = count($option);
        $meta['overall_rating'] = $userCount > 0 ? $headerRatingSum/$userCount : 0.00;
        $meta['count'] = $userCount;
        $meta['color_code'] = $this->getColorCode(floor($meta['overall_rating']));
        return $meta;
    }

    protected function getColorCode($value)
    {
        if($value == 0 || is_null($value))
            return null;
        switch ($value) {
            case 1:
                return '#8C0008';
                break;
            case 2:
                return '#D0021B';
                break;
            case 3:
                return '#C92E41';
                break;
            case 4:
                return '#E27616';
                break;
            case 5:
                return '#AC9000';
                break;
            case 6:
                return '#7E9B42';
                break;
            case 7:
                return '#577B33';
                break;
            default:
                return '#305D03';
        }
    }

    public function reviewQuestions(Request $request, $collaborateId, $id)
    {
        $collaborate = Collaborate::where('state',Collaborate::$state[0])->where('id',$collaborateId)->first();
        if($collaborate === null){
            return $this->sendError("Collaboration not found.");
        }
        $loggedInProfileId = $request->user()->profile->id;
        if(!$request->has('batch_id'))
        {
            return $this->sendError("No product id found");
        }
        $headerId = $id;
        $batchId = $request->input('batch_id');
        $checkAssign = \DB::table('collaborate_batches_assign')->where('batch_id',$batchId)
            ->where('profile_id',$loggedInProfileId)->where('begin_tasting',1)->exists();

        if(!$checkAssign)
        {
            return $this->sendError("Wrong product assigned");
        }
        $withoutNest = \DB::table('collaborate_tasting_questions')->where('collaborate_id',$collaborateId)
            ->whereNull('parent_question_id')->where('header_type_id',$id)->where('is_active',1)->orderBy('id')->get();
        $withNested = \DB::table('collaborate_tasting_questions')->where('collaborate_id',$collaborateId)
            ->whereNotNull('parent_question_id')->where('is_active',1)->where('header_type_id',$id)->orderBy('id')->get();
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
                    $item->questions->is_nested_question = $item->is_nested_question;
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
                $data->questions->is_nested_question = $data->is_nested_question;
                $data->questions->is_mandatory = $data->is_mandatory;
                $data->questions->is_active = $data->is_active;
                $data->questions->parent_question_id = $data->parent_question_id;
                $data->questions->header_type_id = $data->header_type_id;
                $data->questions->collaborate_id = $data->collaborate_id;
                if(isset($data->questions->is_nested_option) && $data->questions->is_nested_option == 1)
                {
                    $data->questions->option = \DB::table('collaborate_tasting_nested_options')->where('header_type_id',$headerId)
                        ->where('question_id',$data->id)->where('is_active',1)->whereNull('parent_id')->get();
                }
                if($data->questions->title == 'INSTRUCTION' || $data->questions->title == 'INSTRUCTIONS' || $data->questions->title == 'Instruction' || $data->questions->title == 'Instructions')
                {
                    if(!isset($data->questions->subtitle))
                        $data->questions->subtitle = "Please follow the questionnaire and select the answers that are closest to what you sensed during product tasting. Remember, there are no right or wrong answers.";

                    if(isset($collaborate->taster_instruction))
                        $data->questions->subtitle = $collaborate->taster_instruction;

                }
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
            return $this->sendError("No product id found");
        }
        $batchId = $request->input('batch_id');
        $checkAssign = \DB::table('collaborate_batches_assign')->where('batch_id',$batchId)->where('collaborate_id',$collaborateId)
            ->where('profile_id',$loggedInProfileId)->where('begin_tasting',1)->exists();

        if(!$checkAssign)
        {
            return $this->sendError("Wrong product assigned");
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
            $squence = \DB::table('collaborate_tasting_nested_options')->where('is_active',1)->where('question_id',$questionId)
                ->where('collaborate_id',$collaborateId)->where('id',$id)->first();
            $this->model['question'] = \DB::table('collaborate_tasting_nested_options')->where('is_active',1)->where('question_id',$questionId)
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
            return $this->sendError("No product id found");
        }
        $this->model['option'] = \DB::table('collaborate_tasting_nested_options')->where('question_id',$questionId)
            ->where('collaborate_id',$collaborateId)->where('is_active',1)->where('value','like',"%$term%")->get();
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
            $meta = null;
            $track_consistency = 0;
            foreach ($answerModel as $item)
            {
                $questionId = $item->question_id;
                $question = \DB::table('collaborate_tasting_questions')
                                    ->where('id',$questionId)
                                    ->first();
                if ($item->key == 'comment') {
                    $comment = $item->value;
                    continue;
                }

                if ($item->key == 'authenticity_check') {
                    $question = \DB::table('collaborate_tasting_questions')
                                    ->where('id',$questionId)
                                    ->first();
                    $meta = $item->meta;
                    $track_consistency = $question->track_consistency;
                }
                
                if (isset(json_decode($question->questions)->is_nested_option) 
                        && json_decode($question->questions)->is_nested_option == 1) {
                    $aroma = \DB::table('collaborate_tasting_nested_options')
                        ->where('id',$item->leaf_id)
                        ->first();
                    $is_nested = \DB::table('collaborate_tasting_nested_options')
                    ->where('collaborate_id',$collaborateId)
                    ->where('parent_id',$aroma->sequence_id)
                    ->exists();
                    $is_intensity = 0;
                    if (!is_null($aroma)) {
                        $is_intensity = $aroma->is_intensity;
                    }
                    $data[] = ['value'=>$item->value,'is_intensity'=>$is_intensity,'intensity'=>$item->intensity,'id'=>$item->leaf_id,'option_type'=>$item->option_type,'parent_sequence_id'=>$aroma->parent_sequence_id, 'sequence_id'=>$aroma->sequence_id,'is_nested_option'=>$is_nested,'parent_id'=>$aroma->parent_id];
                } else {
                    $data[] = ['value'=>$item->value,'intensity'=>$item->intensity,'id'=>$item->leaf_id,'option_type'=>$item->option_type];
                }
            }
            if (!is_null($comment) && !empty($comment)) {
                $answers[] = ['question_id'=>$questionId,'option'=>$data,'comment'=>$comment];
            } else if (!is_null($meta) && !empty($meta)) {
                $answers[] = ['question_id'=>$questionId,'meta'=>json_decode($meta),'track_consistency'=>$track_consistency];
            } else {
                $answers[] = ['question_id'=>$questionId,'option'=>$data];
            }
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

        $questions = \DB::table('collaborate_tasting_nested_options')->where('is_active',1)->where('question_id',$questionId)->where('collaborate_id',$collaborateId)->get();

        foreach ($questions as $question)
        {
            $checknested = \DB::table('collaborate_tasting_nested_options')->where('is_active',1)->where('question_id',$questionId)->where('collaborate_id',$collaborateId)
                ->where('parent_id',$question->sequence_id)->exists();
            if($checknested)
            {
                \DB::table('collaborate_tasting_nested_options')->where('question_id',$questionId)->where('collaborate_id',$collaborateId)
                    ->where('id',$question->id)->update(['is_nested_option'=>1]);
            }

        }

        return $this->sendResponse();
    }

    public function insertQuestions(Request $request, $collaborateId, $headerId)
    {
        $title = $request->input('title');
        $subTitle = $request->has('subtitle') ? !is_null($request->input('subtitle')) ? $request->input('subtitle') : null : null;
        $isNested = $request->input('is_nested_question');
        $parentQueId = $request->has('parent_question_id') ? !is_null($request->input('parent_question_id'))
            ? $request->input('parent_question_id') : null : null ;

        $questions = $request->input('questions');

        $this->model = \DB::table('collaborate_tasting_questions')->insert(['title'=>$title,'subtitle'=>$subTitle,'is_nested_question'=>$isNested,
            'parent_question_id'=>$parentQueId,'is_active'=>1,'is_mandatory'=>1,'questions'=>$questions,'collaborate_id'=>$collaborateId,'header_type_id'=>$headerId]);
        return $this->sendResponse();
    }

}
