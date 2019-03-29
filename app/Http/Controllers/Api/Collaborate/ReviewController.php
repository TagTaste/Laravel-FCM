<?php

namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate\Review;
use App\Collaborate\ReviewHeader;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class ReviewController extends Controller
{

    protected $model;
    protected $now;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Review $model)
    {
        $this->model = $model;
        $this->now = Carbon::now()->toDateTimeString();
    }

    public function reviewAnswers(Request $request, $collaborateId, $headerId)
    {
        $this->now = Carbon::now()->toDateTimeString();
        $data = [];
        $answers = $request->input('answer');
        $loggedInProfileId = $request->user()->profile->id ;
        $batchId = $request->input('batch_id');
        if(!$request->has('batch_id'))
        {
            return $this->sendError("No prodcut id found");
        }
        $checkAssign = \DB::table('collaborate_batches_assign')->where('batch_id',$batchId)->where('profile_id',$loggedInProfileId)->exists();

        if(!$checkAssign)
        {
            return $this->sendError("Wrong product assigned");
        }
        $currentStatus = $request->has('current_status') ? $request->input('current_status') : 2;
        $latestCurrentStatus = \Redis::get("current_status:batch:$batchId:profile:$loggedInProfileId");
        if($currentStatus == $latestCurrentStatus && $latestCurrentStatus == 3)
        {
            return $this->sendError("You have already completed this product");
        }
        $this->model = Review::where('profile_id',$loggedInProfileId)->where('collaborate_id',$collaborateId)
            ->where('batch_id',$batchId)->where('tasting_header_id',$headerId)->delete();

        if(count($answers))
        {
            foreach ($answers as $answer)
            {
                $options = isset($answer['option']) ? $answer['option'] : [];
                $questionId = $answer['question_id'];
                foreach ($options as $option)
                {
                    $leafId = isset($option['id']) && $option['id'] != 0 ? $option['id'] : null;
                    $valueId = isset($option['value_id']) && $option['value_id'] != 0 ? $option['id'] : null;
                    $intensity = isset($option['intensity']) && !is_null($option['intensity']) && !empty($option['intensity']) ? $option['intensity'] : null;
                    $data[] = ['key'=>null,'value'=>$option['value'],'leaf_id'=>$leafId,
                        'question_id'=>$questionId,'tasting_header_id'=>$headerId,
                        'profile_id'=>$loggedInProfileId,'batch_id'=>$batchId,
                        'collaborate_id'=>$collaborateId,'intensity'=>$intensity,'current_status'=>$currentStatus,'value_id'=>$valueId,
                        'created_at'=>$this->now,'updated_at'=>$this->now];
                }
                if(isset($answer['comment']) && !is_null($answer['comment']) && !empty($answer['comment']))
                {
                    $data[] = ['key'=>"comment",'value'=>$answer['comment'],'leaf_id'=>0,
                        'question_id'=>$questionId,'tasting_header_id'=>$headerId,
                        'profile_id'=>$loggedInProfileId,'batch_id'=>$batchId,
                        'collaborate_id'=>$collaborateId,'intensity'=>null,'current_status'=>$currentStatus,'value_id'=>null,
                        'created_at'=>$this->now,'updated_at'=>$this->now];
                }
            }
        }
        if(count($data)>0)
        {
            $this->model = Review::insert($data);
            if($currentStatus == 3)
            {
                $mandatoryQuestion = \DB::table('collaborate_tasting_questions')->where('collaborate_id',$collaborateId)
                    ->where('is_mandatory',1)->get();
                $mandatoryQuestionsId = $mandatoryQuestion->pluck('id');
                $mandatoryReviewCount = \DB::table('collaborate_tasting_user_review')->where('collaborate_id',$collaborateId)->whereIn('question_id',$mandatoryQuestionsId)->where('batch_id',$batchId)->where('profile_id',$loggedInProfileId)->get()->count();

                if($mandatoryQuestion->count() == $mandatoryReviewCount)
                {
                    $this->model = true;
                    \DB::table('collaborate_tasting_user_review')->where('collaborate_id',$collaborateId)
                        ->where('batch_id',$batchId)->where('profile_id',$loggedInProfileId)->update(['current_status'=>3]);
                }
                else
                {
                    $currentStatus = 2;
                    $this->model = false;
                }

            }
            \Redis::set("current_status:batch:$batchId:profile:$loggedInProfileId" ,$currentStatus);
        }
        return $this->sendResponse();
    }
}
