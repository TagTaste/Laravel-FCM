<?php

namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate;
use App\Collaborate\Batches;
use App\Collaborate\ReviewHeader;
use App\Collaborate\Questions;
use App\CompanyUser;
use App\Recipe\Company;
use App\Recipe\Profile;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\PaymentHelper;
use App\Profile\User;
use Illuminate\Support\Collection;
use Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Redis;
use App\Collaborate\Review;
use App\Collaborate\Applicant;
use App\Traits\FilterFactory;
use App\Collaborate\BatchAssign;
use App\Helper;
use App\CollaborateTastingEntryMapping;
use App\Payment\PaymentLinks;
use App\ModelFlagReason;

class BatchController extends Controller
{
    use FilterFactory;
    protected $model;


    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Collaborate\Batches $model)
    {
        $this->model = $model;

        $this->middleware('permissionCollaborate', ['only' => [
            'store',
            'update' // Could add bunch of more methods too
        ]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($collaborateId)
    {
        $batches = $this->model->where('collaborate_id', $collaborateId)
            ->orderBy("created_at", "desc")->get()->toArray();
        foreach ($batches as &$batch) {
            //$batch['beginTastingCount'] = \DB::table('collaborate_batches_assign')->where('begin_tasting',1)->where('batch_id',$batch['id'])->distinct()->get(['profile_id'])->count();
            // $batch['assignedCount'] = \DB::table('collaborate_batches_assign')->where('batch_id', $batch['id'])->distinct()->get(['profile_id'])->count();
            $profileIds = Applicant::where('collaborate_id', $collaborateId)->whereNotNull('shortlisted_at')->whereNull('rejected_at')->pluck('profile_id')->toArray();
            $batch['assignedCount'] = BatchAssign::where('batch_id', $batch['id'])->whereIn('profile_id', $profileIds)->distinct()->get(['profile_id'])->count();
            $batch['reviewedCount'] = \DB::table('collaborate_tasting_user_review')->where('current_status', 3)->where('collaborate_id', $batch['collaborate_id'])
                ->where('batch_id', $batch['id'])->distinct()->get(['profile_id'])->count();

            $batch['beginTastingCount'] = $batch['assignedCount'] - $batch['reviewedCount'];

            //below changes done by nikhil
            $batch['inProgressUserCount'] = \DB::table('collaborate_tasting_user_review')->where('current_status', 2)->where('collaborate_id', $batch['collaborate_id'])
                ->where('batch_id', $batch['id'])->distinct()->get(['profile_id'])->count();

            $userCountWithbegintasting = \DB::table('collaborate_batches_assign')->where('begin_tasting', 1)->where('batch_id', $batch['id'])->distinct()->get(['profile_id'])->count();
            $batch['notifiedUserCount'] = $userCountWithbegintasting - ($batch['reviewedCount'] + $batch['inProgressUserCount']);
        }

        $this->model = $batches;
        return $this->sendResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, $collaborateId)
    {
        // check inputs
        $inputs = $request->except(['_method', '_token']);

        // check if collaboration exists in our system
        $collaborate = Collaborate::where('id', $collaborateId)->where('state', Collaborate::$state[0])->first();
        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }

        if (is_null($collaborate->global_question_id)) {
            return $this->sendError("You can not update your products as questionaire is not attached.");
        }

        // check if batch of same name exist
        $check_if_batch_name_exist = $this->model
            ->where('collaborate_id', $collaborateId)
            ->where('name', $inputs['name'])
            ->exists();
        if ($check_if_batch_name_exist) {
            $this->model = null;
            return $this->sendError("Batch with the same name already exists.");
        }

        // current time
        $now = Carbon::now()->toDateTimeString();

        // begin transaction
        \DB::beginTransaction();
        try {
            // create a new batch
            $this->model = $this->model->create($inputs);
            $batch_id = $this->model->id;

            // compute all the batch assign inputs
            $batch_inputs = [];

            // fetch all the active applicants
            $applicants = Collaborate\Applicant::where('collaborate_id', $collaborateId)
                ->whereNotNull('shortlisted_at')
                ->whereNull('rejected_at')
                ->pluck('profile_id');

            foreach ($applicants as $applicant_id) {
                // update the redis for the applicant info
                Redis::sAdd("collaborate:$collaborateId:profile:$applicant_id:", $batch_id);
                Redis::set("current_status:batch:$batch_id:profile:$applicant_id", 0);

                // compute all the batch applicant assign input data
                if ($collaborate->track_consistency) {
                    $batch_inputs[] = [
                        'profile_id' => (int)$applicant_id,
                        'batch_id' => (int)$batch_id,
                        'begin_tasting' => 0,
                        'created_at' => $now,
                        'collaborate_id' => (int)$collaborateId,
                        'bill_verified' => 0
                    ];
                } else {
                    $batch_inputs[] = [
                        'profile_id' => (int)$applicant_id,
                        'batch_id' => (int)$batch_id,
                        'begin_tasting' => 0,
                        'created_at' => $now,
                        'collaborate_id' => (int)$collaborateId
                    ];
                }
            }
            // collaborate assign all the batches to the user
            \DB::table('collaborate_batches_assign')->insert($batch_inputs);
            \DB::commit();
        } catch (\Exception $e) {
            // roll in case of error
            \DB::rollback();
            \Log::info($e->getMessage());
            $this->model = null;
            return $this->sendError("Please try again after some time.");
        }

        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $collaborateId, $id)
    {
        //paginate
        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $this->model = [];

        //filters data
        $q = $request->input('q');
        $filters = $request->input('filters');
        $resp = $this->getFilterProfileIds($filters, $collaborateId, $id);
        $profileIds = $resp['profile_id'];
        
        $type = $resp['type'];
        $boolean = 'and';
        $profileIds = \DB::table('collaborate_batches_assign')->where('batch_id', $id)->whereIn('profile_id', $profileIds, $boolean, $type)->orderBy('created_at', 'desc')->get()->pluck('profile_id');

        $profiles = Collaborate\Applicant::where('collaborate_id', $collaborateId)->whereIn('profile_id', $profileIds)->whereNotNull('shortlisted_at')
            ->whereNull('rejected_at');

        //sort applicants
        if (isset($filters))
            $type = false;
            if (isset($q) && $q != null) {
                $searchedProfiles = $this->getSearchedProfile($q, $collaborateId);
                $profiles = $profiles->whereIn('id', $searchedProfiles);
            } 

        if ($request->sortBy != null) {
            $profiles = $this->sortApplicants($request->sortBy, $profiles, $collaborateId);
        } 
        else{
           $profiles=$profiles->orderBy('created_at', 'desc');
        }

        $pId = $profiles->get()->toArray();
        
        $queryCurrentStatus = $request->input('current_status');
        $queryCurrentStatus = isset($queryCurrentStatus) ? $request->input('current_status') : null;

        if($queryCurrentStatus != null){
            $profileIds = $profiles->get()->pluck('profile_id');
            if($queryCurrentStatus == 2 || $queryCurrentStatus == 3){
                $profileIds = \DB::table('collaborate_tasting_user_review')->where('current_status', $queryCurrentStatus)->where('collaborate_id', $collaborateId)->whereIn('profile_id', $profileIds)->where('batch_id', $id)->get()->pluck('profile_id')->unique();
            }else if ($queryCurrentStatus == 1 || $queryCurrentStatus == 0){
                $ids = \DB::table('collaborate_batches_assign')->where('collaborate_id', $collaborateId)->where('batch_id', $id)->where('begin_tasting', $queryCurrentStatus)->get()->pluck('profile_id')->unique();
                   
                $ids2 = \DB::table('collaborate_tasting_user_review')->where('collaborate_id', $collaborateId)->where('batch_id', $id)->get()->pluck('profile_id')->unique();                   
                
                $profileIds = $ids->diff($ids2);
            }

            $profiles = $profiles->whereIn('profile_id', $profileIds);
        }

        
        $profiles = $profiles
            ->skip($skip)->take($take)->get();

        $profileBatchData = BatchAssign::where('batch_id', $id)->where('collaborate_id', $collaborateId)->whereIn('profile_id', $profileIds);
        $profileModelIds = $profileBatchData->pluck('id','profile_id')->toArray();
        $profileFlagValues = $profileBatchData->pluck('is_flag','profile_id')->toArray();
        $flag_slugs = array(config("constant.FLAG_SLUG.SYSTEM"), config("constant.FLAG_SLUG.MANUAL1"));
        $profileFlagReasons = ModelFlagReason::select('model_id', 'flag_reason_id','reason')->whereIn('model_id', $profileModelIds)->whereIn('slug', $flag_slugs)->where('model', 'BatchAssign')->get()->groupBy('model_id');
        
        $profiles = $profiles->toArray();
    
        foreach ($profiles as &$profile) {
            if (Collaborate::where('id', $collaborateId)->first()->track_consistency) {
                $this->model['track_consistency'] = 1;
                $foodBillShot = \DB::table('collaborate_tasting_header')
                    ->where('collaborate_tasting_header.collaborate_id', $collaborateId)
                    ->where('header_selection_type', 3)
                    ->join('collaborate_tasting_questions', 'collaborate_tasting_questions.header_type_id', '=', 'collaborate_tasting_header.id')
                    ->where('collaborate_tasting_questions.track_consistency', 1)
                    ->join('collaborate_tasting_user_review', 'collaborate_tasting_user_review.question_id', '=', 'collaborate_tasting_questions.id')
                    ->where('collaborate_tasting_user_review.profile_id', $profile['profile']['id'])
                    ->where('collaborate_tasting_user_review.batch_id', $id)
                    ->get();
                $foodShots = $foodBillShot != null ? $foodBillShot->pluck('meta')->toArray() : null;
                foreach ($foodShots as &$foodShot) {
                    $foodShot = json_decode($foodShot);
                }
                $profile['foodBillShot'] = $foodShots;
                $batch = \DB::table('collaborate_batches_assign')->where('batch_id', $id)->where('profile_id', $profile['profile']['id'])->first();
                $profile['bill_verified'] = $batch->bill_verified;
                $profile['address_id'] = $batch->address_id;
            }
            $currentStatus = Redis::get("current_status:batch:$id:profile:" . $profile['profile']['id']);
            if ($currentStatus == 3) {
                $profileId = $profile['profile']['id'];
                $reviewCompletionData = \DB::select("SELECT MIN(created_at) as start_time,
                            MAX(updated_at) as completion_timestamp, (SELECT MIN(created_at) FROM `collaborate_tasting_entry_mapping` where profile_id=$profileId AND collaborate_id=$collaborateId AND batch_id=$id) as start_time_v2 FROM `collaborate_tasting_user_review` 
                            where current_status=3 AND profile_id=$profileId AND collaborate_id=$collaborateId AND batch_id=$id");

                $profile["review_completion"] = null;
                if (count($reviewCompletionData) > 0) {
                    $data = [];

                    $timestamp = strtotime($reviewCompletionData[0]->completion_timestamp);
                    $date = date('d M Y', $timestamp);
                    $time = date('h:i:s A', $timestamp);
                    
                    if(isset($reviewCompletionData[0]->start_time_v2)){
                        $durationInSec = strtotime($reviewCompletionData[0]->completion_timestamp) - strtotime($reviewCompletionData[0]->start_time_v2);                        
                    }else{
                        $durationInSec = strtotime($reviewCompletionData[0]->completion_timestamp) - strtotime($reviewCompletionData[0]->start_time);
                    }
                    $duration = $this->secondsToTime($durationInSec);

                    $data[] = ["title" => "Date", "value" => $date];
                    $data[] = ["title" => "Time", "value" => $time];
                    $data[] = ["title" => "Duration", "value" => $duration];

                    $profile["review_completion"] = $data;
                }

                $profile["txn_status"] = $this->getTxnStatusForApplicant($id,$profileId);

                $modelId = $profileModelIds[$profileId];
                // check if review is flagged or not & add color for flagged review
                if(isset($profileFlagValues[$profileId]) && $profileFlagValues[$profileId] == 1){
                    // check the reason and add color based on that
                    $flag_reasons = $profileFlagReasons[$modelId]->pluck('reason')->toArray();
                    
                    $profile['flag_color'] = config("constant.FLAG_COLORS.default");
                    $employee_reason_slug = "tagtaste_employee";
                    if(in_array(config("constant.FLAG_REASONS_TEXT.".$employee_reason_slug), $flag_reasons)){
                        $profile['flag_color'] = config("constant.FLAG_COLORS.".$employee_reason_slug);
                    }
                } else { // if a review is unflagged
                    // check weather it was previously flagged or not
                    $profile['prev_flagged'] = isset($profileFlagReasons[$modelId]) ? 1 : 0;
                }
            }
            $profile['current_status'] = !is_null($currentStatus) ? (int)$currentStatus : 0;
        }

        //count of sensory trained
        $countSensory = \DB::table('profiles')
            ->select('profiles.id')
            ->where('profiles.is_sensory_trained', 1)
            ->whereIn('id', array_column($pId, "profile_id"))
            ->get();

        //count of experts
        $countExpert = \DB::table('profiles')
            ->select('profiles.id')
            ->where('profiles.is_expert', 1)
            ->whereIn('id', array_column($pId, "profile_id"))
            ->get();
        //count of super tasters
        $countSuperTaste = \DB::table('profiles')
            ->select('profiles.id')
            ->where('profiles.is_tasting_expert', 1)
            ->whereIn('id', array_column($pId, "profile_id"))
            ->get();
        // dd($countSuperTaste);

        $this->model["overview"][] = ['title' => "Sensory Trained", "count" => $countSensory->count()];
        $this->model["overview"][] = ['title' => "Experts", "count" => $countExpert->count()];
        $this->model["overview"][] = ['title' => "Super Taster", "count" => $countSuperTaste->count()];
        $this->model['applicants'] = $profiles;
        $this->model['batch'] = Collaborate\Batches::where('id', $id)->first();
        //tabs
        $this->model['assignedCount'] = BatchAssign::where('batch_id', $id)->whereIn('profile_id', array_column($pId, "profile_id"))->distinct()->get(['profile_id'])->count();
        $this->model['inProgressUserCount'] = Review::where('collaborate_id', $collaborateId)->where("current_status",2)->where('batch_id', $id)->whereIn('profile_id', array_column($pId, "profile_id"))->distinct()->get(['profile_id'])->count();
        $this->model['reviewedCount'] = Review::where('collaborate_id', $collaborateId)->where("current_status",3)->where('batch_id', $id)->whereIn('profile_id', array_column($pId, "profile_id"))->distinct()->get(['profile_id'])->count();
        $userCountWithbegintasting = BatchAssign::where('batch_id', $id)->where("begin_tasting",1)->whereIn('profile_id', array_column($pId, "profile_id"))->distinct()->get(['profile_id'])->count(); 
        $this->model['beginTastingCount'] = $this->model['assignedCount'] - $userCountWithbegintasting;
        $this->model['notifiedUserCount'] = $userCountWithbegintasting - ($this->model['reviewedCount'] + $this->model['inProgressUserCount']);
        return $this->sendResponse();
    }

    function getTxnStatusForApplicant($batchId, $profileId){
        $getPaymentDetails =  \DB::table("payment_links")
                            ->select('payment_status.*')
                            ->join('payment_status', 'payment_links.status_id', '=', 'payment_status.id')
                            ->where('payment_links.sub_model_id', '=', $batchId)
                            ->where('payment_links.profile_id', '=', $profileId)
                            ->where('payment_links.model_type', '=', 'Private Review')
                            ->whereNull('payment_links.deleted_at')
                            ->first();

        return $getPaymentDetails;
    }

    function secondsToTime($seconds)
    {
        $s = $seconds % 60;
        $m = floor(($seconds % 3600) / 60);
        $h = floor(($seconds % 86400) / 3600);
        $d = floor(($seconds % 2592000) / 86400);
        $M = floor($seconds / 2592000);
        $durationStr = "";
        if ($M > 0) {
            $durationStr .= "$M month ";
        }

        if ($d > 0) {
            $durationStr .= "$d day ";
        }

        if ($h > 0) {
            $durationStr .= "$h hr ";
        }

        if ($m > 0) {
            $durationStr .= "$m min ";
        }

        if ($s > 0) {
            $durationStr .= "$s sec";
        }

        return $durationStr;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $collaborateId, $id)
    {
        $inputs = $request->except(['_method', '_token']);
        $batches = $this->model->where('id', $id)->where('collaborate_id', $collaborateId)->first();

        if (!$batches) {
            return $this->sendError("No batch available");
        }

        $this->model = $batches->update($inputs);
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $collaborateId, $id)
    {
        $batches = $this->model->where('id', $id)->where('collaborate_id', $collaborateId)->first();
        $this->model = $batches->delete();
        return $this->sendResponse();
    }

    public function getReviewTimeline(Request $request, $collaborateId, $batchId, $profileId){
        $currentStatus = Redis::get("current_status:batch:$batchId:profile:" . $profileId);
        if($currentStatus != 3){
            $this->model = false;
            return $this->sendNewError("You have not completed this review yet.");
        }

        $timeline_data = CollaborateTastingEntryMapping::where("collaborate_id",$collaborateId)->where("batch_id",$batchId)->where("profile_id",$profileId)->orderBy("created_at", "asc")->whereNull("deleted_at")->get();
        
        $applicant = Collaborate\Applicant::where('collaborate_id', $collaborateId)
        ->whereNotNull('shortlisted_at')
        ->whereNull('rejected_at')
        ->where('profile_id', $profileId)->first();

        $submission_status = [];
        $submission_status["title"] = "SUBMISSION";
        $submission_status["is_collapsed"] = false;
        $timeline = []; 
        $last_activity = null;
        $last_header = null;
        foreach($timeline_data as $t){
            $timeline_obj = [];
            $timeline_obj["header_id"] = $t->header_id;
            if($t->activity == config("constant.REVIEW_ACTIVITY.START")){
                $timeline_obj["title"] = "BEGIN";
                $timeline_obj["color_code"] = "#00A146";
                $timeline_obj["line_color_code"] = "#66C790";
            }else if($t->activity == config("constant.REVIEW_ACTIVITY.SECTION_SUBMIT") || $t->activity == config("constant.REVIEW_ACTIVITY.END")){
                $timeline_obj["title"] = $t->header_title;
                $timeline_obj["color_code"] = "#171717";
                $timeline_obj["line_color_code"] = "#747474";
            }

            if($last_header == $t->header_id && $last_activity == $t->activity){
                $last_obj = array_pop($timeline);
                $last_timestamps = $last_obj["timestamps"];
                array_push($last_timestamps, ["title"=>date("d M Y, h:i:s A", strtotime($t->created_at))]);
                $last_obj["timestamps"] = $last_timestamps;
                array_push($timeline, $last_obj);
            }else{
                $timeline_obj["timestamps"] = [["title"=>date("d M Y, h:i:s A", strtotime($t->created_at))]];
                array_push($timeline, $timeline_obj);

                if($t->activity == config("constant.REVIEW_ACTIVITY.END")){
                    array_push($timeline, ["title"=>"END", "color_code"=>"#00AEB3","line_color_code"=>"#66CED1"]);    
                }    
            }
            $last_header = $t->header_id;
            $last_activity = $t->activity;
        }

        if(count($timeline) == 0){
            $reviewCompletionData = \DB::select("SELECT MIN(created_at) as start_time,
            MAX(updated_at) as completion_timestamp FROM `collaborate_tasting_user_review` 
            where current_status=3 AND profile_id=$profileId AND collaborate_id=$collaborateId AND batch_id=$batchId");

            //insert begin for old data
            $timeline_obj = ["title"=>"BEGIN", "color_code"=>"#00A146","line_color_code"=>"#66C790"];
            $timeline_obj["timestamps"] = [["title"=>date("d M Y, h:i:s A", strtotime($reviewCompletionData[0]->start_time))]];

            if(isset($entry_timestamp)){
                $timeline_obj["timestamps"] = [["title"=>date("d M Y, h:i:s A", strtotime($entry_timestamp->created_at))]];
            }
            array_push($timeline, $timeline_obj);    

            //insert end for old data
            $timeline_obj = ["title"=>"END", "color_code"=>"#00AEB3","line_color_code"=>"#66CED1"];
            $timeline_obj["timestamps"] = [["title"=>date("d M Y, h:i:s A", strtotime($reviewCompletionData[0]->completion_timestamp))]];
            array_push($timeline, $timeline_obj);  
            
            $durationInSec = strtotime($reviewCompletionData[0]->completion_timestamp) - strtotime($reviewCompletionData[0]->start_time);

            $duration = $this->secondsToTime($durationInSec);
        }else{
            $entry_timestamp = $timeline_data[0]->created_at ?? null;
            $exit_timestamp =  $timeline_data[count($timeline_data) - 1]->created_at ?? null;

            if($entry_timestamp != null && $exit_timestamp != null){
                $durationInSec = strtotime($exit_timestamp) - strtotime($entry_timestamp);     
                $duration = $this->secondsToTime($durationInSec);           
            }else{
                $duration = "-";
            }
        }

        // flag review data
        $profileBatchData = BatchAssign::where('batch_id', $batchId)->where('collaborate_id', $collaborateId)->where('profile_id', $profileId)->first();

        $modelId = $profileBatchData->id;
        $profileFlagReasons = ModelFlagReason::select('model_id', 'flag_reason_id','reason', 'slug', 'created_at')->where('model_id', $modelId)->where('model', 'BatchAssign')->get()->groupBy('model_id');

        $flag_text = '';
        if(!($profileFlagReasons->isEmpty())){
            // get specific review reasons data in decending order.
            $profileFlagReasons = $profileFlagReasons[$modelId]->sortByDesc('created_at');
            $lastReasonSlug = $profileFlagReasons->pluck('slug')->first();
        
            if($lastReasonSlug == 'system_flag'){
                $profileFlagReasons = $profileFlagReasons->pluck('reason')->toArray(); 
                $total_reasons = count($profileFlagReasons);
                $sec_last_index = $total_reasons - 2;
                $flag_text = 'Flagged for';
                $reason_texts = '';
                if($total_reasons > 1){
                    for($i=0; $i < $sec_last_index; $i++){
                        $reason_texts = $reason_texts.$profileFlagReasons[$i].', ';
                    }
                    $reason_texts = $reason_texts.$profileFlagReasons[$sec_last_index].' ';
                    $flag_text = $flag_text.' '.$reason_texts.'and '.$profileFlagReasons[$total_reasons - 1].'.';
                } else {
                    $flag_text = $flag_text.' '.$reason_texts.$profileFlagReasons[0].'.';
                }
            } else {
                // last flag/unflag reason
                $flag_text = $profileFlagReasons->pluck('reason')->first();
            }
        }
        $submission_status["is_flag"] = $profileBatchData->is_flag;
        $submission_status["flag_text"] = $flag_text;
        $submission_status["timeline"] = $timeline;        
        $submission_status["duration"] = $duration;
        $this->model = ["submission_status"=>[$submission_status], "profile"=>$applicant->profile];
        return $this->sendNewResponse();
    }

    public function flagUnflagReview(Request $request, $collaborateId, $batchId, $profileId)
    {
        $flag_request = $request->flag;
        $this->model = 0;
        $loggedInProfileId = $request->user()->profile->id;

        //flag or unflag a review
        $profileReview = BatchAssign::where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->where('profile_id', $profileId)->first();
        $reviewFlag = $profileReview->is_flag;

        // check if it's already flagged or unflagged
        if(isset($flag_request) && $flag_request == 1 && $flag_request == $reviewFlag){
            return $this->sendNewError("It is already flagged, it cannot be flagged again.");
        } else if(isset($flag_request) && $flag_request == 0 && $flag_request == $reviewFlag) {
            return $this->sendNewError("It is already Unflagged, it cannot be Unflagged again.");
        }

        $flag = $profileReview->update(['is_flag' => $flag_request]);
        $updateReason = ModelFlagReason::create(['model_id' => $profileReview->id, 'reason' => $request->flag_text, 'slug' => config("constant.FLAG_SLUG.MANUAL".$flag_request), 'model' => 'BatchAssign', 'profile_id' => $loggedInProfileId]);

        if($flag && $updateReason){
            $this->model = 1;
        } else {
            return $this->sendNewError("Something went wrong. Review cannot be flagged or Unflagged.");
        }
        return $this->sendNewResponse();
    }

    public function flagLogs($collaborateId, $batchId, $profileId){
        $model_id = BatchAssign::where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->where('profile_id', $profileId)->first()->id;
        $modelFlagReasons = ModelFlagReason::where('model', 'BatchAssign')->where('model_id', $model_id)->get();
        $this->model = [];
        foreach($modelFlagReasons as $modelFlagReason){
            if($modelFlagReason->slug == config("constant.FLAG_SLUG.MANUAL0")){
                $data['title'] = 'UNFLAGGED';
                $data['color_code'] = config("constant.FLAG_COLORS.unflag_color");
                $data['line_color_code'] = config("constant.FLAG_COLORS.unflag_line_color");
            } else {
                $data['title'] = 'FLAGGED';
                $data['color_code'] = config("constant.FLAG_COLORS.flag_color");
                $data['line_color_code'] = config("constant.FLAG_COLORS.flag_line_color");
            }
            $data['flag_text'] = $modelFlagReason->reason;
            $data['created_at'] = Carbon::parse($modelFlagReason->created_at)->format('Y-m-d H:i:s');
            if(!empty($modelFlagReason->company_id)){
                $data['company'] = Company::where('id', $modelFlagReason->company_id)->first()->toArray();
            } else {
                $data['profile'] = Profile::where('id', $modelFlagReason->profile_id)->first()->toArray();
            }
            $this->model[] = $data;
        }
        return $this->sendNewResponse();
    }

    public function applicantFilters(Request $request, $collaborateId, $batchId)
    {
        $collaborate = Collaborate::where('id', $collaborateId)->where('state', '!=', Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $appliedFilters = $request->input('filters');
        $this->model = $this->getFilters($appliedFilters, $collaborateId, $batchId);
        return $this->sendResponse();
    }

    public function assignBatch(Request $request, $id)
    {
        $collaborate = Collaborate::where('id', $id)->where('state', '!=', Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;

        // if(isset($collaborate->company_id)&& (!is_null($collaborate->company_id)))
        // {
        //     $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$profileId)->exists();
        //     if(!$checkUser){
        //         return $this->sendError("Invalid Collaboration Project.");
        //     }
        // }
        // else if($collaborate->profile_id != $profileId){
        //     return $this->sendError("Invalid Collaboration Project.");
        // }

        $applierProfileIds = $request->input('profile_id');
        $checkUserShortlist = Collaborate\Applicant::where('collaborate_id', $id)->whereIn('profile_id', $applierProfileIds)->where('is_invited', 1)->whereNull('shortlisted_at')->exists();
        if ($checkUserShortlist) {
            return $this->sendError("User is not accepted invitations.");
        }
        $batchId = $request->input('batch_id');
        $checkBatch = \DB::table('collaborate_batches')->where('collaborate_id', $id)->where('id', $batchId)->exists();
        if (!$checkBatch) {
            return $this->sendError("wrong batch for this collaboration.");
        }
        $inputs = [];
        $now = Carbon::now()->toDateTimeString();
        \DB::table('collaborate_batches_assign')->where('collaborate_id', $id)->where('batch_id', $batchId)->whereIn('profile_id', $applierProfileIds)->delete();
        foreach ($applierProfileIds as $applierProfileId) {
            Redis::sAdd("collaborate:$id:profile:$applierProfileId:", $batchId);
            Redis::set("current_status:batch:$batchId:profile:$applierProfileId", 0);
            if ($collaborate->track_consistency)
                $inputs[] = ['profile_id' => $applierProfileId, 'batch_id' => $batchId, 'begin_tasting' => 0, 'created_at' => $now, 'collaborate_id' => $id, 'bill_verified' => 0];
            else
                $inputs[] = ['profile_id' => $applierProfileId, 'batch_id' => $batchId, 'begin_tasting' => 0, 'created_at' => $now, 'collaborate_id' => $id];
        }
        $this->model = \DB::table('collaborate_batches_assign')->insert($inputs);

        return $this->sendResponse();
    }

    public function removeFromBatch(Request $request, $collaborateId)
    {
        $profileIds = $request->input('profile_id');
        $batchId = $request->input('batch_id');
        $checkUserReview = \DB::table('collaborate_batches_assign')->where('begin_tasting', 1)->where('batch_id', $batchId)->whereIn('profile_id', $profileIds)->exists();
        if ($checkUserReview) {
            $this->model = [];
            return $this->sendError("You can not remove from batch.");
        }
        foreach ($profileIds as $profileId) {
            Redis::sRem("collaborate:$collaborateId:profile:$profileId:", $batchId);
        }
        $this->model = \DB::table('collaborate_batches_assign')->where('batch_id', $batchId)->whereIn('profile_id', $profileIds)->delete();

        return $this->sendResponse();
    }

    public function beginTasting(Request $request, $collaborateId)
    {
        $collaborate = Collaborate::where('id', $collaborateId)->where('state', '!=', Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $batchId = $request->input('batch_id');
        $profileIds = $request->input('profile_id');
        if ($request->has("begin_all")) {
            if ($request->input("begin_all") == 1) {
                $profileIds = \DB::table('collaborate_batches_assign')->where('batch_id', $batchId)->where('collaborate_id', $collaborateId)->get()->pluck('profile_id');
            }
        }

        $this->model = \DB::table('collaborate_batches_assign')->where('batch_id', $batchId)->whereIn('profile_id', $profileIds)
            ->update(['begin_tasting' => 1]);
            $who = null;
        if ($this->model) {
            $company = Company::where('id', $collaborate->company_id)->first();
            foreach ($profileIds as $profileId) {
                $currentStatus = Redis::get("current_status:batch:$batchId:profile:$profileId");
                if ($currentStatus == 0) {
                    Redis::set("current_status:batch:$batchId:profile:$profileId", 1);
                }
                
                if (empty($company) && empty($who)) {
                    $who = Profile::where("id", "=", $collaborate->profile_id)->first();
                }
                $collaborate->profile_id = $profileId;

                event(new \App\Events\Actions\BeginTasting($collaborate, $who, null, null, null, $company, $batchId));
            }
        }
        return $this->sendResponse();
    }

    public function getShortlistedPeople(Request $request, $collaborateId, $batchId)
    {
        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $applicants = Collaborate\Applicant::where('collaborate_id', $collaborateId)
            ->whereNotNull('shortlisted_at')->skip($skip)->take($take)->get()->toArray();

        foreach ($applicants as &$applicant) {
            $batches = Collaborate\BatchAssign::where('profile_id', $applicant['profile']['id'])->get()->pluck('batches');
            $applicant['batches'] = $batches;
        }
        $this->model = $applicants;
        return $this->sendResponse();
    }

    public function getShortlistedSearchPeople(Request $request, $collaborateId, $batchId)
    {
        $query = $request->input('term');

        $profileIds = \App\Recipe\Profile::select('profiles.id')
            ->join('users', 'profiles.user_id', '=', 'users.id')->where('users.name', 'like', "%$query%")
            ->get()->pluck('id');
        $applicants = Collaborate\Applicant::where('collaborate_id', $collaborateId)->whereIn('profile_id', $profileIds)
            ->whereNotNull('shortlisted_at')->get()->toArray();

        foreach ($applicants as &$applicant) {
            $batches = Collaborate\BatchAssign::where('profile_id', $applicant['profile']['id'])->get()->pluck('batches');
            $applicant['batches'] = $batches;
        }
        $this->model = $applicants;
        return $this->sendResponse();
    }

    public function userBatches(Request $request, $collaborateId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $collaborate = \App\Recipe\Collaborate::where('id', $collaborateId)->first()->toArray();
        $batchIds = \DB::table('collaborate_batches_assign')->where('collaborate_id', $collaborateId)->where('profile_id', $loggedInProfileId)->where('begin_tasting', 1)
            ->get()->pluck('batch_id');
        $count = count($batchIds);
        $batchIdArray = [];
        if ($count) {
            foreach ($batchIds as &$batchId) {
                $batchIdArray[] = "batch:" . $batchId;
            }
            $batchInfos = Redis::mGet($batchIdArray);
            $batches = [];
            foreach ($batchInfos as &$batchInfo) {
                $batchInfo = json_decode($batchInfo);
                $currentStatus = Redis::get("current_status:batch:$batchInfo->id:profile:" . $loggedInProfileId);
                $batch = \DB::table('collaborate_batches_assign')
                    ->where('batch_id', $batchInfo->id)
                    ->where('profile_id', $loggedInProfileId)
                    ->first();
                $batchInfo->current_status = !is_null($currentStatus) ? (int)$currentStatus : 0;
                $batchInfo->address_id = $batch->address_id;
                $batchInfo->bill_verified = $batch->bill_verified;
                $paymentOnBacth = \DB::table('payment_details')
                    ->where('model_id', $batchInfo->collaborate_id)
                    ->where('sub_model_id', $batchInfo->id)
                    ->where('is_active', 1)
                    ->first();
                $batchInfo->isPaid = PaymentHelper::getisPaidMetaFlag($paymentOnBacth);
                if ($batchInfo->current_status != 0) {
                    $batches[] = $batchInfo;
                }
            }
        }
        $collaborate['batches'] = $count > 0 ? $batches : [];
        $this->model = $collaborate;
        return $this->sendResponse();
    }

    public function getCurrentStatus(Request $request, $collaborateId, $batchId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $currentStatus = Redis::get("current_status:batch:$batchId:profile:" . $loggedInProfileId);
        $this->model = !is_null($currentStatus) ? (int)$currentStatus : 0;
        return $this->sendResponse();
    }

    public function reports(Request $request, $collaborateId, $batchId, $headerId)
    {
        $collaborate = Collaborate::where('id', $collaborateId)->where('state', '!=', Collaborate::$state[1])->first();
        $collaborate_batch = Batches::where('id', $batchId)->where('collaborate_id', $collaborateId)->exists();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        elseif(!$collaborate_batch)
        {
            return $this->sendError("Product doesn't belongs to this collaboration");
        }

        // if(isset($collaborate->company_id)&& (!is_null($collaborate->company_id)))
        // {
        //     $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$profileId)->exists();
        //     if(!$checkUser){
        //         return $this->sendError("Invalid Collaboration Project.");
        //     }
        // }
        // else if($collaborate->profile_id != $profileId){
        //     return $this->sendError("Invalid Collaboration Project.");
        // }

        $withoutNest = \DB::table('collaborate_tasting_questions')->where('collaborate_id', $collaborateId)
            ->whereNull('parent_question_id')->where('header_type_id', $headerId)->orderBy('id')->get();
        $withNested = \DB::table('collaborate_tasting_questions')->where('collaborate_id', $collaborateId)
            ->whereNotNull('parent_question_id')->where('header_type_id', $headerId)->orderBy('id')->get();

        foreach ($withoutNest as &$data) {
            if (isset($data->questions) && !is_null($data->questions)) {
                $data->questions = json_decode($data->questions);
            }
        }
        foreach ($withoutNest as &$data) {
            $i = 0;
            foreach ($withNested as $item) {
                if ($item->parent_question_id == $data->id) {
                    $item->questions = json_decode($item->questions);
                    $item->questions->id = $item->id;
                    $item->questions->is_nested_question = $item->is_nested_question;
                    $item->questions->is_mandatory = $item->is_mandatory;
                    $item->questions->is_active = $item->is_active;
                    $item->questions->parent_question_id = $item->parent_question_id;
                    $item->questions->header_type_id = $item->header_type_id;
                    $item->questions->collaborate_id = $item->collaborate_id;
                    $data->questions->questions{
                        $i} = $item->questions;
                    $i++;
                }
            }
        }

        //filters data
        $filters = $request->input('filters');
        $resp = $this->getFilterProfileIds($filters, $collaborateId);
        $profileIds = $resp['profile_id'];
        $type = $resp['type'];
        $boolean = 'and';
        $totalApplicants = \DB::table('collaborate_tasting_user_review')->where('value', '!=', '')->where('current_status', 3)->where('collaborate_id', $collaborateId)
            ->whereIn('profile_id', $profileIds, $boolean, $type)->where('batch_id', $batchId)->distinct()->get(['profile_id'])->count();
        $model = [];
        foreach ($withoutNest as $data) {
            $reports = [];
            if (isset($data->questions) && !is_null($data->questions)) {
                $reports['question_id'] = $data->id;
                $reports['title'] = $data->title;
                $reports['subtitle'] = $data->subtitle;
                $reports['is_nested_question'] = $data->is_nested_question;
                $reports['question'] = $data->questions;
                $trackOptions = [];
                if (isset($data->questions->track_consistency) && $data->questions->track_consistency) {
                    if (isset($data->questions->nested_option_list)) {
                        $s_ids = explode(',', $data->questions->nested_option_consistency);
                        foreach ($s_ids as $s_id) {
                            $opt = \DB::table('collaborate_tasting_nested_options')
                                ->where('sequence_id', $s_id)
                                ->where('question_id', $data->id)
                                ->first();
                            if ($opt != null) {
                                $opt->intensity_consistency = $data->questions->intensity_consistency;
                                $opt->intensity_value = $data->questions->intensity_value;
                                $opt->intensity_type = $data->questions->intensity_type;
                                $opt->benchmark_intensity = $data->questions->benchmark_intensity;
                                $opt->benchmark_score = $data->questions->benchmark_score;
                                $trackOptions[] = $opt;
                            }
                        }
                    } else {
                        foreach ($data->questions->option as $option) {
                            if ($option->track_consistency) {
                                $opt = $option;
                                $trackOptions[] = $opt;
                            }
                        }
                    }
                }
                if (isset($data->questions->is_nested_question) && $data->questions->is_nested_question == 1) {
                    $subAnswers = [];
                    foreach ($data->questions->questions as $item) {
                        $subReports = [];
                        $subReports['question_id'] = $item->id;
                        $subReports['title'] = $item->title;
                        $subReports['subtitle'] = isset($item->subtitle) ? $item->subtitle : null;
                        $subReports['is_nested_question'] = $item->is_nested_question;
                        $subReports['total_applicants'] = $totalApplicants;
                        $subReports['total_answers'] = \DB::table('collaborate_tasting_user_review')->where('current_status', 3)->where('collaborate_id', $collaborateId)
                            ->whereIn('profile_id', $profileIds, $boolean, $type)->where('batch_id', $batchId)->where('question_id', $item->id)->distinct()->get(['profile_id'])->count();
                        $answers = \DB::table('collaborate_tasting_user_review')->select('leaf_id', 'value', \DB::raw('count(*) as total'), 'option_type')->selectRaw("GROUP_CONCAT(intensity) as intensity")
                            ->where('current_status', 3)->whereIn('profile_id', $profileIds, $boolean, $type)->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->where('question_id', $item->id)
                            ->orderBy('question_id', 'ASC')->orderBy('total', 'DESC')->groupBy('question_id', 'value', 'leaf_id', 'option_type')->get();
                        $answersAnyOther = \DB::table('collaborate_tasting_user_review')->select('leaf_id', \DB::raw('count(*) as total'), 'option_type')->selectRaw("GROUP_CONCAT(intensity) as intensity")->where('current_status', 3)
                            ->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->where('question_id', $data->id)
                            ->whereIn('profile_id', $profileIds, $boolean, $type)->orderBy('question_id', 'ASC')->orderBy('total', 'DESC')->groupBy('question_id', 'leaf_id', 'option_type')->where('option_type', '=', 1)->get();
                        $answers = $answers->merge($answersAnyOther);
                        $options = isset($item->option) ? $item->option : [];
                        foreach ($answers as &$answer) {
                            if (isset($item->is_nested_option) && $item->is_nested_option && $answer->option_type == 1) {
                                $answer->value = \DB::table('collaborate_tasting_nested_options')
                                    ->where('id', $answer->leaf_id)
                                    ->first()
                                    ->value;
                            }

                            $value = [];
                            foreach ($options as $option) {
                                if ($option->id == $answer->leaf_id) {
                                    if ($answer->option_type == '1') {
                                        $answer->value = $option->value;
                                    }
                                    if (isset($option->is_intensity) && $option->is_intensity == 1 && $option->intensity_type == 2) {
                                        $answerIntensity = $answer->intensity;
                                        $answerIntensity = explode(",", $answerIntensity);
                                        $questionIntensity = $option->intensity_value;
                                        $questionIntensity = explode(",", $questionIntensity);
                                        foreach ($questionIntensity as $x) {
                                            $count = 0;
                                            foreach ($answerIntensity as $y) {
                                                if ($this->checkValue($x, $y))
                                                    $count++;
                                            }
                                            $value[] = ['value' => $x, 'count' => $count];
                                        }
                                    } else if (isset($option->is_intensity) && $option->is_intensity == 1 && $option->intensity_type == 1) {
                                        $answerIntensity = $answer->intensity;
                                        $answerIntensity = explode(",", $answerIntensity);
                                        $questionIntensityValue = $option->intensity_value;
                                        $questionIntensity = [];
                                        for ($i = 1; $i <= $questionIntensityValue; $i++) {
                                            $questionIntensity[] = $i;
                                        }
                                        foreach ($questionIntensity as $x) {
                                            $count = 0;
                                            foreach ($answerIntensity as $y) {
                                                if ($y == $x)
                                                    $count++;
                                            }
                                            $value[] = ['value' => $x, 'count' => $count];
                                        }
                                    }
                                    $answer->is_intensity = isset($option->is_intensity) ? $option->is_intensity : null;
                                    $answer->intensity_value = isset($option->intensity_value) ? $option->intensity_value : null;
                                    $answer->intensity_type = isset($option->intensity_type) ? $option->intensity_type : null;
                                }
                            }
                            $answer->intensity = $value;
                        }
                        $subReports['answer'] = $answers;
                        $subAnswers[] = $subReports;
                    }
                    $reports['nestedAnswers'] = $subAnswers;
                } else
                    unset($reports['nestedAnswers']);
                $reports['total_applicants'] = $totalApplicants;
                $totalQueResponse = \DB::table('collaborate_tasting_user_review')->where('current_status', 3)->where('collaborate_id', $collaborateId)
                    ->whereIn('profile_id', $profileIds, $boolean, $type)->where('batch_id', $batchId)->where('question_id', $data->id)->distinct()->get(['profile_id'])->count();
                $reports['total_answers'] = $totalQueResponse;
                if (isset($data->questions->select_type) && $data->questions->select_type == 3) {
                    $reports['answer'] = Collaborate\Review::where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->where('question_id', $data->id)
                        ->whereIn('profile_id', $profileIds, $boolean, $type)->where('current_status', 3)->where('tasting_header_id', $headerId)->skip(0)->take(3)->get();
                } else  if (isset($data->questions->select_type) && $data->questions->select_type == config("constant.SELECT_TYPES.SELFIE_TYPE")) {
                    $values =  \DB::table('collaborate_tasting_user_review')->select('users.name', 'profiles.id', 'profiles.handle', 'profiles.is_tasting_expert',  'collaborate_tasting_user_review.meta', 'collaborate_tasting_user_review.updated_at', 'collaborate_tasting_user_review.intensity', 'collaborate_tasting_user_review.leaf_id', 'profiles.verified as is_verified', 'profiles.image_meta', 'collaborate_tasting_user_review.option_type')->join('profiles', 'profiles.id', 'collaborate_tasting_user_review.profile_id')
                        ->join('users', 'users.id', 'profiles.user_id')->where('collaborate_tasting_user_review.collaborate_id', $collaborateId)->where('collaborate_tasting_user_review.batch_id', $batchId)->where('collaborate_tasting_user_review.question_id', $data->id)
                        ->whereIn('collaborate_tasting_user_review.profile_id', $profileIds, $boolean, $type)->where('collaborate_tasting_user_review.current_status', 3)->where('collaborate_tasting_user_review.tasting_header_id', $headerId)->skip(0)->take(config("constant.DEFAULT_SIZE"))->get();
                    $reports['answer'] = [];
                    $dataset = [];
                    $profile = [];
                    foreach ($values as $value) {
                        $dataset['leaf_id'] = $value->leaf_id;
                        $dataset['option_type'] = $value->option_type;
                        $profile['id'] = $value->id;
                        $profile['name'] = $value->name;
                        $profile['handle'] = $value->handle;
                        $profile['superTaster'] = $value->is_tasting_expert;
                        $profile['verified'] = $value->is_verified;
                        $profile['image_meta'] = $value->image_meta;
                        $dataset['profile'] = $profile;
                        $dataset['meta'] = json_decode($value->meta);
                        $dataset['intensity'] = [];
                        $dataset['is_intensity'] = 0;
                        $dataset['intensity_value'] = null;
                        $dataset['intensity_type'] = null;
                        $dataset['updated_at'] =  $value->updated_at;

                        $reports['answer'][] = $dataset;
                    }
                    //  dd($reports['answer']);
                }else  if (isset($data->questions->select_type) && $data->questions->select_type == config("constant.SELECT_TYPES.RANK_TYPE")){
                    // $reports['answer'][] = 'rank que';
                    $optionList = $data->questions->option;
                    $maxRank = $data->questions->max_rank;
                    $highestValue = 0;
                    foreach($optionList as $option){
                        $optionResponse = \DB::table('collaborate_tasting_user_review')->select('leaf_id', 'value_id','value',\DB::raw('count(*) as total'))->where('current_status',3)->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->where('question_id', $data->id)->where('leaf_id',$option->id)->whereIn('profile_id', $profileIds, $boolean, $type)->groupBy('question_id', 'leaf_id', 'value_id', 'value')->get();

                        $sum = 0;
                        $totalOptionResponse = 0;
                        foreach($optionResponse as $optionAns){
                            $sum = $sum + ($optionAns->total*($maxRank-$optionAns->value_id + 1));     
                            $totalOptionResponse += $optionAns->total;
                        }
                        $option->total = $totalOptionResponse;
                        $option->reverse_avg = $totalOptionResponse == 0 ? 0 : $sum/$totalOptionResponse;
                        $option->ranked_by_percentage = $totalQueResponse == 0 ? 0 : $totalOptionResponse/$totalQueResponse;
                        $option->multiply = $option->reverse_avg*$option->ranked_by_percentage;
                        $option->total = $totalOptionResponse;
                        if($highestValue < $option->multiply){
                            $highestValue = $option->multiply;
                        }
                    }
                    
                    $finalOptionList = [];
                    foreach($optionList as $key => $option){
                        $indexedValue = $highestValue == 0 ? 0 : ($option->multiply*100)/$highestValue;
                        $option->percentage = round($indexedValue,2);
                        $option->high = $highestValue;

                        $colorCode = Helper::getIndexedColor($key);
                        $finalOptioList[] = ["leaf_id"=>$option->id,"total"=>$option->total, "option_type"=>$option->option_type, "value"=>$option->value,"percentage"=>$option->percentage, "color_code"=>$colorCode];                    
                    }
                    usort($finalOptioList, function($a, $b) {return $a['percentage'] < $b['percentage'];});

                    $reports['answer'] = $finalOptioList; 
                    unset($finalOptioList);
                } else  if (isset($data->questions->select_type) && $data->questions->select_type == config("constant.SELECT_TYPES.RANGE_TYPE")){
                    $finalOptionList = [];
                    $optionList = $data->questions->option;
                    $totalSum = 0;
                    $totalResponse = 0;
                    foreach($optionList as $option){
                        $optionResponseCount = \DB::table('collaborate_tasting_user_review')->where('current_status',3)->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->where('question_id', $data->id)->where('leaf_id',$option->id)
                        ->whereIn('profile_id', $profileIds, $boolean, $type)->count();

                        $totalResponse += $optionResponseCount;
                        $totalSum += $optionResponseCount*$option->value;

                        $finalOptionList[] = ["id"=>$option->id, "value"=>$option->value, "label"=>$option->label, "count"=>$optionResponseCount];
                    }

                    $average = $totalResponse == 0 ? 0 : number_format((float)($totalSum/$totalResponse), 2, '.', '');

                    $roundedAvgOption = Helper::getOptionForValue($average, $optionList);
                    $average = empty($roundedAvgOption->label) ? $average : $average." (".$roundedAvgOption->label.")";

                    // $average = $totalResponse == 0 ? 0 : round($totalSum/$totalResponse,2);
                    $reports['answer'] = array(["total"=>$totalResponse,"value"=>$average,"option"=>$finalOptionList]);
                    unset($finalOptioList);
                } else {
                    $answers = \DB::table('collaborate_tasting_user_review')->select('leaf_id', \DB::raw('count(*) as total'), 'option_type', 'value')->selectRaw("GROUP_CONCAT(intensity) as intensity")->where('current_status', 3)
                        ->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->where('question_id', $data->id)
                        ->whereIn('profile_id', $profileIds, $boolean, $type)->orderBy('question_id', 'ASC')->orderBy('total', 'DESC')->groupBy('question_id', 'leaf_id', 'option_type', 'value')->where('option_type', '!=', 1)->get();
                    $answersAnyOther = \DB::table('collaborate_tasting_user_review')->select('leaf_id', \DB::raw('count(*) as total'), 'option_type')->selectRaw("GROUP_CONCAT(intensity) as intensity")->where('current_status', 3)
                        ->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->where('question_id', $data->id)
                        ->whereIn('profile_id', $profileIds, $boolean, $type)->orderBy('question_id', 'ASC')->orderBy('total', 'DESC')->groupBy('question_id', 'leaf_id', 'option_type')->where('option_type', '=', 1)->get();
                    
                    $answers = $answers->merge($answersAnyOther);
                    $options = isset($data->questions->option) ? $data->questions->option : [];
                    foreach ($answers as &$answer) {
                        $value = [];
                        if (isset($data->questions->is_nested_option) && $data->questions->is_nested_option == 1 && isset($data->questions->intensity_value) && isset($answer->intensity)) {
                            if ($data->questions->intensity_type == 2) {
                                $answerIntensity = $answer->intensity;
                                $answerIntensity = explode(",", $answerIntensity);
                                $questionIntensity = $data->questions->intensity_value;
                                $questionIntensity = explode(",", $questionIntensity);
                                foreach ($questionIntensity as $x) {

                                    $count = 0;
                                    $intensityConsistency = 0;
                                    $benchmarkIntensity = null;
                                    foreach ($trackOptions as $key => $trackOption) {
                                        if ($answer->leaf_id == $trackOption->id && $x == ucwords($trackOption->intensity_consistency)) {
                                            $answer->track_consistency = 1;
                                            $answer->benchmark_score = $trackOption->benchmark_score;
                                            $intensityConsistency = 1;
                                            $benchmarkIntensity = $trackOption->benchmark_intensity;
                                            unset($trackOptions[$key]);
                                        }
                                    }
                                    foreach ($answerIntensity as $y) {
                                        if ($this->checkValue($x, $y))
                                            $count++;
                                    }
                                    $value[] = ['value' => $x, 'count' => $count, 'track_consistency' => $intensityConsistency, 'benchmark_intensity' => $benchmarkIntensity];
                                }
                            } else if ($data->questions->intensity_type == 1) {
                                $answerIntensity = $answer->intensity;
                                $answerIntensity = explode(",", $answerIntensity);
                                $questionIntensityValue = $data->questions->intensity_value;
                                $questionIntensity = [];
                                if (isset($data->questions->initial_intensity)) {
                                    $temp = $data->questions->initial_intensity;
                                } else {
                                    $temp = 1;
                                }
                                for ($i = $temp; $i < (int)$questionIntensityValue + $temp; $i++) {
                                    $questionIntensity[] = $i;
                                }
                                foreach ($questionIntensity as $x) {
                                    $count = 0;
                                    $intensityConsistency = 0;
                                    $benchmarkIntensity = null;
                                    foreach ($trackOptions as $key => $trackOption) {
                                        if ($answer->leaf_id == $trackOption->id && $x == ucwords($trackOption->intensity_consistency)) {
                                            $answer->track_consistency = 1;
                                            $answer->benchmark_score = $trackOption->benchmark_score;
                                            $benchmarkIntensity = $trackOption->benchmark_intensity;
                                            $intensityConsistency = 1;
                                            unset($trackOptions[$key]);
                                        }
                                    }
                                    foreach ($answerIntensity as $y) {
                                        if ($y == $x)
                                            $count++;
                                    }
                                    $value[] = ['value' => $x, 'count' => $count, 'track_consistency' => $intensityConsistency, 'benchmark_intensity' => $benchmarkIntensity];
                                }
                            }
                            foreach ($trackOptions as $key => $trackOption) {
                                if ($answer->leaf_id == $trackOption->id) {
                                    $answer->track_consistency = 1;
                                    $answer->benchmark_score = $trackOption->benchmark_score;
                                    unset($trackOptions[$key]);
                                }
                            }
                            $answer->is_intensity = isset($data->questions->is_intensity) ? $data->questions->is_intensity : null;
                            $answer->intensity_value = $data->questions->intensity_value;
                            $answer->intensity_type = $data->questions->intensity_type;
                            $answer->initial_intensity = isset($data->questions->initial_intensity) ? $data->questions->initial_intensity : null;
                        } else {
                            foreach ($options as $option) {
                                if ($option->id == $answer->leaf_id) {
                                    if ($answer->option_type == '1') {
                                        $answer->value = $option->value;
                                    }
                                    if (isset($option->is_intensity) && $option->is_intensity == 1 && $data->questions->select_type != 5 && $option->intensity_type == 2) {
                                        $answerIntensity = $answer->intensity;
                                        $answerIntensity = explode(",", $answerIntensity);
                                        $questionIntensity = $option->intensity_value;
                                        $questionIntensity = explode(",", $questionIntensity);
                                        foreach ($questionIntensity as $x) {
                                            $count = 0;
                                            $intensityConsistency = 0;
                                            $benchmarkIntensity = null;
                                            foreach ($trackOptions as $key => $trackOption) {
                                                if ($answer->leaf_id == $trackOption->id && $x == ucwords($trackOption->intensity_consistency)) {
                                                    $answer->track_consistency = 1;
                                                    $intensityConsistency = 1;
                                                    $benchmarkIntensity = $trackOption->benchmark_intensity;
                                                    $answer->benchmark_score = $trackOption->benchmark_score;
                                                    unset($trackOptions[$key]);
                                                }
                                            }
                                            foreach ($answerIntensity as $y) {

                                                if ($this->checkValue($x, $y))
                                                    $count++;
                                            }
                                            $value[] = ['value' => $x, 'count' => $count, 'track_consistency' => $intensityConsistency, 'benchmark_intensity' => $benchmarkIntensity];
                                        }
                                    } else if (isset($option->is_intensity) && $option->is_intensity == 1 && $data->questions->select_type != 5 && $option->intensity_type == 1) {
                                        $answerIntensity = $answer->intensity;
                                        $answerIntensity = explode(",", $answerIntensity);
                                        $questionIntensityValue = $option->intensity_value;
                                        $questionIntensity = [];
                                        if (isset($data->questions->initial_intensity)) {
                                            $temp = $data->questions->initial_intensity;
                                        } else {
                                            $temp = 1;
                                        }
                                        for ($i = $temp; $i < (int)$questionIntensityValue + $temp; $i++) {
                                            $questionIntensity[] = $i;
                                        }
                                        foreach ($questionIntensity as $x) {
                                            $count = 0;
                                            $intensityConsistency = 0;
                                            $benchmarkIntensity = null;
                                            foreach ($trackOptions as $key => $trackOption) {
                                                if ($answer->leaf_id == $trackOption->id && $answerIntensity == $trackOption->intensity_consistency) {
                                                    $answer->track_consistency = 1;
                                                    $intensityConsistency = 1;
                                                    $benchmarkIntensity = $trackOption->benchmark_intensity;
                                                    $answer->benchmark_score = $trackOption->benchmark_score;
                                                    unset($trackOptions[$key]);
                                                }
                                            }
                                            foreach ($answerIntensity as $y) {
                                                if ($y == $x)
                                                    $count++;
                                            }
                                            $value[] = ['value' => $x, 'count' => $count, 'track_consistency' => $intensityConsistency, 'benchmark_intensity' => $benchmarkIntensity];
                                        }
                                    }
                                    foreach ($trackOptions as $key => $trackOption) {
                                        if ($answer->leaf_id == $trackOption->id) {
                                            $answer->track_consistency = 1;
                                            $answer->benchmark_score = $trackOption->benchmark_score;
                                            unset($trackOptions[$key]);
                                        }
                                    }
                                    $answer->is_intensity = isset($option->is_intensity) ? $option->is_intensity : null;
                                    $answer->intensity_value = isset($option->intensity_value) ? $option->intensity_value : null;
                                    $answer->intensity_type = isset($option->intensity_type) ? $option->intensity_type : null;
                                    $answer->initial_intensity = isset($option->initial_intensity) ? $option->initial_intensity : null;
                                }
                            }
                        }
                        $answer->intensity = $value;
                    }
                    $answers = $this->addConsistencyAnswers($trackOptions, $answers, isset($data->questions->nested_option_list));
                    //dd($answers);
                    $reports['answer'] = $answers;
                }

                if (isset($data->questions->is_nested_option)) {
                    $reports['is_nested_option'] = $data->questions->is_nested_option;
                    if ($data->questions->is_nested_option == 1) {
                        foreach ($reports['answer'] as &$item) {
                            if ($item->option_type == 1) {
                                $item->value = \DB::table('collaborate_tasting_nested_options')
                                    ->where('id', $answer->leaf_id)
                                    ->first()
                                    ->value;
                            }
                            $nestedOption = \DB::table('collaborate_tasting_nested_options')->where('header_type_id', $headerId)
                                ->where('question_id', $data->id)->where('id', $item->leaf_id)->where('value', 'like', $item->value)->first();
                            $item->path = isset($nestedOption->path) ? $nestedOption->path : null;
                        }
                    }
                }

                $model[] = $reports;
            }
        }
        $userCount = 0;
        $headerRatingSum = 0;
        $meta = [];
        $meta['color_code'] = '#7E9B42'; //Default colour for background

        $question = Collaborate\Questions::where('header_type_id', $headerId)->whereRaw("JSON_CONTAINS(questions, '5', '$.select_type')")->first();
        if (!empty($question)) {
            $overallPreferances = \DB::table('collaborate_tasting_user_review')->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->where('current_status', 3)->where('question_id', $question->id)->whereIn('profile_id', $profileIds, $boolean, $type)->get();
            foreach ($overallPreferances as $overallPreferance) {
                if ($overallPreferance->tasting_header_id == $headerId) {
                    $headerRatingSum += $overallPreferance->leaf_id;
                    $userCount++;
                }
            }
            $meta = $this->getRatingMeta($userCount, $headerRatingSum, $question);
        }

        $this->model = ['report' => $model, 'meta' => $meta];

        return $this->sendResponse();
    }

    public function getList(Request $request, $collaborateId, $batchId, $headerId, $questionId)
    {
        $page = $request->input('page');
        $filters = $request->input('filters');
        $resp = $this->getFilterProfileIds($filters, $collaborateId);
        $type = $resp['type'];
        $boolean = 'and';
        $profileIds = $resp['profile_id'];
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $images =  \DB::table('collaborate_tasting_user_review')->select('users.name', 'profiles.id', 'profiles.verified', 'profiles.handle', 'profiles.is_tasting_expert', 'profiles.image_meta', 'collaborate_tasting_user_review.updated_at', 'collaborate_tasting_user_review.meta')->join('profiles', 'profiles.id', 'collaborate_tasting_user_review.profile_id')
            ->join('users', 'users.id', 'profiles.user_id')->where('collaborate_tasting_user_review.collaborate_id', $collaborateId)->where('collaborate_tasting_user_review.batch_id', $batchId)->where('collaborate_tasting_user_review.question_id', $questionId)
            ->whereIn('collaborate_tasting_user_review.profile_id', $profileIds, $boolean, $type)->where('collaborate_tasting_user_review.current_status', 3)->where('collaborate_tasting_user_review.tasting_header_id', $headerId);

        $this->model = [];
        $data = [];
        $data['total_respondants'] = \DB::table('collaborate_tasting_user_review')->where('current_status', 3)->where('collaborate_id', $collaborateId)
            ->whereIn('profile_id', $profileIds, $boolean, $type)->where('batch_id', $batchId)->where('question_id', $questionId)->distinct()->get(['profile_id'])->count();
        $title = \DB::table('collaborate_tasting_questions')->select('title')->where('collaborate_id', $collaborateId)
            ->where('id', $questionId)->where('header_type_id', $headerId)->first();
        $data['title'] = $title->title;
        $images = $images->skip($skip)->take($take)
            ->get();
        $data['images'] = [];
        $imageItem = [];
        $profile = [];
        foreach ($images as $image) {
            $profile['id'] = $image->id;
            $profile['name'] = $image->name;
            $profile['handle'] = $image->handle;
            $profile['superTaster'] = $image->is_tasting_expert;
            $profile['verified'] = $image->verified;
            $profile['image_meta'] = $image->image_meta;
            $imageItem['profile'] = $profile;
            $imageItem['meta'] = json_decode($image->meta);
            $imageItem['updated_at'] = $image->updated_at;
            $data['images'][] =  $imageItem;
        }
        $this->model = $data;
        return $this->sendResponse();
    }
    protected function addConsistencyAnswers($trackOptions, $answers, $isNested)
    {
        foreach ($trackOptions as $trackOption) {
            $mod['leaf_id'] = $trackOption->id;
            $mod['total'] = 0;
            $mod['value'] = $trackOption->value;
            $mod['intensity'] = [];
            $mod['option_type'] = $trackOption->option_type;
            $mod['is_intensity'] = $trackOption->is_intensity;
            $mod['intensity_value'] = $trackOption->intensity_value;
            $mod['intensity_type'] = $trackOption->intensity_type;
            $mod['track_consistency'] = 1;
            $mod['benchmark_score'] = $trackOption->benchmark_score;
            if ($mod['intensity_value'] != null) {
                //dd(ucwords($trackOption->intensity_consistency));
                $int = explode(',', $mod['intensity_value']);
                foreach ($int as $i) {
                    $intensityConsistency = $i == ucwords($trackOption->intensity_consistency) ? 1 : 0;
                    $benchmarkIntensity = $i == ucwords($trackOption->intensity_consistency) ? $trackOption->benchmark_intensity : null;
                    $mod['intensity'][] = ['value' => $i, 'count' => 0, 'track_consitency' => $intensityConsistency, 'benchmark_intensity' => $benchmarkIntensity];
                }
            }
            if ($isNested)
                $mod = (object)$mod;
            $answers[] = $mod;
        }
        return $answers;
    }
    public function filterReports($filters, $collaborateId, $batchId, $headerId, $withoutNest)
    {
        $profileIds = $this->getFilterProfileIds($filters, $collaborateId);
        $totalApplicants = \DB::table('collaborate_tasting_user_review')->where('value', '!=', '')->where('current_status', 3)->where('collaborate_id', $collaborateId)
            ->where('batch_id', $batchId)->whereIn('profile_id', $profileIds)->distinct()->get(['profile_id'])->count();
        $model = [];
        foreach ($withoutNest as $data) {
            $reports = [];
            if (isset($data->questions) && !is_null($data->questions)) {
                $reports['question_id'] = $data->id;
                $reports['title'] = $data->title;
                $reports['subtitle'] = $data->subtitle;
                $reports['is_nested_question'] = $data->is_nested_question;
                $reports['question'] = $data->questions;
                if ($data->questions->is_nested_question == 1) {
                    $subAnswers = [];
                    foreach ($data->questions->questions as $item) {
                        $subReports = [];
                        $subReports['question_id'] = $item->id;
                        $subReports['title'] = $item->title;
                        $subReports['subtitle'] = isset($item->subtitle) ? $item->subtitle : null;
                        $subReports['is_nested_question'] = $item->is_nested_question;
                        $subReports['total_applicants'] = $totalApplicants;
                        $subReports['total_answers'] = \DB::table('collaborate_tasting_user_review')->where('current_status', 3)->where('collaborate_id', $collaborateId)
                            ->where('batch_id', $batchId)->where('question_id', $item->id)->whereIn('profile_id', $profileIds)->distinct()->get(['profile_id'])->count();
                        $subReports['answer'] = \DB::table('collaborate_tasting_user_review')->select('value', 'intensity', \DB::raw('count(*) as total'))->where('current_status', 3)
                            ->where('collaborate_id', $collaborateId)->whereIn('profile_id', $profileIds)->where('batch_id', $batchId)->where('question_id', $item->id)
                            ->orderBy('question_id')->groupBy('question_id', 'value', 'leaf_id', 'intensity')->get();
                        $subAnswers[] = $subReports;
                    }
                    $reports['nestedAnswers'] = $subAnswers;
                } else
                    unset($reports['nestedAnswers']);
                $reports['total_applicants'] = $totalApplicants;
                $reports['total_answers'] = \DB::table('collaborate_tasting_user_review')->where('current_status', 3)->where('collaborate_id', $collaborateId)
                    ->where('batch_id', $batchId)->whereIn('profile_id', $profileIds)->where('question_id', $data->id)->distinct()->get(['profile_id'])->count();
                if (isset($data->questions->select_type) && $data->questions->select_type == 3) {
                    $reports['answer'] = Collaborate\Review::where('collaborate_id', $collaborateId)->whereIn('profile_id', $profileIds)->where('batch_id', $batchId)->where('question_id', $data->id)
                        ->where('current_status', 3)->where('tasting_header_id', $headerId)->skip(0)->take(3)->get();
                } else {
                    $reports['answer'] = \DB::table('collaborate_tasting_user_review')->select('leaf_id', 'value', 'intensity', \DB::raw('count(*) as total'))->where('current_status', 3)
                        ->where('collaborate_id', $collaborateId)->whereIn('profile_id', $profileIds)->where('batch_id', $batchId)->where('question_id', $data->id)
                        ->orderBy('question_id')->groupBy('question_id', 'value', 'leaf_id', 'intensity')->get();
                }

                if (isset($data->questions->is_nested_option)) {
                    $reports['is_nested_option'] = $data->questions->is_nested_option;
                    if ($data->questions->is_nested_option == 1) {
                        foreach ($reports['answer'] as &$item) {
                            $nestedOption = \DB::table('collaborate_tasting_nested_options')->where('header_type_id', $headerId)
                                ->where('question_id', $data->id)->where('id', $item->leaf_id)->where('value', 'like', $item->value)->first();
                            $item->path = isset($nestedOption->path) ? $nestedOption->path : null;
                        }
                    }
                }

                $model[] = $reports;
            }
        }
        $this->model = $model;

        return $this->sendResponse();
    }

    public function filters(Request $request, $collaborateId)
    {
        $collaborate = Collaborate::where('id', $collaborateId)->where('state', '!=', Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }

        // $filters = $request->input('filter');

        $version_num = '';
        if($request->is('*/v1/*')){
            $version_num = 'v1';
        } else if($request->is('*/v2/*')){
            $version_num = 'v2';
        }

        $appliedFilters = $request->input('filters');
        $this->model = $this->dashboardFilters($appliedFilters, $collaborateId, $version_num, 'dashboard_filters');

        return $this->sendResponse();
    }

    public function productFilters(Request $request, $collaborateId, $batchId)
    {
        $collaborate = Collaborate::where('id', $collaborateId)->where('state', '!=', Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }

        $version_num = '';
        $appliedFilters = $request->input('filters');
        $this->model = $this->dashboardFilters($appliedFilters, $collaborateId, $version_num, 'dashboard_product_filters', $batchId);

        return $this->sendResponse();
    }

    public function reportFilters(Request $request, $collaborateId)
    {
        $filters = $request->input('filter');

        $gender = ['Male', 'Female', 'Other'];
        $age = ['< 18', '18 - 35', '35 - 55', '55 - 70', '> 70'];
        $currentStatus = [0, 1, 2, 3];
        $userType = ['Expert', 'Consumer'];
        $sensoryTrained = ["Yes", "No"];
        $superTaster = ["SuperTaster", "Normal"];
        $applicants = \DB::table('collaborate_applicants')->where('collaborate_id', $collaborateId)->get();
        $city = [];
        foreach ($applicants as $applicant) {
            if (isset($applicant->city)) {
                if (!in_array($applicant->city, $city))
                    $city[] = $applicant->city;
            }
        }
        $data = [];
        if (count($filters)) {
            foreach ($filters as $filter) {
                if ($filter == 'gender')
                    $data['gender'] = $gender;
                if ($filter == 'age')
                    $data['age'] = $age;
                if ($filter == 'city')
                    $data['city'] = $city;
                if ($filter == 'current_status')
                    $data['current_status'] = $currentStatus;
                if ($filter ==  'super_taster')
                    $data['super_taster'] = $superTaster;
                if ($filter == 'user_type')
                    $data['user_type'] = $userType;
                if ($filter == 'sensory_trained')
                    $data['sensory_trained'] = $sensoryTrained;
            }
        } else {
            $data = ['gender' => $gender, 'age' => $age, 'city' => $city, "user_type" => $userType, "sensory_trained" => $sensoryTrained, "super_taster" => $superTaster];
        }
        $this->model = $data;
        return $this->sendResponse();
    }

    public function questionFilters(Request $request, $collaborateId)
    {
        $collaborate = Collaborate::where('id', $collaborateId)->where('state', '!=', Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        
        $headers = ReviewHeader::where('is_active',1)->where('collaborate_id',$collaborateId)->whereNotIn('header_selection_type',[0, 3])->orderBy('id')->get();
        $headers_array = [];

        // check for already saved filters
        $savedFilter = \DB::table('collaborate_question_filters')->where('collaborate_id', $collaborateId)->whereNull('deleted_at')->first();
            
        $filterForm = (!is_null($savedFilter)) ? json_decode($savedFilter->value, true) : $savedFilter;

        foreach($headers as $key => $header)
        {
            $headers_array[$key] = $header->toArray();
            $headers_questions[$key] = Questions::select('id','is_mandatory', 'is_active','track_consistency', 'max_rank', 'questions')->where('collaborate_id', $collaborateId)->where('header_type_id', $header->id)->whereNull('parent_question_id')->orderBy('id')->get()->toArray();

            $question_no = 1;

            if(!empty($headers_questions[$key])) {
                foreach($headers_questions[$key] as $index => $value) {
                    $question_json_data = json_decode($value['questions'], true);
                    
                    foreach($question_json_data as $k => $question_json){
                        if($k == 'id'){
                            continue;
                        }
                        $headers_questions[$key][$index][$k] = $question_json;
                        unset($headers_questions[$key][$index]['questions']);
                    }

                    if($headers_questions[$key][$index]["select_type"] == 1 || $headers_questions[$key][$index]["select_type"] == 5)
                    {
                        $question_id = $headers_questions[$key][$index]["id"];
                        $headers_questions[$key][$index]["is_selected"] = $this->checkIfQuestionSelected($question_id, $filterForm);
                        $headers_questions[$key][$index]["question_no"] = $question_no;
                        $headers_array[$key]["questions"][] = $headers_questions[$key][$index];
                    } 
                    else if($headers_questions[$key][$index]["select_type"] == 2)
                    {
                        $question_id = $headers_questions[$key][$index]["id"];
                        $headers_questions[$key][$index]["is_selected"] = $this->checkIfQuestionSelected($question_id, $filterForm);

                        if(isset($headers_questions[$key][$index]["is_nested_option"]) && $headers_questions[$key][$index]["is_nested_option"] == 1)
                        {
                            $global_question_options_info =  Review::select('value','option_type','leaf_id')->where('collaborate_id',$collaborateId)->where('question_id', $headers_questions[$key][$index]["id"])->where('current_status', 3)->distinct()->get()->toArray();

                            // Add id to options
                            if(!empty($global_question_options_info))
                            {
                                $global_question_options_info = array_map(function ($option_array, $index) {
                                    $option_array['id'] = $option_array['leaf_id'];
                                    unset($option_array['leaf_id']);
                                    return $option_array;
                                }, $global_question_options_info, array_keys($global_question_options_info));
                            }

                            $headers_questions[$key][$index]["option"] = $global_question_options_info;
                        }

                        $headers_questions[$key][$index]["question_no"] = $question_no;
                        $headers_array[$key]["questions"][] = $headers_questions[$key][$index];
                    }
                    $question_no++;
                }
            }

            $headers_array[$key]["questions"] = (!isset($headers_array[$key]["questions"]) || empty($headers_array[$key]["questions"])) ? [] : $headers_array[$key]["questions"];
        }

        $this->model = $headers_array;
        return $this->sendResponse(); 
    }

    public function checkIfQuestionSelected($queId, $filterForm){
        $found = false;
        if(!is_null($filterForm))
        {
            foreach($filterForm as $form){
                $questions = $form['questions'];
                foreach($questions as $question){
                    if($question['id'] == $queId){
                        $found = true;
                    }
                }      
            }
        }

        return $found;
    }

    public function comments(Request $request, $collaborateId, $batchId, $headerId, $questionId)
    {
        //filters data
        $filters = $request->input('filters');
        $resp = $this->getFilterProfileIds($filters, $collaborateId);
        $profileIds = $resp['profile_id'];
        $type = $resp['type'];
        $boolean = 'and';

        //paginate
        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $this->model = Collaborate\Review::where('collaborate_id', $collaborateId)->whereIn('profile_id', $profileIds, $boolean, $type)->where('question_id', $questionId)->where('batch_id', $batchId)
            ->where('tasting_header_id', $headerId)->where('current_status', 3)->skip($skip)->take($take)->get();

        return $this->sendResponse();
    }

    public function hutCsv(Request $request, $collaborateId, $batchId)
    {
        $collaborate = Collaborate::where('id', $collaborateId)->where('state', '!=', Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;

        // if(isset($collaborate->company_id)&& (!is_null($collaborate->company_id)))
        // {
        //     $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$profileId)->exists();
        //     if(!$checkUser){
        //         return $this->sendError("Invalid Collaboration Project.");
        //     }
        // }
        // else if($collaborate->profile_id != $profileId){
        //     return $this->sendError("Invalid Collaboration Project.");
        // }

        $this->model = [];
        $profileIds = \DB::table('collaborate_batches_assign')->where('batch_id', $batchId)->get()->pluck('profile_id');

        $applicantDetails = Collaborate\Applicant::where("collaborate_id", $collaborateId)->whereIn('profile_id', $profileIds)
            ->where('hut', 1)->get();

        if (count($applicantDetails) == 0)
            return $this->sendError("No User exists.");

        $headers = array(
            "Content-type" => "application/vnd.ms-excel; charset=utf-8",
            "Content-Disposition" => "attachment; filename=" . $collaborateId . "_HUT_USER_ADDRESS_LIST.xls",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        $batchInfo = \DB::table('collaborate_batches')->where('id', $batchId)->first();
        $profiles = [];
        $index = 1;
        foreach ($applicantDetails as $applicantDetail) {
            $applierAddress = "";
            $applierAddress = isset($applicantDetail->applier_address['house_no']) && !is_null($applicantDetail->applier_address['house_no']) ? $applierAddress . $applicantDetail->applier_address['house_no'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['landmark']) && !is_null($applicantDetail->applier_address['landmark']) ? $applierAddress . $applicantDetail->applier_address['landmark'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['locality']) && !is_null($applicantDetail->applier_address['locality']) ? $applierAddress . $applicantDetail->applier_address['locality'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['city']) && !is_null($applicantDetail->applier_address['city']) ? $applierAddress . $applicantDetail->applier_address['city'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['state']) && !is_null($applicantDetail->applier_address['state']) ? $applierAddress . $applicantDetail->applier_address['state'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['country']) && !is_null($applicantDetail->applier_address['country']) ? $applierAddress . $applicantDetail->applier_address['country'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['pincode']) && !is_null($applicantDetail->applier_address['pincode']) ? $applierAddress . " - " . $applicantDetail->applier_address['pincode'] : $applierAddress;

            $profiles[] = [
                'S.No' => $index, 'Name' => $applicantDetail->profile->name, 'Profile Link' => "https://www.tagtaste.com/@" . $applicantDetail->profile->handle,
                'Delivery Address' => $applierAddress
            ];
            $index++;
        }
        $columns = array('S.No', 'Name', 'Profile Link', 'Delivery Address');
        Excel::create($collaborateId . "_HUT_USER_ADDRESS_LIST", function ($excel) use ($profiles, $columns, $batchInfo, $collaborateId) {

            // Set the title
            $excel->setTitle("HUT USER ADDRESS");

            // Chain the setters
            $excel->setCreator('TagTaste')
                ->setCompany('TagTaste');

            // Call them separately
            $excel->setDescription('Collaboration Applicant applier HUT Address');
            // Our first sheet
            $excel->sheet('First sheet', function ($sheet) use ($profiles, $columns, $batchInfo, $collaborateId) {
                $sheet->setOrientation('landscape');
                $sheet->row(1, array("Collaboration Link - " . "https://www.tagtaste.com/collaborate/" . $collaborateId));
                $sheet->row(2, array("Product Name - " . $batchInfo->name));
                $sheet->row(3, $columns);
                $index = 4;
                foreach ($profiles as $key => $value) {
                    $sheet->appendRow($index, $value);
                    $index++;
                }
                $sheet->appendRow("");
            });
        })->store('xls');
        $filePath = storage_path("exports/" . $collaborateId . "_HUT_USER_ADDRESS_LIST.xls");

        return response()->download($filePath, $collaborateId . "_HUT_USER_ADDRESS_LIST.xls", $headers);
    }

    public function allHutCsv(Request $request, $collaborateId)
    {
        $collaborate = Collaborate::where('id', $collaborateId)->where('state', '!=', Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;

        if (isset($collaborate->company_id) && (!is_null($collaborate->company_id))) {
            $checkUser = CompanyUser::where('company_id', $collaborate->company_id)->where('profile_id', $profileId)->exists();
            if (!$checkUser) {
                return $this->sendError("Invalid Collaboration Project.");
            }
        } else if ($collaborate->profile_id != $profileId) {
            return $this->sendError("Invalid Collaboration Project.");
        }

        $this->model = [];
        $applicantDetails = Collaborate\Applicant::where("collaborate_id", $collaborateId)->where('hut', 1)->get();

        if (count($applicantDetails) == 0)
            return $this->sendError("No User exists.");

        $headers = array(
            "Content-type" => "application/vnd.ms-excel; charset=utf-8",
            "Content-Disposition" => "attachment; filename=" . $collaborateId . "_HUT_USER_ADDRESS_LIST.xls",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        $profiles = [];
        $index = 1;
        foreach ($applicantDetails as $applicantDetail) {
            $applierAddress = "";
            $applierAddress = isset($applicantDetail->applier_address['house_no']) && !is_null($applicantDetail->applier_address['house_no']) ? $applierAddress . $applicantDetail->applier_address['house_no'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['landmark']) && !is_null($applicantDetail->applier_address['landmark']) ? $applierAddress . $applicantDetail->applier_address['landmark'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['locality']) && !is_null($applicantDetail->applier_address['locality']) ? $applierAddress . $applicantDetail->applier_address['locality'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['city']) && !is_null($applicantDetail->applier_address['city']) ? $applierAddress . $applicantDetail->applier_address['city'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['state']) && !is_null($applicantDetail->applier_address['state']) ? $applierAddress . $applicantDetail->applier_address['state'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['country']) && !is_null($applicantDetail->applier_address['country']) ? $applierAddress . $applicantDetail->applier_address['country'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['pincode']) && !is_null($applicantDetail->applier_address['pincode']) ? $applierAddress . " - " . $applicantDetail->applier_address['pincode'] : $applierAddress;

            $batchIds = \DB::table('collaborate_batches_assign')->where('profile_id', $applicantDetail->profile->id)->get()->pluck('batch_id');
            $batches = \DB::table('collaborate_batches')->whereIn('id', $batchIds)->get();
            $batchList = "";
            foreach ($batches as $batch) {
                $batchList = $batch->name . "," . $batchList;
            }
            $profiles[] = [
                'S.No' => $index, 'Name' => $applicantDetail->profile->name, 'Profile Link' => "https://www.tagtaste.com/@" . $applicantDetail->profile->handle,
                'Delivery Address' => $applierAddress, 'product Name' => $batchList
            ];
            $index++;
        }
        $columns = array('S.No', 'Name', 'Profile Link', 'Delivery Address', 'product Name');
        Excel::create($collaborateId . "_HUT_USER_ADDRESS_LIST", function ($excel) use ($profiles, $columns, $collaborateId) {

            // Set the title
            $excel->setTitle("HUT USER ADDRESS");

            // Chain the setters
            $excel->setCreator('TagTaste')
                ->setCompany('TagTaste');

            // Call them separately
            $excel->setDescription('Collaboration Applicant applier HUT Address');
            // Our first sheet
            $excel->sheet('First sheet', function ($sheet) use ($profiles, $columns, $collaborateId) {
                $sheet->setOrientation('landscape');
                $sheet->row(1, array("Collaboration Link - " . "https://www.tagtaste.com/collaborate/" . $collaborateId));
                $sheet->row(3, $columns);
                $index = 4;
                foreach ($profiles as $key => $value) {
                    $sheet->appendRow($index, $value);
                    $index++;
                }
                $sheet->appendRow("");
            });
        })->store('xls');
        $filePath = storage_path("exports/" . $collaborateId . "_HUT_USER_ADDRESS_LIST.xls");

        return response()->download($filePath, $collaborateId . "_HUT_USER_ADDRESS_LIST.xls", $headers);
    }

    public function reportSummary(Request $request, $collaborateId)
    {
        //filters data
        $filters = $request->input('filters');
        $resp = $this->getFilterProfileIds($filters, $collaborateId);
        $profileIds = $resp['profile_id'];
        $type = $resp['type'];
        $boolean = 'and';
        $questionIds = Collaborate\Questions::select('id')->where('collaborate_id', $collaborateId)->where('questions->select_type', 5)->get()->pluck('id');
        $overAllPreferences = \DB::table('collaborate_tasting_user_review')->select('tasting_header_id', 'question_id', 'leaf_id', 'batch_id', 'value', \DB::raw('count(*) as total'))->where('current_status', 3)
            ->where('collaborate_id', $collaborateId)->whereIn('profile_id', $profileIds, $boolean, $type)->whereIn('question_id', $questionIds)
            ->orderBy('tasting_header_id', 'ASC')->orderBy('batch_id', 'ASC')->orderBy('leaf_id', 'ASC')->groupBy('tasting_header_id', 'question_id', 'leaf_id', 'value', 'batch_id')->get();

        $batches = \DB::table('collaborate_batches')->where('collaborate_id', $collaborateId)->orderBy('id')->get();

        $model = [];
        $headers = Collaborate\ReviewHeader::where('collaborate_id', $collaborateId)->get();
        foreach ($headers as $header) {
            $data = [];
            if ($header->header_type == 'INSTRUCTIONS')
                continue;
            $data['header_type'] = $header->header_type;
            $data['id'] = $header->id;
            foreach ($batches as $batch) {
                $item  = [];
                $item['batch_info'] = $batch;
                $totalValue = 0;
                $totalReview = 0;
                foreach ($overAllPreferences as $overAllPreference) {

                    if ($header->id == $overAllPreference->tasting_header_id && $batch->id == $overAllPreference->batch_id) {
                        $totalReview = $totalReview + $overAllPreference->total;
                        $totalValue = $totalValue + $overAllPreference->leaf_id * $overAllPreference->total;
                    }
                }
                if ($totalValue && $totalReview)
                    $item['overAllPreference'] = number_format((float)($totalValue / $totalReview), 2, '.', '');
                else
                    $item['overAllPreference'] = "0.00";

                $data['batches'][] = $item;
            }
            $model[] = $data;
        }
        $this->model = $model;
        return $this->sendResponse();
    }

    public function getPRProfile(Request $request, $collaborateId, $batchId)
    {
        $excludeProfileIds = $request->input('profile_id');
        if (count($excludeProfileIds) > 0)
            $profileIds = \DB::table('collaborate_batches_assign')->whereNotIn('profile_id', $excludeProfileIds)->where('collaborate_id', $collaborateId)
                ->where('batch_id', $batchId)->get()->pluck('profile_id');
        else
            $profileIds = \DB::table('collaborate_batches_assign')->where('collaborate_id', $collaborateId)
                ->where('batch_id', $batchId)->get()->pluck('profile_id');
        $query = $request->input('term');
        $profileIds = \App\Recipe\Profile::select('profiles.id')->join('users', 'profiles.user_id', '=', 'users.id')
            ->whereIn('profiles.id', $profileIds)->where('users.name', 'like', "%$query%")
            ->get()->pluck('id');

        $this->model = Profile::whereIn('id', $profileIds)->get();
        return $this->sendResponse();
    }

    public function reportPdf(Request $request, $collaborateId, $batchId)
    {
        $collaborate = Collaborate::where('id', $collaborateId)->where('state', '!=', Collaborate::$state[1])->first();
        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        
        $batchData = $this->model->where('id', $batchId)->where('collaborate_id', $collaborateId)->first();
        if ($batchData === null) {
            return $this->sendError("Invalid batch.");
        }

        // if(isset($collaborate->company_id)&& (!is_null($collaborate->company_id)))
        // {
        //     $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$profileId)->exists();
        //     if(!$checkUser){
        //         return $this->sendError("Invalid Collaboration Project.");
        //     }
        // }
        // else if($collaborate->profile_id != $profileId){
        //     return $this->sendError("Invalid Collaboration Project.");
        // }

        $headers = Collaborate\ReviewHeader::where('collaborate_id', $collaborateId)->get();

        $this->model = [];
        //filters data
        $filters = $request->input('filters');
        $resp = $this->getFilterProfileIds($filters, $collaborateId);
        $profileIds = $resp['profile_id'];
        $type = $resp['type'];
        $boolean = 'and';
        foreach ($headers as $header) {
            if ($header->header_type == 'INSTRUCTIONS' || $header->header_selection_type == 3)
                continue;
            $headerId = $header->id;
            $withoutNest = \DB::table('collaborate_tasting_questions')->where('collaborate_id', $collaborateId)
                ->whereNull('parent_question_id')->where('header_type_id', $headerId)->orderBy('id')->get();
            $withNested = \DB::table('collaborate_tasting_questions')->where('collaborate_id', $collaborateId)
                ->whereNotNull('parent_question_id')->where('header_type_id', $headerId)->orderBy('id')->get();

            foreach ($withoutNest as &$data) {
                if (isset($data->questions) && !is_null($data->questions)) {
                    $data->questions = json_decode($data->questions);
                }
            }
            foreach ($withoutNest as &$data) {
                $i = 0;
                foreach ($withNested as $item) {
                    if ($item->parent_question_id == $data->id) {
                        $item->questions = json_decode($item->questions);
                        $item->questions->id = $item->id;
                        $item->questions->is_nested_question = $item->is_nested_question;
                        $item->questions->is_mandatory = $item->is_mandatory;
                        $item->questions->is_active = $item->is_active;
                        $item->questions->parent_question_id = $item->parent_question_id;
                        $item->questions->header_type_id = $item->header_type_id;
                        $item->questions->collaborate_id = $item->collaborate_id;
                        $data->questions->questions{
                            $i} = $item->questions;
                        $i++;
                    }
                }
            }

            $totalApplicants = \DB::table('collaborate_tasting_user_review')->where('value', '!=', '')->where('current_status', 3)->where('collaborate_id', $collaborateId)
                ->whereIn('profile_id', $profileIds, $boolean, $type)->where('batch_id', $batchId)->distinct()->get(['profile_id'])->count();
            $model = [];
            foreach ($withoutNest as $data) {
                $reports = [];
                if (isset($data->questions) && !is_null($data->questions) && $data->questions->select_type != 6) {
                    $reports['question_id'] = $data->id;
                    $reports['title'] = $data->title;
                    $reports['subtitle'] = $data->subtitle;
                    $reports['is_nested_question'] = $data->is_nested_question;
                    $reports['question'] = $data->questions;
                    if (isset($data->questions->is_nested_question) && $data->questions->is_nested_question == 1) {
                        $subAnswers = [];
                        foreach ($data->questions->questions as $item) {
                            $subReports = [];
                            $subReports['question_id'] = $item->id;
                            $subReports['title'] = $item->title;
                            $subReports['subtitle'] = isset($item->subtitle) ? $item->subtitle : null;
                            $subReports['is_nested_question'] = $item->is_nested_question;
                            $subReports['total_applicants'] = $totalApplicants;
                            $subReports['total_answers'] = \DB::table('collaborate_tasting_user_review')->where('current_status', 3)->where('collaborate_id', $collaborateId)
                                ->whereIn('profile_id', $profileIds, $boolean, $type)->where('batch_id', $batchId)->where('question_id', $item->id)->distinct()->get(['profile_id'])->count();
                            $answers = \DB::table('collaborate_tasting_user_review')->select('leaf_id', 'value', \DB::raw('count(*) as total'))->selectRaw("GROUP_CONCAT(intensity) as intensity")
                                ->where('current_status', 3)->whereIn('profile_id', $profileIds, $boolean, $type)->where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->where('question_id', $item->id)
                                ->orderBy('question_id', 'ASC')->orderBy('total', 'DESC')->groupBy('question_id', 'value', 'leaf_id')->get();
                            $options = isset($item->option) ? $item->option : [];
                            foreach ($answers as &$answer) {
                                $value = [];
                                foreach ($options as $option) {
                                    if ($option->id == $answer->leaf_id) {
                                        if ($option->is_intensity == 1 && $option->intensity_type == 2) {
                                            $answerIntensity = $answer->intensity;
                                            $answerIntensity = explode(",", $answerIntensity);
                                            $questionIntensity = $option->intensity_value;
                                            $questionIntensity = explode(",", $questionIntensity);
                                            foreach ($questionIntensity as $x) {
                                                $count = 0;
                                                foreach ($answerIntensity as $y) {
                                                    if ($this->checkValue($x, $y))
                                                        $count++;
                                                }
                                                $value[] = ['value' => $x, 'count' => $count];
                                            }
                                        } else if ($option->is_intensity == 1 && $option->intensity_type == 1) {
                                            $answerIntensity = $answer->intensity;
                                            $answerIntensity = explode(",", $answerIntensity);
                                            $questionIntensityValue = $option->intensity_value;
                                            $questionIntensity = [];
                                            for ($i = 1; $i <= $questionIntensityValue; $i++) {
                                                $questionIntensity[] = $i;
                                            }
                                            foreach ($questionIntensity as $x) {
                                                $count = 0;
                                                foreach ($answerIntensity as $y) {
                                                    if ($y == $x)
                                                        $count++;
                                                }
                                                $value[] = ['value' => $x, 'count' => $count];
                                            }
                                        }
                                        $answer->is_intensity = isset($option->is_intensity) ? $option->is_intensity : null;
                                        $answer->intensity_value = isset($option->intensity_value) ? $option->intensity_value : null;
                                        $answer->intensity_type = isset($option->intensity_type) ? $option->intensity_type : null;
                                    }
                                }
                                $answer->intensity = $value;
                            }
                            $subReports['answer'] = $answers;
                            $subAnswers[] = $subReports;
                        }
                        $reports['nestedAnswers'] = $subAnswers;
                    } else
                        unset($reports['nestedAnswers']);
                    $reports['total_applicants'] = $totalApplicants;
                    $reports['total_answers'] = \DB::table('collaborate_tasting_user_review')->where('current_status', 3)->where('collaborate_id', $collaborateId)
                        ->whereIn('profile_id', $profileIds, $boolean, $type)->where('batch_id', $batchId)->where('question_id', $data->id)->distinct()->get(['profile_id'])->count();
                    if (isset($data->questions->select_type) && $data->questions->select_type == 3) {
                        $reports['answer'] = Collaborate\Review::where('collaborate_id', $collaborateId)->where('batch_id', $batchId)->where('question_id', $data->id)
                            ->whereIn('profile_id', $profileIds, $boolean, $type)->where('current_status', 3)->where('tasting_header_id', $headerId)->skip(0)->take(3)->get();
                    } else {
                        $answers = \DB::table('collaborate_tasting_user_review')->select('leaf_id', 'collaborate_tasting_user_review.value', \DB::raw('count(*) as total'), 'collaborate_tasting_user_review.option_type')->selectRaw("GROUP_CONCAT(collaborate_tasting_user_review.intensity) as intensity,collaborate_tasting_nested_options.is_intensity as is_intensity")
                            ->leftJoin("collaborate_tasting_nested_options", "leaf_id", "=", "collaborate_tasting_nested_options.id")
                            ->where('current_status', 3)
                            ->where('collaborate_tasting_user_review.collaborate_id', $collaborateId)->where('collaborate_tasting_user_review.batch_id', $batchId)->where('collaborate_tasting_user_review.question_id', $data->id)
                            ->whereIn('profile_id', $profileIds, $boolean, $type)->orderBy('collaborate_tasting_user_review.question_id', 'ASC')->orderBy('total', 'DESC')->groupBy('collaborate_tasting_user_review.question_id', 'collaborate_tasting_user_review.value', 'leaf_id', 'collaborate_tasting_user_review.option_type')->get();

                        $options = isset($data->questions->option) ? $data->questions->option : [];
                        foreach ($answers as &$answer) {
                            if ($answer->option_type == 1 && strtoupper($answer->value) != 'ANY OTHER')
                                $answer->value = "Any Other - " . $answer->value;
                            $value = [];
                            if (isset($data->questions->is_nested_option) && $data->questions->is_nested_option == 1 && isset($data->questions->intensity_value) && isset($answer->intensity)) {
                                if ($data->questions->intensity_type == 2) {
                                    $answerIntensity = $answer->intensity;
                                    $answerIntensity = explode(",", $answerIntensity);
                                    $questionIntensity = $data->questions->intensity_value;
                                    $questionIntensity = explode(",", $questionIntensity);
                                    foreach ($questionIntensity as $x) {

                                        $count = 0;
                                        foreach ($answerIntensity as $y) {
                                            if ($this->checkValue($x, $y))
                                                $count++;
                                        }
                                        $value[] = ['value' => $x, 'count' => $count];
                                    }
                                } else if ($data->questions->intensity_type == 1) {
                                    $answerIntensity = $answer->intensity;
                                    $answerIntensity = explode(",", $answerIntensity);
                                    $questionIntensityValue = $data->questions->intensity_value;
                                    $questionIntensity = [];
                                    if (isset($data->questions->initial_intensity)) {
                                        $temp = $data->questions->initial_intensity;
                                    } else {
                                        $temp = 1;
                                    }
                                    for ($i = $temp; $i < (int)$questionIntensityValue + $temp; $i++) {
                                        $questionIntensity[] = $i;
                                    }
                                    foreach ($questionIntensity as $x) {
                                        $count = 0;
                                        foreach ($answerIntensity as $y) {
                                            if ($y == $x)
                                                $count++;
                                        }
                                        $value[] = ['value' => $x, 'count' => $count];
                                    }
                                }
                                $answer->initial_intensity = isset($data->questions->initial_intensity) ? $data->questions->initial_intensity : null;
                                $answer->is_intensity = isset($option->is_intensity) ? $option->is_intensity : null;
                                $answer->intensity_value = isset($option->intensity_value) ? $option->intensity_value : null;
                                $answer->intensity_type = $data->questions->intensity_type;
                            } else {
                                foreach ($options as $option) {
                                    if ($option->id == $answer->leaf_id) {
                                        if ($option->is_intensity == 1 && $data->questions->select_type != 5 && $option->intensity_type == 2) {

                                            $answerIntensity = $answer->intensity;
                                            $answerIntensity = explode(",", $answerIntensity);
                                            $questionIntensity = $option->intensity_value;
                                            $questionIntensity = explode(",", $questionIntensity);
                                            foreach ($questionIntensity as $x) {
                                                $count = 0;
                                                foreach ($answerIntensity as $y) {
                                                    if ($this->checkValue($x, $y))
                                                        $count++;
                                                }
                                                $value[] = ['value' => $x, 'count' => $count];
                                            }
                                        } else if ($option->is_intensity == 1 && $data->questions->select_type != 5 && $option->intensity_type == 1) {
                                            $answerIntensity = $answer->intensity;
                                            $answerIntensity = explode(",", $answerIntensity);
                                            $questionIntensityValue = $option->intensity_value;
                                            $questionIntensity = [];
                                            if (isset($data->questions->initial_intensity)) {
                                                $temp = $data->questions->initial_intensity;
                                            } else {
                                                $temp = 1;
                                            }
                                            for ($i = $temp; $i < (int)$questionIntensityValue + $temp; $i++) {
                                                $questionIntensity[] = $i;
                                            }
                                            foreach ($questionIntensity as $x) {
                                                $count = 0;
                                                foreach ($answerIntensity as $y) {
                                                    if ($y == $x)
                                                        $count++;
                                                }
                                                $value[] = ['value' => $x, 'count' => $count];
                                            }
                                        }
                                        $answer->initial_intensity = isset($option->initial_intensity) ? $option->initial_intensity : null;
                                        $answer->is_intensity = isset($option->is_intensity) ? $option->is_intensity : null;
                                        $answer->intensity_value = isset($option->intensity_value) ? $option->intensity_value : null;
                                        $answer->intensity_type = isset($option->intensity_type) ? $option->intensity_type : null;
                                    }
                                }
                            }
                            $answer->intensity = $value;
                        }
                        $reports['answer'] = $answers;
                    }

                    if (isset($data->questions->is_nested_option)) {
                        $reports['is_nested_option'] = $data->questions->is_nested_option;
                        if ($data->questions->is_nested_option == 1) {
                            foreach ($reports['answer'] as &$item) {
                                $nestedOption = \DB::table('collaborate_tasting_nested_options')->where('header_type_id', $headerId)
                                    ->where('question_id', $data->id)->where('id', $item->leaf_id)->where('value', 'like', $item->value)->first();
                                $item->path = isset($nestedOption->path) ? $nestedOption->path : null;
                            }
                        }
                    }

                    $model[] = $reports;
                }
            }
            $this->model[] = ['headerName' => $header->header_type, 'data' => $model];
        }
        $data = $this->model;
        $pdf = PDF::loadView('collaborates.reports', [
            'data' => $data,
            'filters' => $filters,
            'collaborate' => $collaborate,
            'batchData' => $batchData
        ]);
        $pdf = $pdf->output();
        $relativePath = "images/collaboratePdf/$collaborateId/collaborate";
        $name = "collaborate-" . $collaborateId . "-batch-" . $batchId . ".pdf";
        file_put_contents($name, $pdf);
        $s3 = \Storage::disk('s3');
        $resp = $s3->putFile($relativePath, new File($name), ['visibility' => 'public']);
        $this->model = \Storage::url($resp);
        return $this->sendResponse();
    }

    private function checkValue($a, $b)
    {
        if ($a == $b || $a == " " . $b || " " . $a == $b)
            return 1;
        return 0;
    }

    public function getHeaderWeight(Request $request, $collaborateId)
    {
        // $profileId = $request->user()->profile->id;
        // $collaborate = Collaborate::where('id', $collaborateId)->where('state', '!=', Collaborate::$state[1])->first();

        // if ($collaborate === null) {
        //     return $this->sendError("Invalid Collaboration Project.");
        // }

        // if(isset($collaborate->company_id)&& (!is_null($collaborate->company_id)))
        // {
        //     $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$profileId)->exists();
        //     if(!$checkUser){
        //         return $this->sendError("Invalid Collaboration Project.");
        //     }
        // }
        // else if($collaborate->profile_id != $profileId){
        //     return $this->sendError("Invalid Collaboration Project.");
        // }

        $filters = $request->input('filters');
        $resp = $this->getFilterProfileIds($filters, $collaborateId);
        $profileIds = $resp['profile_id'];
        $type = $resp['type'];
        $boolean = 'and';
        $questionIds = Collaborate\Questions::select('id')->where('collaborate_id', $collaborateId)->where('questions->select_type', 5)->get()->pluck('id');
        $overAllPreferences = \DB::table('collaborate_tasting_user_review')->select('tasting_header_id', 'question_id', 'leaf_id', 'batch_id', 'value', \DB::raw('count(*) as total'))->where('current_status', 3)
            ->where('collaborate_id', $collaborateId)->whereIn('profile_id', $profileIds, $boolean, $type)->whereIn('question_id', $questionIds)
            ->orderBy('tasting_header_id', 'ASC')->orderBy('batch_id', 'ASC')->orderBy('leaf_id', 'ASC')->groupBy('tasting_header_id', 'question_id', 'leaf_id', 'value', 'batch_id')->get();
    
        $batches = Collaborate\Batches::where('collaborate_id', $collaborateId)->orderBy('id')->get();

        $model = [];
        $headers = Collaborate\ReviewHeader::where('collaborate_id', $collaborateId)->whereNotIn('header_selection_type', [0, 3])->get();
        
        foreach ($headers as $header) {
            $data = [];
            if ($header->header_type == 'INSTRUCTIONS')
                continue;
            $data['header_type'] = $header->header_type;
            $data['id'] = $header->id;
            
            foreach ($batches as $batch) {
                $item  = [];
                $item['batch_info'] = $batch;
                $totalValue = 0;
                $totalReview = 0;
                foreach ($overAllPreferences as $overAllPreference) {
                    if ($header->id == $overAllPreference->tasting_header_id && $batch->id == $overAllPreference->batch_id) {
                        $totalReview = $totalReview + $overAllPreference->total;
                        $totalValue = $totalValue + $overAllPreference->leaf_id * $overAllPreference->total;
                    }
                }
                if ($totalValue && $totalReview)
                    $item['overAllPreference'] = number_format((float)($totalValue / $totalReview), 2, '.', '');
                else
                    $item['overAllPreference'] = "0.00";
                $item['reviewedCount'] = $totalReview;
                $data['batches'][] = $item;
            }
            $model[] = $data;
        }
        $weight = \DB::table('collaborate_report_weight_assign')->where('collaborate_id', $collaborateId)->first();
        if (isset($weight) && !is_null($weight) && isset($weight->header_weight))
            $weight->header_weight = json_decode($weight->header_weight, true);
        else
            $weight = null;
        $this->model = ['summary' => $model, 'weight' => $weight];
        return $this->sendResponse();
    }

    public function storeHeaderWeight(Request $request, $collaborateId)
    {
        $collaborate = Collaborate::where('id', $collaborateId)->where('state', '!=', Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;

        // if(isset($collaborate->company_id)&& (!is_null($collaborate->company_id)))
        // {
        //     $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$profileId)->exists();
        //     if(!$checkUser){
        //         return $this->sendError("Invalid Collaboration Project.");
        //     }
        // }
        // else if($collaborate->profile_id != $profileId){
        //     return $this->sendError("Invalid Collaboration Project.");
        // }
        $time = Carbon::now()->toDateTimeString();
        $checkBatch = \DB::table('collaborate_report_weight_assign')->where('collaborate_id', $collaborateId)->first();
        if (is_null($checkBatch)) {
            if (!$request->has('header_weight')) {
                $this->model = [];
                return $this->sendError('Please enter correct header wise weight');
            }
            \DB::table('collaborate_report_weight_assign')->insert([
                'collaborate_id' => $collaborateId, 'profile_id' => $request->user()->profile->id,
                'header_weight' => $request->input('header_weight'), 'created_at' => $time, 'updated_at' => $time
            ]);
        } else {
            \DB::table('collaborate_report_weight_assign')->where('collaborate_id', $collaborateId)->update([
                'profile_id' => $request->user()->profile->id,
                'header_weight' => $request->input('header_weight'), 'updated_at' => $time
            ]);
        }
        $this->model = \DB::table('collaborate_report_weight_assign')->where('collaborate_id', $collaborateId)->first();
        $this->model->header_weight = json_decode($this->model->header_weight, true);
        return $this->sendResponse();
    }

    protected function getRatingMeta($userCount, $headerRatingSum, $question)
    {
        $meta = [];
        $question = json_decode($question->questions);
        $option = isset($question->option) ? $question->option : [];
        $meta['max_rating'] = count($option);
        $meta['overall_rating'] = $userCount > 0 ? $headerRatingSum / $userCount : 0.00;
        $meta['count'] = $userCount;
        $meta['color_code'] = $this->getColorCode(floor($meta['overall_rating']));
        return $meta;
    }

    protected function getColorCode($value)
    {
        if ($value == 0 || is_null($value))
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

    public function optionReports(Request $request, $collaborateId, $id, $headerId, $questionId)
    {
        $collaborate = Collaborate::where('id', $collaborateId)->where('state', '!=', Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;

        // if(isset($collaborate->company_id)&& (!is_null($collaborate->company_id)))
        // {
        //     $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$profileId)->exists();
        //     if(!$checkUser){
        //         return $this->sendError("Invalid Collaboration Project.");
        //     }
        // }
        // else if($collaborate->profile_id != $profileId){
        //     return $this->sendError("Invalid Collaboration Project.");
        // }
        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);

        //filters data
        $filters = $request->input('filters');
        $resp = $this->getFilterProfileIds($filters, $collaborateId);
        $profileIds = $resp['profile_id'];
        $type = $resp['type'];
        $boolean = 'and';
        
        $this->model = \DB::table('collaborate_tasting_user_review')
            ->select('value', 'intensity')
            ->where('batch_id', $id)
            ->where('collaborate_id', $collaborateId)
            ->where('question_id', $questionId)
            ->where('option_type', 1)
            ->where('current_status', 3)
            ->whereIn('profile_id', $profileIds, $boolean, $type);
        //->groupBy('intensity','value');
        $data["values"] = $this->model
            ->skip($skip)
            ->take($take)
            ->get();
        $data["count"] = $data["values"]->count();
        $this->model = $data;
        return $this->sendResponse();
    }

    public function optionIdReports(Request $request, $collaborateId, $id, $headerId, $questionId, $optionId)
    {
        $collaborate = Collaborate::where('id', $collaborateId)->where('state', '!=', Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;
        
        //filters data
        $filters = $request->input('filters');
        $resp = $this->getFilterProfileIds($filters, $collaborateId);
        $profileIds = $resp['profile_id'];
        $type = $resp['type'];
        $boolean = 'and';

        $page = $request->input('page');
        list($skip, $take) = \App\Strategies\Paginator::paginate($page);
        $this->model = \DB::table('collaborate_tasting_user_review')
            ->select('value', 'intensity')
            ->where('batch_id', $id)
            ->where('collaborate_id', $collaborateId)
            ->where('question_id', $questionId)
            ->where('option_type', 1)
            ->where('current_status', 3)
            ->where('leaf_id', $optionId)
            ->whereIn('profile_id', $profileIds, $boolean, $type);
        //->groupBy('intensity','value');
        $data["values"] = $this->model
            ->skip($skip)
            ->take($take)
            ->get();
        $data["count"] = $data["values"]->count();
        $this->model = $data;
        return $this->sendResponse();
    }


    //status = 0 batchassigned
    //status = 1 foodBill shot submitted
    //status = 2 accepted
    //status = 3 rejected
    public function foodBillStatus(Request $request, $collaborateId, $batchId)
    {
        $status = $request->status;
        $profileId = $request->profile_id;
        if (!isset($profileId) || !isset($status)) {
            return $this->sendError('Invalid input given');
        }
        $foodBill = \DB::table('collaborate_batches_assign')
            ->where('collaborate_id', $collaborateId)
            ->where('batch_id', $batchId)
            ->where('profile_id', $profileId)
            ->whereNotNull('bill_verified');

        if (!$foodBill->exists()) {
            return $this->sendError('Food bill doesnt exists for given Id');
        }
        $this->model = $foodBill->update(['bill_verified' => $status]);
        return $this->sendResponse();
    }

    public function rollbackTaster(Request $request, $collaborateId)
    {
        $collaborate = Collaborate::where('id', $collaborateId)->where('state', '!=', Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $batchId = $request->input('batch_id');
        $profileIds = $request->input('profile_id');
        $err = true;
        foreach ($profileIds as $profileId) {
            $currentStatus = Redis::get("current_status:batch:$batchId:profile:$profileId");
            if ($currentStatus == 1 || $currentStatus == 0) {
                //perform operation
                Redis::set("current_status:batch:$batchId:profile:$profileId", 0); //update taster rollback redis
                $t = \DB::table('collaborate_batches_assign')->where('batch_id', $batchId)->where('profile_id', $profileId)->update(['begin_tasting' => 0]);
                $err = false;
                if ($t) {
                    $this->model = true;
                } else {
                    $err = true;
                }
                $who = null;


                $company = Company::where('id', $collaborate->company_id)->first();
                if (empty($company)) {
                    $who = Profile::where("id", "=", $collaborate->profile_id)->first();
                }
                $collaborate->profile_id = $profileId;
                event(new \App\Events\Actions\RollbackTaster($collaborate, $who, null, null, null, $company, $batchId));
            } else {
                $err = true;
            }
        }


        if ($err) {
            $this->model = false;
            return $this->sendError('Sorry, you cannot undo begin tasting as the tasting is in progress');
        }
        return $this->sendResponse();
    }
}
