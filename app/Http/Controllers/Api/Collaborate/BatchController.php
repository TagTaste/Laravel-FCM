<?php namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate;
use App\CompanyUser;
use App\Recipe\Company;
use App\Recipe\Profile;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Collection;
use Excel;

class BatchController extends Controller
{

    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Collaborate\Batches $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($collaborateId)
    {
        $batches = $this->model->where('collaborate_id',$collaborateId)
            ->orderBy("created_at","desc")->get()->toArray();

        foreach ($batches as &$batch)
        {
            $batch['reviewedCount'] = \DB::table('collaborate_tasting_user_review')->where('current_status',3)->where('collaborate_id',$batch['collaborate_id'])
                ->where('batch_id',$batch['id'])->distinct()->get(['profile_id'])->count();

            $batch['assignedCount'] = \DB::table('collaborate_batches_assign')->where('batch_id',$batch['id'])->distinct()->get(['profile_id'])->count();

            $batch['beginTastingCount'] = \DB::table('collaborate_batches_assign')->where('begin_tasting',1)->where('batch_id',$batch['id'])->distinct()->get(['profile_id'])->count();
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
        $inputs = $request->except(['_method','_token']);
        $this->model = $this->model->create($inputs);

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
        //filters data
        $filters = $request->input('filters');
        $profileIds = $this->getFilterProfileIds($filters,$collaborateId, $id);
        $type = true;
        $boolean = 'and' ;
        if(isset($filters))
            $type = false;
        $profileIds = \DB::table('collaborate_batches_assign')->where('batch_id',$id)->whereIn('profile_id', $profileIds, $boolean, $type)->get()->pluck('profile_id');
        $profiles = Collaborate\Applicant::where('collaborate_id',$collaborateId)->whereIn('profile_id',$profileIds)->get();
        $profiles = $profiles->toArray();
        foreach ($profiles as &$profile)
        {
            $currentStatus = \Redis::get("current_status:batch:$id:profile:" . $profile['profile']['id']);
            $profile['current_status'] = !is_null($currentStatus) ? (int)$currentStatus : 0;
        }
        $this->model = [];
        $this->model['applicants'] = $profiles;
        $this->model['batch'] = Collaborate\Batches::where('id',$id)->first();
        return $this->sendResponse();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request,$collaborateId, $id)
    {
        $inputs = $request->except(['_method','_token']);
        $batches = $this->model->where('id',$id)->where('collaborate_id',$collaborateId)->first();

        if(!$batches)
        {
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
        $batches = $this->model->where('id',$id)->where('collaborate_id',$collaborateId)->first();
        $this->model = $batches->delete();
        return $this->sendResponse();
    }

    public function assignBatch(Request $request, $id)
    {
        $collaborate = Collaborate::where('id',$id)->where('state','!=',Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;

        if(isset($collaborate->company_id)&& (!is_null($collaborate->company_id)))
        {
            $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$profileId)->exists();
            if(!$checkUser){
                return $this->sendError("Invalid Collaboration Project.");
            }
        }
        else if($collaborate->profile_id != $profileId){
            return $this->sendError("Invalid Collaboration Project.");
        }

        $applierProfileIds = $request->input('profile_id');
        $checkUserShortlist = Collaborate\Applicant::where('collaborate_id',$id)->whereIn('profile_id',$applierProfileIds)->where('is_invited',1)->whereNull('shortlisted_at')->exists();
        if($checkUserShortlist)
        {
            return $this->sendError("User is not accepted invitations.");
        }
        $batchId = $request->input('batch_id');
        $checkBatch = \DB::table('collaborate_batches')->where('collaborate_id',$id)->where('id',$batchId)->exists();
        if(!$checkBatch)
        {
            return $this->sendError("wrong batch for this collaboration.");
        }
        $inputs = [];
        $now = Carbon::now()->toDateTimeString();
        \DB::table('collaborate_batches_assign')->where('collaborate_id',$id)->where('batch_id',$batchId)->whereIn('profile_id',$applierProfileIds)->delete();
        foreach ($applierProfileIds as $applierProfileId)
        {
            \Redis::sAdd("collaborate:$id:profile:$applierProfileId:" ,$batchId);
            \Redis::set("current_status:batch:$batchId:profile:$applierProfileId" ,0);
            $inputs[] = ['profile_id' => $applierProfileId,'batch_id'=>$batchId,'begin_tasting'=>0,'created_at'=>$now, 'collaborate_id'=>$id];
        }
        $this->model = \DB::table('collaborate_batches_assign')->insert($inputs);

        return $this->sendResponse();
    }

    public function removeFromBatch(Request $request, $collaborateId)
    {
        $profileIds = $request->input('profile_id');
        $batchId = $request->input('batch_id');
        $checkUserReview = \DB::table('collaborate_batches_assign')->where('begin_tasting',1)->where('batch_id',$batchId)->whereIn('profile_id',$profileIds)->exists();
        if($checkUserReview)
        {
            return $this->sendError("You can not remove from batch.");
        }
        foreach ($profileIds as $profileId)
        {
            \Redis::sRem("collaborate:$collaborateId:profile:$profileId:" ,$batchId);
        }
        $this->model = \DB::table('collaborate_batches_assign')->where('batch_id',$batchId)->whereIn('profile_id',$profileIds)->delete();

        return $this->sendResponse();

    }

    public function beginTasting(Request $request, $collaborateId)
    {
        $collaborate = Collaborate::where('id',$collaborateId)->where('state','!=',Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $batchId = $request->input('batch_id');
        $profileIds = $request->input('profile_id');
        if($request->has("begin_all"))
        {
            if($request->input("begin_all") == 1)
            {
                $profileIds = \DB::table('collaborate_batches_assign')->where('batch_id',$batchId)->where('collaborate_id',$collaborateId)->get()->pluck('profile_id');
            }
        }

        $this->model = \DB::table('collaborate_batches_assign')->where('batch_id',$batchId)->whereIn('profile_id',$profileIds)
            ->update(['begin_tasting'=>1]);
        if($this->model)
        {
            $company = Company::where('id',$collaborate->company_id)->first();
            foreach ($profileIds as $profileId)
            {
                $currentStatus = \Redis::get("current_status:batch:$batchId:profile:$profileId");
                if($currentStatus ==0)
                {
                    \Redis::set("current_status:batch:$batchId:profile:$profileId" ,1);
                }
                $collaborate->profile_id = $profileId;
                event(new \App\Events\Actions\BeginTasting($collaborate,null,null,null,null,$company,$batchId));
            }
        }
        return $this->sendResponse();

    }

    public function getShortlistedPeople(Request $request, $collaborateId, $batchId)
    {
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $applicants = Collaborate\Applicant::where('collaborate_id',$collaborateId)
            ->whereNotNull('shortlisted_at')->skip($skip)->take($take)->get()->toArray();

        foreach ($applicants as &$applicant)
        {
            $batches = Collaborate\BatchAssign::where('profile_id',$applicant['profile']['id'])->get()->pluck('batches');
            $applicant['batches'] = $batches;
        }
        $this->model = $applicants;
        return $this->sendResponse();

    }

    public function getShortlistedSearchPeople(Request $request, $collaborateId, $batchId)
    {
        $query = $request->input('term');

        $profileIds = \App\Recipe\Profile::select('profiles.id')
            ->join('users','profiles.user_id','=','users.id')->where('users.name','like',"%$query%")
            ->get()->pluck('id');
        $applicants = Collaborate\Applicant::where('collaborate_id',$collaborateId)->whereIn('profile_id',$profileIds)
            ->whereNotNull('shortlisted_at')->get()->toArray();

        foreach ($applicants as &$applicant)
        {
            $batches = Collaborate\BatchAssign::where('profile_id',$applicant['profile']['id'])->get()->pluck('batches');
            $applicant['batches'] = $batches;
        }
        $this->model = $applicants;
        return $this->sendResponse();

    }

    public function userBatches(Request $request, $collaborateId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $collaborate = \App\Recipe\Collaborate::where('id',$collaborateId)->first()->toArray();
        $batchIds = \DB::table('collaborate_batches_assign')->where('collaborate_id',$collaborateId)->where('profile_id',$loggedInProfileId)->where('begin_tasting',1)
            ->get()->pluck('batch_id');
        $count = count($batchIds);
        $batchIdArray = [];
        if($count) {
            foreach ($batchIds as &$batchId) {
                $batchIdArray[] = "batch:" . $batchId;
            }
            $batchInfos = \Redis::mGet($batchIdArray);
            $batches = [];
            foreach ($batchInfos as &$batchInfo) {
                $batchInfo = json_decode($batchInfo);
                $currentStatus = \Redis::get("current_status:batch:$batchInfo->id:profile:" . $loggedInProfileId);
                $batchInfo->current_status = !is_null($currentStatus) ? (int)$currentStatus : 0;
                if($batchInfo->current_status != 0)
                {
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
        $currentStatus = \Redis::get("current_status:batch:$batchId:profile:" . $loggedInProfileId);
        $this->model = !is_null($currentStatus) ? (int)$currentStatus : 0;
        return $this->sendResponse();

    }

    public function reports(Request $request, $collaborateId, $batchId, $headerId)
    {
        $collaborate = Collaborate::where('id',$collaborateId)->where('state','!=',Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;

        if(isset($collaborate->company_id)&& (!is_null($collaborate->company_id)))
        {
            $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$profileId)->exists();
            if(!$checkUser){
                return $this->sendError("Invalid Collaboration Project.");
            }
        }
        else if($collaborate->profile_id != $profileId){
            return $this->sendError("Invalid Collaboration Project.");
        }

        $withoutNest = \DB::table('collaborate_tasting_questions')->where('collaborate_id',$collaborateId)
            ->whereNull('parent_question_id')->where('header_type_id',$headerId)->orderBy('id')->get();
        $withNested = \DB::table('collaborate_tasting_questions')->where('collaborate_id',$collaborateId)
            ->whereNotNull('parent_question_id')->where('header_type_id',$headerId)->orderBy('id')->get();

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

        //filters data
        $filters = $request->input('filters');
        $profileIds = $this->getFilterProfileIds($filters,$collaborateId, $batchId);
        $type = true;
        $boolean = 'and' ;
        if(isset($filters))
            $type = false;
        $totalApplicants = \DB::table('collaborate_tasting_user_review')->where('value','!=','')->where('current_status',3)->where('collaborate_id',$collaborateId)
            ->whereIn('profile_id', $profileIds, $boolean, $type)->where('batch_id',$batchId)->distinct()->get(['profile_id'])->count();
        $model = [];
        foreach ($withoutNest as $data)
        {
            $reports = [];
            if(isset($data->questions)&&!is_null($data->questions))
            {
                $reports['question_id'] = $data->id;
                $reports['title'] = $data->title;
                $reports['subtitle'] = $data->subtitle;
                $reports['is_nested_question'] = $data->is_nested_question;
                $reports['question'] = $data->questions ;
                if($data->questions->is_nested_question == 1)
                {
                    $subAnswers = [];
                    foreach ($data->questions->questions as $item)
                    {
                        $subReports = [];
                        $subReports['question_id'] = $item->id;
                        $subReports['title'] = $item->title;
                        $subReports['subtitle'] = isset($item->subtitle) ? $item->subtitle : null;
                        $subReports['is_nested_question'] = $item->is_nested_question;
                        $subReports['total_applicants'] = $totalApplicants;
                        $subReports['total_answers'] = \DB::table('collaborate_tasting_user_review')->where('current_status',3)->where('collaborate_id',$collaborateId)
                            ->whereIn('profile_id', $profileIds, $boolean, $type)->where('batch_id',$batchId)->where('question_id',$item->id)->distinct()->get(['profile_id'])->count();
                        $subReports['answer'] = \DB::table('collaborate_tasting_user_review')->select('leaf_id','value','intensity',\DB::raw('count(*) as total'))->where('current_status',3)
                            ->whereIn('profile_id', $profileIds, $boolean, $type)->where('collaborate_id',$collaborateId)->where('batch_id',$batchId)->where('question_id',$item->id)
                            ->orderBy('question_id')->groupBy('question_id','value','leaf_id','intensity')->get();
                        $subAnswers[] = $subReports;
                    }
                    $reports['nestedAnswers'] = $subAnswers;

                }
                else
                    unset($reports['nestedAnswers']);
                $reports['total_applicants'] = $totalApplicants;
                $reports['total_answers'] = \DB::table('collaborate_tasting_user_review')->where('current_status',3)->where('collaborate_id',$collaborateId)
                    ->whereIn('profile_id', $profileIds, $boolean, $type)->where('batch_id',$batchId)->where('question_id',$data->id)->distinct()->get(['profile_id'])->count();
                if(isset($data->questions->select_type) && $data->questions->select_type == 3)
                {
                    $reports['answer'] = Collaborate\Review::where('collaborate_id',$collaborateId)->where('batch_id',$batchId)->where('question_id',$data->id)
                        ->whereIn('profile_id', $profileIds, $boolean, $type)->where('current_status',3)->where('tasting_header_id',$headerId)->skip(0)->take(3)->get();
                }
                else
                {
                    $reports['answer'] = \DB::table('collaborate_tasting_user_review')->select('leaf_id','value','intensity',\DB::raw('count(*) as total'))->where('current_status',3)
                        ->where('collaborate_id',$collaborateId)->where('batch_id',$batchId)->where('question_id',$data->id)
                        ->whereIn('profile_id', $profileIds, $boolean, $type)->orderBy('question_id')->groupBy('question_id','value','leaf_id','intensity')->get();
                }

                if(isset($data->questions->is_nested_option))
                {
                    $reports['is_nested_option'] = $data->questions->is_nested_option;
                    if($data->questions->is_nested_option == 1)
                    {
                        foreach($reports['answer'] as &$item)
                        {
                            $nestedOption = \DB::table('collaborate_tasting_nested_options')->where('header_type_id',$headerId)
                                ->where('question_id',$data->id)->where('id',$item->leaf_id)->where('value','like',$item->value)->first();
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

    public function filterReports($filters,$collaborateId, $batchId, $headerId,$withoutNest)
    {
        $profileIds = $this->getFilterProfileIds($filters,$collaborateId);
        $totalApplicants = \DB::table('collaborate_tasting_user_review')->where('value','!=','')->where('current_status',3)->where('collaborate_id',$collaborateId)
            ->where('batch_id',$batchId)->whereIn('profile_id',$profileIds)->distinct()->get(['profile_id'])->count();
        $model = [];
        foreach ($withoutNest as $data)
        {
            $reports = [];
            if(isset($data->questions)&&!is_null($data->questions))
            {
                $reports['question_id'] = $data->id;
                $reports['title'] = $data->title;
                $reports['subtitle'] = $data->subtitle;
                $reports['is_nested_question'] = $data->is_nested_question;
                $reports['question'] = $data->questions ;
                if($data->questions->is_nested_question == 1)
                {
                    $subAnswers = [];
                    foreach ($data->questions->questions as $item)
                    {
                        $subReports = [];
                        $subReports['question_id'] = $item->id;
                        $subReports['title'] = $item->title;
                        $subReports['subtitle'] = isset($item->subtitle) ? $item->subtitle : null;
                        $subReports['is_nested_question'] = $item->is_nested_question;
                        $subReports['total_applicants'] = $totalApplicants;
                        $subReports['total_answers'] = \DB::table('collaborate_tasting_user_review')->where('current_status',3)->where('collaborate_id',$collaborateId)
                            ->where('batch_id',$batchId)->where('question_id',$item->id)->whereIn('profile_id',$profileIds)->distinct()->get(['profile_id'])->count();
                        $subReports['answer'] = \DB::table('collaborate_tasting_user_review')->select('value','intensity',\DB::raw('count(*) as total'))->where('current_status',3)
                            ->where('collaborate_id',$collaborateId)->whereIn('profile_id',$profileIds)->where('batch_id',$batchId)->where('question_id',$item->id)
                            ->orderBy('question_id')->groupBy('question_id','value','leaf_id','intensity')->get();
                        $subAnswers[] = $subReports;
                    }
                    $reports['nestedAnswers'] = $subAnswers;
                }
                else
                    unset($reports['nestedAnswers']);
                $reports['total_applicants'] = $totalApplicants;
                $reports['total_answers'] = \DB::table('collaborate_tasting_user_review')->where('current_status',3)->where('collaborate_id',$collaborateId)
                    ->where('batch_id',$batchId)->whereIn('profile_id',$profileIds)->where('question_id',$data->id)->distinct()->get(['profile_id'])->count();
                if(isset($data->questions->select_type) && $data->questions->select_type == 3)
                {
                    $reports['answer'] = Collaborate\Review::where('collaborate_id',$collaborateId)->whereIn('profile_id',$profileIds)->where('batch_id',$batchId)->where('question_id',$data->id)
                        ->where('current_status',3)->where('tasting_header_id',$headerId)->skip(0)->take(3)->get();
                }
                else
                {
                    $reports['answer'] = \DB::table('collaborate_tasting_user_review')->select('leaf_id','value','intensity',\DB::raw('count(*) as total'))->where('current_status',3)
                        ->where('collaborate_id',$collaborateId)->whereIn('profile_id',$profileIds)->where('batch_id',$batchId)->where('question_id',$data->id)
                        ->orderBy('question_id')->groupBy('question_id','value','leaf_id','intensity')->get();
                }

                if(isset($data->questions->is_nested_option))
                {
                    $reports['is_nested_option'] = $data->questions->is_nested_option;
                    if($data->questions->is_nested_option == 1)
                    {
                        foreach($reports['answer'] as &$item)
                        {
                            $nestedOption = \DB::table('collaborate_tasting_nested_options')->where('header_type_id',$headerId)
                                ->where('question_id',$data->id)->where('id',$item->leaf_id)->where('value','like',$item->value)->first();
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
        $filters = $request->input('filter');

        $gender = ['Male','Female','Other'];
        $age = ['< 18','18 - 35','35 - 55','55 - 70','> 70'];
        $currentStatus = [0,1,2,3];
        $applicants = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->get();
        $city = [];
        foreach ($applicants as $applicant)
        {
            if(isset($applicant->city))
            {
                if(!in_array($applicant->city,$city))
                    $city[] = $applicant->city;
            }
        }
        $data = [];
        if(count($filters))
        {
            foreach ($filters as $filter)
            {
                if($filter == 'gender')
                    $data['gender'] = $gender;
                if($filter == 'age')
                    $data['age'] = $age;
                if($filter == 'city')
                    $data['city'] = $city;
                if($filter == 'current_status')
                    $data['current_status'] = $currentStatus;
            }
        }
        else
        {
            $data = ['gender'=>$gender,'age'=>$age,'city'=>$city,'current_status'=>$currentStatus];
        }
        $this->model = $data;

        return $this->sendResponse();

    }

    public function comments(Request $request, $collaborateId, $batchId, $headerId, $questionId)
    {
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model = Collaborate\Review::where('collaborate_id',$collaborateId)->where('question_id',$questionId)->where('batch_id',$batchId)
            ->where('tasting_header_id',$headerId)->where('current_status',3)->skip($skip)->take($take)->get();

        return $this->sendResponse();
    }

    public function hutCsv(Request $request, $collaborateId, $batchId)
    {
        $collaborate = Collaborate::where('id',$collaborateId)->where('state','!=',Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;

        if(isset($collaborate->company_id)&& (!is_null($collaborate->company_id)))
        {
            $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$profileId)->exists();
            if(!$checkUser){
                return $this->sendError("Invalid Collaboration Project.");
            }
        }
        else if($collaborate->profile_id != $profileId){
            return $this->sendError("Invalid Collaboration Project.");
        }

        $this->model = [];
        $profileIds = \DB::table('collaborate_batches_assign')->where('batch_id',$batchId)->get()->pluck('profile_id');

        $applicantDetails = Collaborate\Applicant::where("collaborate_id",$collaborateId)->whereIn('profile_id',$profileIds)
            ->where('hut',1)->get();

        if(count($applicantDetails) == 0)
            return $this->sendError("No User exists.");

        $headers = array(
            "Content-type" => "application/vnd.ms-excel; charset=utf-8",
            "Content-Disposition" => "attachment; filename=".$collaborateId."_HUT_USER_ADDRESS_LIST.xls",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        $batchInfo = \DB::table('collaborate_batches')->where('id',$batchId)->first();
        $profiles = [];
        $index = 1;
        foreach ($applicantDetails as $applicantDetail)
        {
            $applierAddress = "";
            $applierAddress = isset($applicantDetail->applier_address['house_no']) && !is_null($applicantDetail->applier_address['house_no']) ? $applierAddress.$applicantDetail->applier_address['house_no'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['landmark']) && !is_null($applicantDetail->applier_address['landmark']) ? $applierAddress.$applicantDetail->applier_address['landmark'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['locality']) && !is_null($applicantDetail->applier_address['locality']) ? $applierAddress.$applicantDetail->applier_address['locality'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['city']) && !is_null($applicantDetail->applier_address['city']) ? $applierAddress.$applicantDetail->applier_address['city'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['state']) && !is_null($applicantDetail->applier_address['state']) ? $applierAddress.$applicantDetail->applier_address['state'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['country']) && !is_null($applicantDetail->applier_address['country']) ? $applierAddress.$applicantDetail->applier_address['country'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['pincode']) && !is_null($applicantDetail->applier_address['pincode']) ? $applierAddress." - ".$applicantDetail->applier_address['pincode'] : $applierAddress;

            $profiles[] = ['S.No'=>$index,'Name'=>$applicantDetail->profile->name,'Profile Link'=>"https://www.tagtaste.com/@".$applicantDetail->profile->handle,
                'Delivery Address'=>$applierAddress];
            $index++;
        }
        $columns = array('S.No','Name','Profile Link','Delivery Address');
        Excel::create($collaborateId."_HUT_USER_ADDRESS_LIST", function($excel) use($profiles, $columns,$batchInfo,$collaborateId) {

            // Set the title
            $excel->setTitle("HUT USER ADDRESS");

            // Chain the setters
            $excel->setCreator('TagTaste')
                ->setCompany('TagTaste');

            // Call them separately
            $excel->setDescription('Collaboration Applicant applier HUT Address');
            // Our first sheet
            $excel->sheet('First sheet', function($sheet) use($profiles, $columns, $batchInfo,$collaborateId) {
                $sheet->setOrientation('landscape');
                $sheet->row(1,array("Collaboration Link - "."https://www.tagtaste.com/collaborate/".$collaborateId));
                $sheet->row(2,array("Product Name - ".$batchInfo->name));
                $sheet->row(3,$columns);
                $index = 4;
                foreach ($profiles as $key => $value) {
                    $sheet->appendRow($index, $value);
                    $index++;
                }
                $sheet->appendRow("");

            });

        })->store('xls');
        $filePath = storage_path("exports/".$collaborateId."_HUT_USER_ADDRESS_LIST.xls");

        return response()->download($filePath, $collaborateId."_HUT_USER_ADDRESS_LIST.xls", $headers);
    }

    public function allHutCsv(Request $request, $collaborateId)
    {
        $collaborate = Collaborate::where('id',$collaborateId)->where('state','!=',Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;

        if(isset($collaborate->company_id)&& (!is_null($collaborate->company_id)))
        {
            $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$profileId)->exists();
            if(!$checkUser){
                return $this->sendError("Invalid Collaboration Project.");
            }
        }
        else if($collaborate->profile_id != $profileId){
            return $this->sendError("Invalid Collaboration Project.");
        }

        $this->model = [];
        $applicantDetails = Collaborate\Applicant::where("collaborate_id",$collaborateId)->where('hut',1)->get();

        if(count($applicantDetails) == 0)
            return $this->sendError("No User exists.");

        $headers = array(
            "Content-type" => "application/vnd.ms-excel; charset=utf-8",
            "Content-Disposition" => "attachment; filename=".$collaborateId."_HUT_USER_ADDRESS_LIST.xls",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        $profiles = [];
        $index = 1;
        foreach ($applicantDetails as $applicantDetail)
        {
            $applierAddress = "";
            $applierAddress = isset($applicantDetail->applier_address['house_no']) && !is_null($applicantDetail->applier_address['house_no']) ? $applierAddress.$applicantDetail->applier_address['house_no'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['landmark']) && !is_null($applicantDetail->applier_address['landmark']) ? $applierAddress.$applicantDetail->applier_address['landmark'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['locality']) && !is_null($applicantDetail->applier_address['locality']) ? $applierAddress.$applicantDetail->applier_address['locality'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['city']) && !is_null($applicantDetail->applier_address['city']) ? $applierAddress.$applicantDetail->applier_address['city'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['state']) && !is_null($applicantDetail->applier_address['state']) ? $applierAddress.$applicantDetail->applier_address['state'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['country']) && !is_null($applicantDetail->applier_address['country']) ? $applierAddress.$applicantDetail->applier_address['country'] : $applierAddress;
            $applierAddress = isset($applicantDetail->applier_address['pincode']) && !is_null($applicantDetail->applier_address['pincode']) ? $applierAddress." - ".$applicantDetail->applier_address['pincode'] : $applierAddress;

            $batchIds = \DB::table('collaborate_batches_assign')->where('profile_id',$applicantDetail->profile->id)->get()->pluck('batch_id');
            $batches = \DB::table('collaborate_batches')->whereIn('id',$batchIds)->get();
            $batchList = "";
            foreach ($batches as $batch)
            {
                $batchList = $batch->name.",".$batchList;
            }
            $profiles[] = ['S.No'=>$index,'Name'=>$applicantDetail->profile->name,'Profile Link'=>"https://www.tagtaste.com/@".$applicantDetail->profile->handle,
                'Delivery Address'=>$applierAddress,'product Name'=>$batchList];
            $index++;
        }
        $columns = array('S.No','Name','Profile Link','Delivery Address','product Name');
        Excel::create($collaborateId."_HUT_USER_ADDRESS_LIST", function($excel) use($profiles, $columns,$collaborateId) {

            // Set the title
            $excel->setTitle("HUT USER ADDRESS");

            // Chain the setters
            $excel->setCreator('TagTaste')
                ->setCompany('TagTaste');

            // Call them separately
            $excel->setDescription('Collaboration Applicant applier HUT Address');
            // Our first sheet
            $excel->sheet('First sheet', function($sheet) use($profiles, $columns,$collaborateId) {
                $sheet->setOrientation('landscape');
                $sheet->row(1,array("Collaboration Link - "."https://www.tagtaste.com/collaborate/".$collaborateId));
                $sheet->row(3,$columns);
                $index = 4;
                foreach ($profiles as $key => $value) {
                    $sheet->appendRow($index, $value);
                    $index++;
                }
                $sheet->appendRow("");

            });

        })->store('xls');
        $filePath = storage_path("exports/".$collaborateId."_HUT_USER_ADDRESS_LIST.xls");

        return response()->download($filePath, $collaborateId."_HUT_USER_ADDRESS_LIST.xls", $headers);
    }

    public function reportSummary(Request $request, $collaborateId)
    {
        //filters data
        $filters = $request->input('filters');
        $profileIds = $this->getFilterProfileIds($filters,$collaborateId);
        $type = true;
        $boolean = 'and' ;
        if(isset($filters))
            $type = false;
        $questionIds = Collaborate\Questions::select('id')->where('collaborate_id',$collaborateId)->where('questions->select_type',5)->get()->pluck('id');
        $overAllPreferences = \DB::table('collaborate_tasting_user_review')->select('tasting_header_id','question_id','leaf_id','batch_id','value',\DB::raw('count(*) as total'))->where('current_status',3)
            ->where('collaborate_id',$collaborateId)->whereIn('profile_id', $profileIds, $boolean, $type)->whereIn('question_id',$questionIds)
            ->orderBy('tasting_header_id','ASC')->orderBy('batch_id','ASC')->orderBy('leaf_id','ASC')->groupBy('tasting_header_id','question_id','leaf_id','value','batch_id')->get();

        $batches = \DB::table('collaborate_batches')->where('collaborate_id',$collaborateId)->orderBy('id')->get();

        $model = [];
        $headers = Collaborate\ReviewHeader::where('collaborate_id',$collaborateId)->get();
        foreach ($headers as $header)
        {
            $data = [];
            if($header->header_type == 'INSTRUCTIONS')
                continue;
            $data['header_type'] = $header->header_type;
            $data['id'] = $header->id;
            foreach ($batches as $batch)
            {
                $item  = [];
                $item['batch_info'] = $batch;
                $totalValue = 0;
                $totalReview = 0;
                foreach ($overAllPreferences as $overAllPreference)
                {

                    if($header->id == $overAllPreference->tasting_header_id && $batch->id == $overAllPreference->batch_id)
                    {
                        $totalReview = $totalReview + $overAllPreference->total;
                        $totalValue = $totalValue + $overAllPreference->leaf_id * $overAllPreference->total;
                    }
                }
                if($totalValue && $totalReview)
                    $item['overAllPreference'] = number_format((float)($totalValue/$totalReview), 2, '.', '');
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
        $profileIds = \DB::table('collaborate_batches_assign')->where('collaborate_id',$collaborateId)->where('batch_id',$batchId)->get()->pluck('profile_id');
        $query = $request->input('term');
        $profileIds = \App\Recipe\Profile::select('profiles.id')->join('users','profiles.user_id','=','users.id')
            ->whereIn('profiles.id',$profileIds)->where('users.name','like',"%$query%")
            ->get()->pluck('id');

        $this->model = Profile::whereIn('id',$profileIds)->get();
        return $this->sendResponse();
    }

    public function getFilterProfileIds($filters, $collaborateId, $batchId = null)
    {
        $profileIds = new Collection([]);
        if($profileIds->count() == 0 && isset($filters['profile_id']))
        {
            $filterProfile = [];
            foreach ($filters['profile_id'] as $filter)
            {
                $filterProfile[] = (int)$filter;
            }
            $profileIds = $profileIds->merge($filterProfile);
        }
        if(isset($filters['current_status']) && !is_null($batchId))
        {
            foreach ($filters['current_status'] as $currentStatus)
            {
                $currentStatusIds = new Collection([]);
                if($currentStatus == 0 || $currentStatus == 1)
                {
                    if($profileIds->count() > 0)
                        $ids = \DB::table('collaborate_batches_assign')->where('collaborate_id',$collaborateId)->where('batch_id', $batchId)
                            ->whereIn('profile_id',$profileIds)->where('begin_tasting',$currentStatus)->get()->pluck('profile_id');
                    else
                        $ids = \DB::table('collaborate_batches_assign')->where('collaborate_id',$collaborateId)->where('batch_id', $batchId)
                            ->where('begin_tasting',$currentStatus)->get()->pluck('profile_id');

                    $ids2 = \DB::table('collaborate_tasting_user_review')->where('collaborate_id',$collaborateId)->where('batch_id', $batchId)
                        ->get()->pluck('profile_id');

                    $ids = $ids->diff($ids2);
                }
                else
                {
                    if($profileIds->count() > 0)
                        $ids = \DB::table('collaborate_tasting_user_review')->where('collaborate_id',$collaborateId)->where('batch_id', $batchId)
                            ->whereIn('profile_id',$profileIds)->where('current_status',$currentStatus)->get()->pluck('profile_id');
                    else
                        $ids = \DB::table('collaborate_tasting_user_review')->where('collaborate_id',$collaborateId)->where('batch_id', $batchId)
                            ->where('current_status',$currentStatus)->get()->pluck('profile_id');
                }
                $currentStatusIds = $currentStatusIds->merge($ids);
                $profileIds = $currentStatusIds;
            }
            $profileIds = $currentStatusIds;

        }
        if(isset($filters['city']))
        {
            $cityFilterIds = new Collection([]);
            foreach ($filters['city'] as $city)
            {
                if($profileIds->count() > 0)
                    $ids = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->where('city', 'LIKE', $city)
                        ->whereIn('profile_id',$profileIds)->get()->pluck('profile_id');
                else
                    $ids = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->where('city', 'LIKE', $city)->get()->pluck('profile_id');

                $cityFilterIds = $cityFilterIds->merge($ids);

            }
            $profileIds = $cityFilterIds;

        }
        if(isset($filters['age']))
        {
            $ageFilterIds = new Collection([]);
            foreach ($filters['age'] as $age)
            {
                $age = htmlspecialchars_decode($age);
                if($profileIds->count() > 0 )
                    $ids = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->where('age_group', 'LIKE', $age)
                        ->whereIn('profile_id',$profileIds)->get()->pluck('profile_id');
                else
                    $ids = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->where('age_group', 'LIKE', $age)
                        ->get()->pluck('profile_id');
                $ageFilterIds = $ageFilterIds->merge($ids);
            }
            $profileIds = $ageFilterIds;

        }
        if(isset($filters['gender']))
        {
            $genderFilterIds = new Collection([]);

            foreach ($filters['gender'] as $gender)
            {
                if($profileIds->count() > 0 )
                    $ids = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->where('gender', 'LIKE', $gender)
                        ->whereIn('profile_id',$profileIds)->get()->pluck('profile_id');
                else
                    $ids = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->where('gender', 'LIKE', $gender)
                        ->get()->pluck('profile_id');
                $genderFilterIds = $genderFilterIds->merge($ids);
            }
            $profileIds = $genderFilterIds;
        }

        return $profileIds;
    }

    public function reportPdf(Request $request, $collaborateId,$batchId)
    {
        $collaborate = Collaborate::where('id',$collaborateId)->where('state','!=',Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;

        if(isset($collaborate->company_id)&& (!is_null($collaborate->company_id)))
        {
            $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$profileId)->exists();
            if(!$checkUser){
                return $this->sendError("Invalid Collaboration Project.");
            }
        }
        else if($collaborate->profile_id != $profileId){
            return $this->sendError("Invalid Collaboration Project.");
        }

        $headers = Collaborate\ReviewHeader::where('collaborate_id',$collaborateId)->get();

        $this->model = [];
        //filters data
        $filters = $request->input('filters');
        $profileIds = $this->getFilterProfileIds($filters,$collaborateId, $batchId);
        $type = true;
        $boolean = 'and' ;
        if(isset($filters))
            $type = false;
        foreach ($headers as $header)
        {
            if($header->header_type == 'INSTRUCTIONS')
                continue;
            $headerId = $header->id;
            $withoutNest = \DB::table('collaborate_tasting_questions')->where('collaborate_id',$collaborateId)
                ->whereNull('parent_question_id')->where('header_type_id',$headerId)->orderBy('id')->get();
            $withNested = \DB::table('collaborate_tasting_questions')->where('collaborate_id',$collaborateId)
                ->whereNotNull('parent_question_id')->where('header_type_id',$headerId)->orderBy('id')->get();

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

            $totalApplicants = \DB::table('collaborate_tasting_user_review')->where('value','!=','')->where('current_status',3)->where('collaborate_id',$collaborateId)
                ->whereIn('profile_id', $profileIds, $boolean, $type)->where('batch_id',$batchId)->distinct()->get(['profile_id'])->count();
            $model = [];
            foreach ($withoutNest as $data)
            {
                $reports = [];
                if(isset($data->questions)&&!is_null($data->questions))
                {
                    $reports['question_id'] = $data->id;
                    $reports['title'] = $data->title;
                    $reports['subtitle'] = $data->subtitle;
                    $reports['is_nested_question'] = $data->is_nested_question;
                    $reports['question'] = $data->questions ;
                    if(isset($data->questions->is_nested_question) && $data->questions->is_nested_question == 1)
                    {
                        $subAnswers = [];
                        foreach ($data->questions->questions as $item)
                        {
                            $subReports = [];
                            $subReports['question_id'] = $item->id;
                            $subReports['title'] = $item->title;
                            $subReports['subtitle'] = isset($item->subtitle) ? $item->subtitle : null;
                            $subReports['is_nested_question'] = $item->is_nested_question;
                            $subReports['total_applicants'] = $totalApplicants;
                            $subReports['total_answers'] = \DB::table('collaborate_tasting_user_review')->where('current_status',3)->where('collaborate_id',$collaborateId)
                                ->whereIn('profile_id', $profileIds, $boolean, $type)->where('batch_id',$batchId)->where('question_id',$item->id)->distinct()->get(['profile_id'])->count();
                            $subReports['answer'] = \DB::table('collaborate_tasting_user_review')->select('leaf_id','value','intensity',\DB::raw('count(*) as total'))->where('current_status',3)
                                ->whereIn('profile_id', $profileIds, $boolean, $type)->where('collaborate_id',$collaborateId)->where('batch_id',$batchId)->where('question_id',$item->id)
                                ->orderBy('question_id')->groupBy('question_id','value','leaf_id','intensity')->get();
                            $subAnswers[] = $subReports;
                        }
                        $reports['nestedAnswers'] = $subAnswers;

                    }
                    else
                        unset($reports['nestedAnswers']);
                    $reports['total_applicants'] = $totalApplicants;
                    $reports['total_answers'] = \DB::table('collaborate_tasting_user_review')->where('current_status',3)->where('collaborate_id',$collaborateId)
                        ->whereIn('profile_id', $profileIds, $boolean, $type)->where('batch_id',$batchId)->where('question_id',$data->id)->distinct()->get(['profile_id'])->count();
                    if(isset($data->questions->select_type) && $data->questions->select_type == 3)
                    {
                        $reports['answer'] = Collaborate\Review::where('collaborate_id',$collaborateId)->where('batch_id',$batchId)->where('question_id',$data->id)
                            ->whereIn('profile_id', $profileIds, $boolean, $type)->where('current_status',3)->where('tasting_header_id',$headerId)->skip(0)->take(3)->get();
                    }
                    else
                    {
                        $reports['answer'] = \DB::table('collaborate_tasting_user_review')->select('leaf_id','value','intensity',\DB::raw('count(*) as total'))->where('current_status',3)
                            ->whereIn('profile_id', $profileIds, $boolean, $type)->where('collaborate_id',$collaborateId)->where('batch_id',$batchId)->where('question_id',$data->id)
                            ->orderBy('question_id')->groupBy('question_id','value','leaf_id','intensity')->get();
                    }

                    if(isset($data->questions->is_nested_option))
                    {
                        $reports['is_nested_option'] = $data->questions->is_nested_option;
                        if($data->questions->is_nested_option == 1)
                        {
                            foreach($reports['answer'] as &$item)
                            {
                                $nestedOption = \DB::table('collaborate_tasting_nested_options')->where('header_type_id',$headerId)
                                    ->where('question_id',$data->id)->where('id',$item->leaf_id)->where('value','like',$item->value)->first();
                                $item->path = isset($nestedOption->path) ? $nestedOption->path : null;
                            }
                        }
                    }

                    $model[] = $reports;
                }
            }
            $this->model[] = ['headerName'=>$header->header_type,'data'=>$model];
        }
        $data = $this->model;
//        dd($data);
        $pdf = PDF::loadView('collaborates.reports',['data' => $data,'filters'=>$filters]);
        $pdf = $pdf->output();
        file_put_contents("collaboratesreport.pdf",$pdf);
        $pdf = base64_encode(file_get_contents("collaboratesreport.pdf",$pdf));
        return response()->json(['pdf'=>$pdf]);
//        return PDF::view('collaborates.reports',['data' => $data,'filters'=>$filters]);

    }
}
