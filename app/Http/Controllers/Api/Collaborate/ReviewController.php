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
        foreach ($answers as $answer)
        {
            $leafId = isset($answer) && $answer['leaf_id'] != 0 ? $answer['leaf_id'] : null;
            $data[] = ['key'=>$answer['key'],'value'=>$answer['value'],'leaf_id'=>$leafId,
                        'question_id'=>$answer['question_id'],'tasting_header_id'=>$headerId,
                        'profile_id'=>$loggedInProfileId,'batch_id'=>$batchId,
                'collaborate_id'=>$collaborateId];
        }
        $this->model = Review::insert($data);
        return $this->sendResponse();
    }
}
