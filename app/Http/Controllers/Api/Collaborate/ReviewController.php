<?php

namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate\Review;
use App\Collaborate\ReviewHeader;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class ReviewController extends Controller
{

    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Review $model)
    {
        $this->model = $model;
    }

    public function reviewAnswers(Request $request, $collaborateId, $headerId)
    {
        $data = [];
        $answers = $request->input('answer');
        $loggedInProfileId = $request->user()->profile->id ;
        $batchId = $request->input('batch_id');
        $answerExists = Review::where('profile_id',$loggedInProfileId)->where('collaborate_id',$collaborateId)
                        ->where('batch_id',$batchId)->where('tasting_header_id',$headerId)->exists();

        if($answerExists)
        {
            Review::where('profile_id',$loggedInProfileId)->where('collaborate_id',$collaborateId)
                ->where('batch_id',$batchId)->where('tasting_header_id',$headerId)->delete();
        }
        foreach ($answers as $answer)
        {
            $options = isset($answer['option']) ? $answer['option'] : [];
            $questionId = $answer['question_id'];
            foreach ($options as $option)
            {
                $leafId = isset($option) && $option['id'] != 0 ? $option['id'] : null;
                $data[] = ['key'=>null,'value'=>$option['value'],'id'=>$leafId,
                    'question_id'=>$questionId,'tasting_header_id'=>$headerId,
                    'profile_id'=>$loggedInProfileId,'batch_id'=>$batchId,
                    'collaborate_id'=>$collaborateId,'intensity'=>$option['intensity']];
            }
            if(isset($answer['comment']) && !is_null($answer['comment']))
            {
                $data[] = ['key'=>"comment",'value'=>$answer['comment'],'id'=>0,
                    'question_id'=>$questionId,'tasting_header_id'=>$headerId,
                    'profile_id'=>$loggedInProfileId,'batch_id'=>$batchId,
                    'collaborate_id'=>$collaborateId,'intensity'=>null];
            }
        }

        $this->model = Review::insert($data);
        return $this->sendResponse();
    }
}
