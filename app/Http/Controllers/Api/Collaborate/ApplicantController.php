<?php namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate;
use App\CompanyUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class ApplicantController extends Controller
{

    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Collaborate\Applicant $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request,$collaborateId)
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
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model = [];
        $applicants = Collaborate\Applicant::where('collaborate_id',$collaborateId)
            ->whereNull('rejected_at')->skip($skip)->take($take)->get();

        $applicants = $applicants->toArray();
        foreach ($applicants as &$applicant)
        {
            $batchIds = \DB::table('collaborate_batches_assign')->where('collaborate_id',$collaborateId)
                ->where('profile_id',$applicant['profile_id'])->get()->pluck('batch_id');

            $applicant['batches'] = Collaborate\Batches::whereIn('id',$batchIds)->get();
        }
        $this->model['applicants'] = $applicants;
        $this->model['totalApplicants'] = Collaborate\Applicant::where('collaborate_id',$collaborateId)
            ->whereNull('rejected_at')->count();
        $this->model['rejectedApplicants'] = Collaborate\Applicant::where('collaborate_id',$collaborateId)->whereNull('shortlisted_at')
            ->whereNotNull('rejected_at')->count();

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
        $collaborate = Collaborate::where('id',$collaborateId)->where('state',Collaborate::$state[0])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $isInvited = $request->input(['is_invited']);
        $now = Carbon::now()->toDateTimeString();
        if($isInvited == 0)
        {
            $loggedInprofileId = $request->user()->profile->id;
            $checkApplicant = Collaborate\Applicant::where('collaborate_id',$collaborateId)->where('profile_id',$loggedInprofileId)->exists();
            if($checkApplicant)
            {
                return $this->sendError("Already Applied");
            }
            $inputs = ['is_invite'=>$isInvited,'profile_id'=>$loggedInprofileId,'collaborate_id'=>$collaborateId,'message'=>$request->input('message')];

        }
        else
        {
            if(!$request->has('profile_id'))
            {
                return $this->sendError("Please select user for invitation");
            }
            $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$request->user()->profile->id)->exists();
            if(!$checkUser){
                return $this->sendError("You are not admin.");
            }
            if($request->user()->profile->id == $request->input('profile_id'))
            {
                return $this->sendError("You can not invite admins of company");
            }
            $checkApplicant = Collaborate\Applicant::where('collaborate_id',$collaborateId)->where('profile_id',$request->input('profile_id'))
                ->whereNotNull('shortlisted_at')->exists();
            if($checkApplicant)
            {
                return $this->sendError("Already Invited");
            }
            $inputs = ['is_invite'=>$isInvited,'profile_id'=>$request->input('profile_id'),'collaborate_id'=>$collaborateId,'shortlisted_at'=>$now];
        }
        $this->model = $this->model->create($inputs);

        if(isset($collaborate->company_id)&& (!is_null($collaborate->company_id)))
        {
            $profileIds = CompanyUser::where('company_id',$collaborate->company_id)->get()->pluck('profile_id');
            foreach ($profileIds as $profileId)
            {
                $collaborate->profile_id = $profileId;
                event(new \App\Events\Actions\Apply($collaborate, $request->user()->profile, $request->input("message","")));

            }
        }
        else
        {
            event(new \App\Events\Actions\Apply($collaborate, $request->user()->profile, $request->input("message","")));
        }

        if(isset($this->model))
        {
            $this->model = true;
        }
        else
        {
            $this->model = false;
        }

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
        $this->model = $this->model->find($id);

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

    public function assignPeople(Request $request, $id)
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
        $applierProfileId = $request->input('profile_id');
        $batchIds = $request->input('batch_id');
        $inputs = [];
        foreach ($batchIds as $batchId)
        {
            $inputs['profile_id'] = $applierProfileId;
            $inputs['batch_id'] = $batchId;
        }
        $this->model = \DB::table('collaborate_batches_assign')->insert($inputs);

        return $this->sendResponse();
    }

    public function shortlistPeople(Request $request, $collaborateId)
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

        $shortlistedProfiles = $request->input('profile_id');
        if(!is_array($shortlistedProfiles)){
            $shortlistedProfiles = [$shortlistedProfiles];
        }
        $now = Carbon::now()->toDateTimeString();

        $this->model = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)
            ->whereIn('profile_id',$shortlistedProfiles)->update(['shortlisted_at'=>$now,'rejected_at'=>null]);

        return $this->sendResponse();
    }

    public function rejectPeople(Request $request, $collaborateId)
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

        $shortlistedProfiles = $request->input('profile_id');
        if(!is_array($shortlistedProfiles)){
            $shortlistedProfiles = [$shortlistedProfiles];
        }
        $now = Carbon::now()->toDateTimeString();

        $this->model = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)
            ->whereIn('profile_id',$shortlistedProfiles)->update(['rejected_at'=>$now,'shortlisted_at'=>null]);

        return $this->sendResponse();
    }

    public function inviteForReview(Request $request, $id)
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
        $profileIds = $request->input('profile_id');
        $inputs = [];
        $checkExist = \DB::table('collaborate_applicants')->whereIn('profile_id',$profileIds)->where('collaborate_id',$id)->exists();
        if($checkExist)
        {
            return $this->sendError("Already Invited");
        }
        $company = \Redis::get('company:small:' . $collaborate->company_id);
        $company = json_decode($company);
        foreach ($profileIds as $profileId)
        {
            $collaborate->profile_id = $profileId;
            event(new \App\Events\Actions\InviteForReview($collaborate,null,null,null,null,$company));
            $inputs[] = ['profile_id'=>$profileId, 'collaborate_id'=>$id,'is_invited'=>1];
        }
        $this->model = $this->model->insert($inputs);
        return $this->sendResponse();

    }

    public function acceptInvitation(Request $request, $id)
    {
        $collaborate = Collaborate::where('id',$id)->where('state','!=',Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }

        $loggedInProfileId = $request->user()->profile->id;
        $now = Carbon::now()->toDateTimeString();
        $this->model = \DB::table('collaborate_applicants')->where('collaborate_id',$id)
            ->where('profile_id',$loggedInProfileId)->update(['shortlisted_at'=>$now,'rejected_at'=>null]);

//        if($this->model)
//        {
//            $company = \Redis::get('company:small:' . $collaborate->company_id);
//            $company = json_decode($company);
//            $collaborate->profile_id = $loggedInProfileId;
//            event(new \App\Events\Actions\InvitationAcceptForReview($collaborate,null,null,null,null,$company));
//        }

        return $this->sendResponse();
    }

    public function rejectInvitation(Request $request, $id)
    {
        $collaborate = Collaborate::where('id',$id)->where('state','!=',Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $loggedInProfileId = $request->user()->profile->id;
        $this->model = \DB::table('collaborate_applicants')->where('collaborate_id',$id)
            ->where('profile_id',$request->user()->profile->id)->delete();

//        if($this->model)
//        {
//            $company = \Redis::get('company:small:' . $collaborate->company_id);
//            $company = json_decode($company);
//            $collaborate->profile_id = $loggedInProfileId;
//            event(new \App\Events\Actions\InvitationAcceptForReview($collaborate,null,null,null,null,$company));
//        }

        return $this->sendResponse();
    }

    public function getShortlistApplicants(Request $request, $collaborateId)
    {
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model = $this->model->where('collaborate_id',$collaborateId)->whereNotNull('shortlisted_at')
            ->whereNull('rejected_at')->skip($skip)->take($take)->get();

        return $this->sendResponse();
    }

    public function getRejectApplicants(Request $request, $collaborateId)
    {
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model = [];
        $this->model['rejectedApplicantsCount'] = Collaborate\Applicant::where('collaborate_id',$collaborateId)->whereNull('shortlisted_at')
            ->whereNotNull('rejected_at')->count();
        $this->model['rejectedApplicantList'] = Collaborate\Applicant::where('collaborate_id',$collaborateId)->whereNull('shortlisted_at')
            ->whereNotNull('rejected_at')->skip($skip)->take($take)->get();

        return $this->sendResponse();
    }

}
