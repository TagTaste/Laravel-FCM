<?php namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate;
use App\CompanyUser;
use App\Recipe\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

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
                ->where('batch_id',$batch['id'])->distinct('profile_id')->count();

            $batch['assignedCount'] = \DB::table('collaborate_batches_assign')->where('batch_id',$batch['id'])->distinct('profile_id')->count();
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
    public function show($collaborateId,$id)
    {
        $profileIds = \DB::table('collaborate_batches_assign')->where('batch_id',$id)->get()->pluck('profile_id');
        $profiles = Profile::whereIn('id',$profileIds)->get();

        $profiles = $profiles->toArray();
        foreach ($profiles as &$profile)
        {
            $currentStatus = \Redis::get("current_status:batch:$id:profile:" . $profile['id']);
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
        $checkUserShortlist = Collaborate\Applicant::whereIn('profile_id',$applierProfileIds)->whereNotNull('shortlisted_at')->exists();
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
        $checkUserReview = \DB::table('collaborate_tasting_user_review')->where('batch_id',$batchId)->whereIn('profile_id',$profileIds)->exists();
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
            $company = \Redis::get('company:small:' . $collaborate->company_id);
            $company = json_decode($company);
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
            foreach ($batchInfos as &$batchInfo) {
                $batchInfo = json_decode($batchInfo);
                $currentStatus = \Redis::get("current_status:batch:$batchInfo->id:profile:" . $loggedInProfileId);
                $batchInfo->current_status = !is_null($currentStatus) ? (int)$currentStatus : 0;
            }
        }
        $collaborate['batches'] = $count > 0 ? $batchInfos : null;
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

}
