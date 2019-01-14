<?php

namespace App\Http\Controllers\Api\PublicReviewProduct;

use App\Collaborate;
use App\PublicReviewProduct;
use App\PublicReviewProduct\Questions;
use App\PublicReviewProduct\Review;
use App\PublicReviewProduct\ReviewHeader;
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
        $product = PublicReviewProduct::where('id',$id)->first();
        if($product == null)
        {
            return $this->sendError("No product exists");
        }
        if($product->is_authenticity_check)
            $this->model = ReviewHeader::where('is_active',1)->where('global_question_id',$product->global_question_id)
            ->orderBy('id')->get();
        else
            $this->model = ReviewHeader::where('header_selection_type','!=',3)->where('is_active',1)->where('global_question_id',$product->global_question_id)
                ->orderBy('id')->get();

        return $this->sendResponse();
    }

    public function reviewQuestions(Request $request, $productId, $headerId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $product = PublicReviewProduct::where('id',$productId)->first();
        if($product === null){
            return $this->sendError("PublicReviewProduct not found.");
        }
        $withoutNest = \DB::table('public_review_questions')->where('global_question_id',$product->global_question_id)
            ->whereNull('parent_question_id')->where('header_id',$headerId)->where('is_active',1)->orderBy('id')->get();
        $withNested = \DB::table('public_review_questions')->where('global_question_id',$product->global_question_id)
            ->whereNotNull('parent_question_id')->where('is_active',1)->where('header_id',$headerId)->orderBy('id')->get();

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
                    $item->questions->header_id = $item->header_id;
                    $item->questions->global_question_id = $item->global_question_id;
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
                $data->questions->header_id = $data->header_id;
                $data->questions->global_question_id = $data->global_question_id;
                if(isset($data->questions->is_nested_option) && $data->questions->is_nested_option == 1)
                {
                    $data->questions->option = \DB::table('public_review_nested_options')->where('header_id',$headerId)
                        ->where('question_id',$data->id)->where('is_active',1)->whereNull('parent_id')->get();
                }
                if($data->questions->title == 'INSTRUCTION' || $data->questions->title == 'INSTRUCTIONS' || $data->questions->title == 'Instruction' || $data->questions->title == 'Instructions')
                {
                    $data->questions->subtitle = "Please follow the questionnaire and select the answers that are closest to what you sensed during product tasting. Remember, there are no right or wrong answers.";
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
        $this->model['answer'] = $this->userAnswer($loggedInProfileId,$productId,$headerId);
        return $this->sendResponse();
    }

    public function getNestedQuestions(Request $request, $productId, $headerId, $questionId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $value = $request->input('value');
        $id = $request->has('id') ? $request->input('id') : null;
        $this->model = [];
        $product = PublicReviewProduct::where('id',$productId)->first();
        $answers = [];
        if($product === null){
            return $this->sendError("PublicReviewProduct not found.");
        }

        if(is_null($id))
        {
            $this->model['question'] = \DB::select("SELECT B.* FROM public_review_nested_options as A , 
                                      public_review_nested_options as B where A.sequence_id = B.parent_id AND A.value LIKE '$value' 
                                      AND A.parent_id IS NULL AND A.global_question_id = $product->global_question_id AND B.question_id = $questionId");

        }
        else
        {
            $squence = \DB::table('public_review_nested_options')->where('is_active',1)->where('question_id',$questionId)
                ->where('global_question_id',$product->global_question_id)->where('id',$id)->first();
            $this->model['question'] = \DB::table('public_review_nested_options')->where('is_active',1)->where('question_id',$questionId)
                ->where('global_question_id',$product->global_question_id)->where('parent_id',$squence->sequence_id)->get();
            $leafIds = $this->model['question']->pluck('id');
            $answerModels = Review::where('profile_id',$loggedInProfileId)->where('product_id',$product->id)
                ->where('header_id',$headerId)->whereIn('leaf_id',$leafIds)
                ->where('question_id',$questionId)->get()->groupBy('question_id');
            foreach ($answerModels as $answerModel)
            {
                $data = [];
                $comment = null;
                $selectType = null;
                foreach ($answerModel as $item)
                {
                    if($item->key == 'comment')
                    {
                        $comment = $item->value;
                        continue;
                    }
                    $selectType = $item->select_type;
                    $questionId = $item->question_id;
                    $data[] = ['value'=>$item->value,'intensity'=>$item->intensity,'id'=>$item->leaf_id];
                }
                $answers[] = ['question_id'=>$questionId,'option'=>$data,'comment'=>$comment,'select_type'=>$selectType];
            }
        }

        $this->model['answer'] = $answers;
        return $this->sendResponse();

    }

    public function getNestedOptionSearch(Request $request, $productId, $headerId, $questionId)
    {
        $this->model = [];
        $term = $request->input('term');
        $product = PublicReviewProduct::where('id',$productId)->first();
        if($product === null){
            return $this->sendError("PublicReviewProduct not found.");
        }
        $this->model['option'] = \DB::table('public_review_nested_options')->where('question_id',$questionId)
            ->where('global_question_id',$product->global_question_id)->where('is_active',1)->where('value','like',"%$term%")->get();
        return $this->sendResponse();
    }

    public function userAnswer($loggedInProfileId,$productId,$headerId)
    {
        $answerModels = Review::where('profile_id',$loggedInProfileId)->where('product_id',$productId)
            ->where('header_id',$headerId)->get()->groupBy('question_id');
        $answers = [];
        foreach ($answerModels as $answerModel)
        {
            $data = [];
            $comment = null;
            $questionId = null;
            foreach ($answerModel as $item)
            {
                $questionId = $item->question_id;
                $selectType = $item->select_type;
                if($item->key == 'comment')
                {
                    $comment = $item->value;
                    continue;
                }
                $data[] = ['value'=>$item->value,'intensity'=>$item->intensity,'id'=>$item->leaf_id];
            }
            if(!is_null($comment) && !empty($comment))
            {
                $answers[] = ['question_id'=>$questionId,'option'=>$data,'comment'=>$comment,'select_type'=>$selectType];
            }
            else
            {
                $answers[] = ['question_id'=>$questionId,'option'=>$data,'select_type'=>$selectType];

            }
        }

        return $answers;
    }

}
