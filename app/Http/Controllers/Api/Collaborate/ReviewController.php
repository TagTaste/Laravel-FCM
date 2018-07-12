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

    public function reviewanswers(Request $request, $collaborateId, $headerId , $questionsId)
    {
        $profileId = $request->user()->profile->id;
        $key = $request->input('key');
        $value = $request->input('value');
        $existReview = \DB::table('collaborate_tasting_user_review')->where('collaborate_id',$collaborateId)->where('tasting_header_id',$headerId)
            ->where('question_id',$questionsId)->where('key',$key)->exists();

        if($existReview)
        {
            $this->model = \DB::table('collaborate_tasting_user_review')->where('collaborate_id',$collaborateId)->where('tasting_header_id',$headerId)
                ->where('question_id',$questionsId)->where('key',$key)->update(['value'=>$value]);
            return $this->sendResponse();
        }
        $this->model = \DB::table('collaborate_tasting_user_review')->insert(['key'=>$key,'value'=>$value,'question_id'=>$questionsId,
            'collaborate_id'=>$collaborateId,'tasting_header_id'=>$headerId]);
        return $this->sendResponse();
    }
}
