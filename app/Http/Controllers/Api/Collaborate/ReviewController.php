<?php

namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate\Review;
use App\Collaborate\ReviewHeader;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Redis;

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
        if(!$request->has('address_id') && 
        \App\Collaborate::where('id',$collaborateId)->first()->track_consistency){
        return $this->sendError('Please send the respective outlet (address id) as query parameter');
        } else if($request->has('address_id') && 
                !\App\Collaborate\Addresses::where('collaborate_id',$collaborateId)->where('address_id',$request->address_id)->exists()) {
                    return $this->sendError('Invalid Address id');
        } else {
            $address_id = $request->address_id!= null ? $request->address_id : null;
        }
        
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
        $latestCurrentStatus = Redis::get("current_status:batch:$batchId:profile:$loggedInProfileId");
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
                if(isset($answer["option"])) {
                    $optionVal = \DB::table('collaborate_tasting_questions')->where('id',$questionId)->get();
                    if(!isset(json_decode($optionVal[0]->questions)->nested_option_list))
                        $optionVal = json_decode($optionVal[0]->questions)->option;
                    else
                        $optionVal = "AROMA";

                }
                foreach ($options as $option)
                {
                    $leafId = isset($option['id']) && $option['id'] != 0 ? $option['id'] : null;
                    if($optionVal=='AROMA') {
                        $optionType = \DB::table('collaborate_tasting_nested_options')->where('id',$leafId)->first()->option_type;
                    } else {
                        $optionType = isset($optionVal[$leafId-1]->option_type) ? $optionVal[$leafId-1]->option_type : 0;
                    }
                    //$optionType = isset($option['option_type'])? $option['option_type'] : 0;
                    $valueId = isset($option['value_id']) && $option['value_id'] != 0 ? $option['id'] : null;
                    $intensity = isset($option['intensity']) && !is_null($option['intensity']) && !empty($option['intensity']) ? $option['intensity'] : null;
                    $data[] = ['key'=>null,'value'=>$option['value'],'leaf_id'=>$leafId,
                        'question_id'=>$questionId,'tasting_header_id'=>$headerId,
                        'profile_id'=>$loggedInProfileId,'batch_id'=>$batchId,
                        'collaborate_id'=>$collaborateId,'intensity'=>$intensity,'current_status'=>$currentStatus,'value_id'=>$valueId,
                        'created_at'=>$this->now,'updated_at'=>$this->now, 'option_type'=>$optionType,'address_map_id'=>$address_id];
                }
                if(isset($answer['meta']) && !is_null($answer['meta']) && !empty($answer['meta']))
                {
                    $data[] = ['key'=>"authenticity_check",'value'=>"meta",'leaf_id'=>0,
                        'question_id'=>$questionId,'tasting_header_id'=>$headerId,
                        'profile_id'=>$loggedInProfileId, 'batch_id'=>$batchId,'collaborate_id'=>$collaborateId,'intensity'=>null,
                        'current_status'=>$currentStatus,'value_id'=>null,
                        'created_at'=>$this->now,'updated_at'=>$this->now,'meta'=>$answer['meta'],'option_type'=>0,'address_map_id'=>$address_id];
                }
                if(isset($answer['comment']) && !is_null($answer['comment']) && !empty($answer['comment']))
                {
                    $data[] = ['key'=>"comment",'value'=>$answer['comment'],'leaf_id'=>0,
                        'question_id'=>$questionId,'tasting_header_id'=>$headerId,
                        'profile_id'=>$loggedInProfileId,'batch_id'=>$batchId,
                        'collaborate_id'=>$collaborateId,'intensity'=>null,'current_status'=>$currentStatus,'value_id'=>null,
                        'created_at'=>$this->now,'updated_at'=>$this->now,'option_type'=>0,'address_map_id'=>$address_id];
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
                $mandatoryReviewCount = \DB::table('collaborate_tasting_user_review')->where('collaborate_id',$collaborateId)->whereIn('question_id',$mandatoryQuestionsId)->where('batch_id',$batchId)->where('profile_id',$loggedInProfileId)->distinct('question_id')->count('question_id');
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
