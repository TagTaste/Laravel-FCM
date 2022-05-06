<?php 

namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate;
use App\CompanyUser;
use App\Recipe\Company;
use App\Recipe\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Api\Controller;
use App\Profile as AppProfile;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\File;
use App\Traits\FilterFactory;

class ApplicantController extends Controller
{
    use FilterFactory;

    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Collaborate\Applicant $model)
    {
        $this->model = $model;
        $this->middleware('permissionCollaborate', ['only' => [
            'index' // Could add bunch of more methods too
        ]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request,$collaborateId)
    {
        
        
        $collaborate = Collaborate::where('id',$collaborateId)
                            //->where('state','!=',Collaborate::$state[1])
                            ->first();

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

        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model = [];
        //filters data
        $q = $request->input('q');
        $filters = $request->input('filters');
        $profileIds = $this->getFilteredProfile($filters,$collaborateId);
        
        $type = true;
        $boolean = 'and' ;
        if(isset($filters))
            $type = false;
        $applicants = Collaborate\Applicant::where('collaborate_id',$collaborateId);
        if(isset($q) && $q != null) {
            $searchedProfiles = $this->getSearchedProfile($q, $collaborateId);
            $applicants = $applicants->whereIn('id', $searchedProfiles);
        }
        if($request->sortBy != null) {
            $applicants = $this->sortApplicants($request->sortBy,$applicants,$collaborateId);
        }

     

        $applicants = $applicants
        ->whereIn('profile_id', $profileIds)
        ->whereNotNull('shortlisted_at')            
        ->whereNull('rejected_at')//->orderBy("created_at","desc")
        ->skip($skip)->take($take)->get();
        
        $applicants = $applicants->toArray();
        
        $pId = [];
        foreach ($applicants as &$applicant)
        {
            
            $batchIds = Redis::sMembers("collaborate:".$applicant['collaborate_id'].":profile:".$applicant['profile_id'].":");
            $count = count($batchIds);
            if($count)
            {
                foreach ($batchIds as &$batchId)
                {
                    $batchId = "batch:".$batchId;
                }
                $batchInfos = Redis::mGet($batchIds);
                foreach ($batchInfos as &$batchInfo)
                {
                    $batchInfo = json_decode($batchInfo);
                    $currentStatus = Redis::get("current_status:batch:$batchInfo->id:profile:".$applicant['profile_id']);
                    $batchInfo->current_status = !is_null($currentStatus) ? (int)$currentStatus : 0;
                }
                
            }
            $pId[] = $applicant['profile_id'];
            $applicant['batches'] = $count > 0 ? $batchInfos : null;
        }
        
        
           //count of sensory trained
        $countSensory = AppProfile::where('is_sensory_trained',"=",1)
           ->whereIn('profiles.id', $pId)
           ->get();
           
           
          //count of experts
          $countExpert = \DB::table('profiles')
          ->select('id')
          ->where('is_expert',1)
          ->whereIn('id', $pId)
          ->get();

          //count of super tasters
          $countSuperTaste = \DB::table('profiles')
          ->select('id')
          ->where('is_tasting_expert',1)
          ->whereIn('id', $pId)
          ->get();
        $this->model['applicants'] = $applicants;
        $this->model['totalApplicants'] = Collaborate\Applicant::where('collaborate_id',$collaborateId)->whereNotNull('shortlisted_at')
            ->whereNull('rejected_at')->count();
        $this->model['rejectedApplicants'] = Collaborate\Applicant::where('collaborate_id',$collaborateId)//->whereNull('shortlisted_at')
            ->whereNotNull('rejected_at')->count();
        $this->model['invitedApplicantsCount'] = Collaborate\Applicant::where('collaborate_id',$collaborateId)->where('is_invited',1)
            ->whereNull('shortlisted_at')->whereNull('rejected_at')->count();
        $this->model["overview"][] = ['title'=> "Sensory Trained","count"=>$countSensory->count()];
        $this->model["overview"][] = ['title'=> "Experts","count"=>$countExpert->count()];
        $this->model["overview"][] = ['title'=> "Super Taster","count"=>$countSuperTaste->count()];
        
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
        $loggedInprofileId = $request->user()->profile->id;
        $collaborate = Collaborate::where('id',$collaborateId)->where('state',Collaborate::$state[0])->first();
        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }

        $isInvited = 0;
        $now = Carbon::now()->toDateTimeString();
        if (!$request->has('applier_address')){
            return $this->sendError("Please select address.");
        }
        // if(isset($collaborate->company_id)&& (!is_null($collaborate->company_id)))
        // {
        //     $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$loggedInprofileId)->exists();
        //     if($checkUser){
        //         return $this->sendError("Invalid Collaboration Project.");
        //     }
        // }
        // else if($collaborate->profile_id == $loggedInprofileId){
        //     return $this->sendError("Invalid Collaboration Project.");
        // }

        if ($isInvited == 0) {
            $loggedInprofileId = $request->user()->profile->id;
            $checkApplicant = Collaborate\Applicant::where('collaborate_id',$collaborateId)->where('profile_id',$loggedInprofileId)->exists();
            if ($checkApplicant) {
                return $this->sendError("Already Applied");
            }
            $hut = $request->has('hut') ? $request->input('hut') : 0 ;
            if($request->applier_address) {
                $applierAddress = $request->input('applier_address');
            $address = json_decode($applierAddress,true);
            $city = (isset($address['collaborate_city'])) ? $address['collaborate_city'] : null;
            } else {
                $city = null;
                $applierAddress = null;
            }
            $profile = Profile::where('id',$loggedInprofileId)->first();
            // Commented by nikhil because some users are not able to show interest
            // if (!isset($profile->ageRange) || is_null($profile->ageRange) || !isset($profile->gender) || is_null($profile->gender)) {
            //     $this->model = null;
            //     return $this->sendError("Please fill mandatory feild.");
            // }
            $inputs = ['is_invite'=>$isInvited,'profile_id'=>$loggedInprofileId,'collaborate_id'=>$collaborateId,
                'message'=>$request->input('message'),'applier_address'=>$applierAddress,'hut'=>$hut,
                'shortlisted_at'=>$now,'city'=>$city,'age_group'=>$profile->ageRange,'gender'=>$profile->gender,'hometown'=>$profile->hometown,'current_city'=>$profile->city];
        }
        
        if ($collaborate->document_required) {
            $doc = \DB::table('profile_documents')->where('profile_id',$loggedInprofileId)->first();
            if (is_null($doc)) {
                return $this->sendError("please upload document");
            } else if (!isset($request->terms_verified)) {
                return $this->sendError("please agree to terms and conditions");
            } else {
                $inputs['terms_verified'] = 1;
                $inputs['document_meta'] = $doc->document_meta;
                $inputs['documents_verified'] = $doc->is_verified;
            }
        }
        $inputs['share_number'] = $request->has('share_number') ? $request->share_number : 0;
        $this->model = $this->model->create($inputs);

        if (isset($this->model)) {
            $this->model = true;

            if (isset($collaborate->company_id)&& (!is_null($collaborate->company_id))) {
                $company = Redis::get('company:small:' . $collaborate->company_id);
                $company = json_decode($company);
                $profileIds = CompanyUser::where('company_id',$collaborate->company_id)->get()->pluck('profile_id');
                foreach ($profileIds as $profileId) {
                    $collaborate->profile_id = $profileId;
                    event(new \App\Events\Actions\Apply($collaborate, $request->user()->profile, $request->input("message",""),null,null, $company));

                }
            } else {
                event(new \App\Events\Actions\Apply($collaborate, $request->user()->profile, $request->input("message","")));
            }
        } else {
            $this->model = false;
        }

        try {
            $batch_inputs = [];
            $batches = Collaborate\Batches::where('collaborate_id',$collaborateId)->get();
            foreach ($batches as $batch) {
                Redis::sAdd("collaborate:$collaborateId:profile:$loggedInprofileId:", $batch->id);
                Redis::set("current_status:batch:$batch->id:profile:$loggedInprofileId" ,0);
                if ($collaborate->track_consistency) {
                    $batch_inputs[] = [
                        'profile_id' => $loggedInprofileId,
                        'batch_id' => $batch->id,
                        'begin_tasting' => 0,
                        'created_at' => $now,
                        'collaborate_id' => (int)$collaborateId,
                        'bill_verified' => 0
                    ];
                } else {
                    $batch_inputs[] = [
                        'profile_id' => $loggedInprofileId,
                        'batch_id' => $batch->id,
                        'begin_tasting' => 0,
                        'created_at' => $now,
                        'collaborate_id' => (int)$collaborateId
                    ];
                }
            }

            \DB::table('collaborate_batches_assign')->insert($batch_inputs);
        } catch (Exception $e) {
            \Log::info($e->getMessage());
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

        $shortlistedProfiles = $request->input('profile_id');
        if(!is_array($shortlistedProfiles)){
            $shortlistedProfiles = [$shortlistedProfiles];
        }
        $now = Carbon::now()->toDateTimeString();

        // begin transaction
        \DB::beginTransaction();
        try {
            // check all the batches in of collaboration
            $batch_inputs = [];
            $batches = Collaborate\Batches::where('collaborate_id',$collaborateId)->pluck('id');
            foreach ($batches as $batchId) {
                foreach ($shortlistedProfiles as $profileId) {
                    Redis::sAdd("collaborate:$collaborateId:profile:$profileId:" ,$batchId);
                    Redis::set("current_status:batch:$batchId:profile:$profileId" ,0);

                    if ($collaborate->track_consistency) {
                        $batch_inputs[] = [
                            'profile_id' => (int)$profileId,
                            'batch_id' => (int)$batchId,
                            'begin_tasting' => 0,
                            'created_at' => $now,
                            'collaborate_id' => (int)$collaborateId,
                            'bill_verified' => 0
                        ];
                    } else {
                        $batch_inputs[] = [
                            'profile_id' => (int)$profileId,
                            'batch_id' => (int)$batchId,
                            'begin_tasting' => 0,
                            'created_at' => $now,
                            'collaborate_id' => (int)$collaborateId,
                        ];    
                    }
                }
            }

            // collaborate assign all the batches to the user
            \DB::table('collaborate_batches_assign')->insert($batch_inputs);

            // shortlist applicant
            $this->model = \DB::table('collaborate_applicants')
                ->where('collaborate_id',$collaborateId)
                ->whereIn('profile_id',$shortlistedProfiles)
                ->update([
                    'shortlisted_at'=>$now,
                    'rejected_at'=>null
                ]);

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

    public function rejectPeople(Request $request, $collaborateId)
    {
        $collaborate = Collaborate::where('id',$collaborateId)->where('state','!=',Collaborate::$state[1])->first();

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

        $shortlistedProfiles = $request->input('profile_id');
        if(!is_array($shortlistedProfiles)){
            $shortlistedProfiles = [$shortlistedProfiles];
        }

        // check if any user is already notified or not
        $checkAssignUser = \DB::table('collaborate_batches_assign')->where('collaborate_id',$collaborateId)->whereIn('profile_id',$shortlistedProfiles)
            ->where('begin_tasting',1)
            ->exists();
        if ($checkAssignUser) {
            return $this->sendError("You can not remove from batch.");
        }
        $now = Carbon::now()->toDateTimeString();

        // begin transaction
        \DB::beginTransaction();
        try {
            // check all the batches in of collaboration
            $batch_inputs = [];
            $batches = Collaborate\Batches::where('collaborate_id',$collaborateId)->pluck('id');
            foreach ($batches as $batchId) {
                foreach ($shortlistedProfiles as $profileId) {
                    Redis::sRem("collaborate:$collaborateId:profile:$profileId:" ,$batchId);
                    \DB::table('collaborate_batches_assign')
                        ->where('batch_id',(int)$batchId)
                        ->where('profile_id',(int)$profileId)
                        ->delete();
                }
                
            }
            // remove applicant
            $this->model = \DB::table('collaborate_applicants')
                ->where('collaborate_id',$collaborateId)
                ->whereIn('profile_id',$shortlistedProfiles)
                ->update(['rejected_at'=>$now]);

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

    public function inviteForReview(Request $request, $id)
    {
        $collaborate = Collaborate::where('id',$id)->where('state','!=',Collaborate::$state[1])->first();

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
        $profileIds = $request->input('profile_id');
        $inputs = [];
        $checkExist = \DB::table('collaborate_applicants')->whereIn('profile_id',$profileIds)->where('collaborate_id',$id)->exists();
        if($checkExist)
        {
            return $this->sendError("Already Invited");
        }
        $company = Company::where('id',$collaborate->company_id)->first();
        $now = Carbon::now()->toDateTimeString();
        $profile =  Profile::join('users','users.id','profiles.user_id')->where('id',$profileId)->first();

        foreach ($profileIds as $profileId)
        {
            $collaborate->profile_id = $profileId;
            event(new \App\Events\Actions\InviteForReview($collaborate,$profile,null,null,null,$company));
            $inputs[] = ['profile_id'=>$profileId, 'collaborate_id'=>$id,'is_invited'=>1,'created_at'=>$now,'updated_at'=>$now];
        }
        $this->model = $this->model->insert($inputs);
        $this->model = Collaborate\Applicant::whereIn('profile_id',$profileIds)->where('collaborate_id',$id)->get();
        return $this->sendResponse();

    }

    public function acceptInvitation(Request $request, $id)
    {
        $collaborate = Collaborate::where('id',$id)->where('state','!=',Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        if(!$request->has('applier_address') && !$collaborate->track_consistency)
        {
            return $this->sendError("Please select address.");
        }
        $loggedInProfileId = $request->user()->profile->id;
        $hut = $request->has('hut') ? $request->input('hut') : 0 ;
        $applierAddress = $request->input('applier_address');
        $address = json_decode($applierAddress,true);
        $city = (isset($address['collaborate_city'])) ? $address['collaborate_city'] : null;
        $profile = Profile::where('id',$loggedInProfileId)->first();
        // Commented by nikhil because some users are not able to show interest
        // if(!isset($profile->ageRange) || is_null($profile->ageRange) || !isset($profile->gender) || is_null($profile->gender))
        // {
        //     $this->model = null;
        //     return $this->sendError("Please fill mandatory feild.");
        // }

        $terms_verified = 0;
        $document_meta = null;
        $documents_verified = 0;
        if($collaborate->document_required) {
            $doc = \DB::table('profile_documents')->where('profile_id',$loggedInProfileId)->first();
            if (is_null($doc)) {
                return $this->sendError("please upload document");
            } else if (!isset($request->terms_verified)) {
                return $this->sendError("please agree to terms and conditions");
            } else {
                $terms_verified = 1;
                $document_meta = $doc->document_meta;
                $documents_verified = $doc->is_verified;
            }
        }
        $share_number = $request->has('share_number') ? $request->share_number : 0;
        $now = Carbon::now()->toDateTimeString();
        $this->model = \DB::table('collaborate_applicants')
            ->where('collaborate_id',$id)
            ->where('profile_id',$loggedInProfileId)
            ->update([
                'shortlisted_at'=>$now,
                'rejected_at'=>null,
                'message'=>$request->input('message'),
                'applier_address'=>$applierAddress,
                'hut'=>$hut,
                'city'=>$city,
                'age_group'=>$profile->ageRange,
                'gender'=>$profile->gender,
                'document_meta'=>$document_meta,
                'terms_verified'=>$terms_verified,
                'documents_verified'=>$documents_verified,
                'share_number'=>$share_number
            ]);

        if ($this->model) {
            $company = Company::where('id',$collaborate->company_id)->first();
            $profileIds = CompanyUser::where('company_id',$collaborate->company_id)
                ->get()
                ->pluck('profile_id');
            foreach ($profileIds as $profileId) {
                $collaborate->profile_id = $profileId;
                event(new \App\Events\Actions\InvitationAcceptForReview($collaborate, $request->user()->profile, $request->input("message",""), null, null, $company));
            }
        }

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

        if($this->model)
        {
            $company = Company::where('id',$collaborate->company_id)->first();
            $profileIds = CompanyUser::where('company_id',$collaborate->company_id)->get()->pluck('profile_id');
            foreach ($profileIds as $profileId)
            {
                $collaborate->profile_id = $profileId;
                event(new \App\Events\Actions\InvitationRejectForReview($collaborate,$request->user()->profile,null,null,null,$company));
            }
        }

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
        $q = $request->input('q');
        $filters = $request->input('filters');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model = [];
        $list = Collaborate\Applicant::where('collaborate_id',$collaborateId)//->whereNull('shortlisted_at')
        ->whereNotNull('rejected_at');

        if(isset($q) && $q != null) {
            $ids = $this->getSearchedProfile($q, $collaborateId);
            $list = $list->whereIn('id', $ids);
        }
        
        if(isset($filters) && $filters != null) {
            $profileIds = $this->getFilteredProfile($filters, $collaborateId);
            $list = $list->whereIn('profile_id',$profileIds);
        }
        if($request->sortBy != null) {
            $archived = $this->sortApplicants($request->sortBy,$list,$collaborateId);
        }

        $this->model['rejectedApplicantsCount'] = $list->count();
            $list = $list->skip($skip)->take($take)->get();
            $this->model['rejectedApplicantList'] = $list;

        return $this->sendResponse();
    }

    public function getUnassignedApplicants(Request $request, $collaborateId)
    {
        $batchId = (int)$request->input("batch_id");
        $this->model = [];
        $page = $request->input('page');
        if(!is_null($page) && $page >1)
        {
            return $this->sendResponse();
        }
        $profileIds = \DB::table('collaborate_batches_assign')->where('batch_id',$batchId)->where('collaborate_id',$collaborateId)->get()->pluck('profile_id')->unique();
        $profiles = Collaborate\Applicant::where('collaborate_id',$collaborateId)->whereNotIn('profile_id',$profileIds)
            ->whereNotNull('shortlisted_at')->whereNull('rejected_at')->get();
        $profiles = $profiles->toArray();
        $applicants = [];
        foreach ($profiles as &$applicant)
        {
            $batchIds = Redis::sMembers("collaborate:".$collaborateId.":profile:".$applicant['profile']['id'].":");
            $count = count($batchIds);
            if($count)
            {
                foreach ($batchIds as &$batchId)
                {
                    $batchId = "batch:".$batchId;
                }
                $batchInfos = Redis::mGet($batchIds);
                foreach ($batchInfos as &$batchInfo)
                {
                    $batchInfo = json_decode($batchInfo);
                    $currentStatus = Redis::get("current_status:batch:$batchInfo->id:profile:".$applicant['profile']['id']);
                    $batchInfo->current_status = !is_null($currentStatus) ? (int)$currentStatus : 0;
                }
            }
            $applicant['batches'] = $count > 0 ? $batchInfos : null;
            $applicants[] = $applicant;
        }
        $this->model['applicants'] = $applicants;
        return $this->sendResponse();
    }

    public function getInvitedApplicants(Request $request, $collaborateId)
    {
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model = [];
        $this->model['invitedApplicantsCount'] = Collaborate\Applicant::where('collaborate_id',$collaborateId)->where('is_invited',1)
            ->whereNull('shortlisted_at')->whereNull('rejected_at')->count();
        $this->model['invitedApplicants'] = Collaborate\Applicant::where('collaborate_id',$collaborateId)->where('is_invited',1)
            ->whereNull('shortlisted_at')->whereNull('rejected_at')->skip($skip)->take($take)->get();

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
            $currentStatusIds = new Collection([]);
            foreach ($filters['current_status'] as $currentStatus)
            {
                if($currentStatus == 0 || $currentStatus == 1)
                {
                    if($profileIds->count() > 0)
                        $ids = \DB::table('collaborate_batches_assign')->where('collaborate_id',$collaborateId)->where('batch_id', $batchId)
                            ->whereIn('profile_id',$profileIds)->where('begin_tasting',$currentStatus)->get()->pluck('profile_id');
                    else
                        $ids = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->where('batch_id', $batchId)
                            ->where('begin_tasting',$currentStatus)->get()->pluck('profile_id');
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
            }
            $profileIds = $currentStatus;
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

    public function getApplicantFilter(Request $request, $collaborateId)
    {
        $filters = $request->input('filter');

        $gender = ['Male','Female','Other'];
        $age = ['< 18','18 - 35','35 - 55','55 - 70','> 70'];
        $currentStatus = [0,1,2,3];
        $userType = ['Expert','Consumer'];
        $sensoryTrained = ["Yes","No"];
        $superTaster = ["SuperTaster", "Normal"];
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
                if($filter == 'super_taster')
                    $data['super_taster'] = $superTaster;
                if($filter == 'user_type')
                    $data['user_type'] = $userType;
                if($filter == 'sensory_trained')
                    $data['sensory_trained'] = $sensoryTrained;
            }
        }
        else
        {
            $data = ['gender'=>$gender,'age'=>$age,'city'=>$city,"user_type"=>$userType,"sensory_trained"=>$sensoryTrained,"super_taster"=>$superTaster];
        }
        $this->model = $data;
        return $this->sendResponse();
    }

    public function rejectDocument(Request $request, $collaborateId)
    {
        $collaborate = Collaborate::where('id',$collaborateId)->where('state','!=',Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $company = Company::where('id',$collaborate->company_id)->first();
        $profileId = $request->user()->profile->id;

        // if (isset($collaborate->company_id)&& (!is_null($collaborate->company_id))) {
        //     $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$profileId)->exists();
        //     if (!$checkUser) {
        //         return $this->sendError("Invalid Collaboration Project.");
        //     }
        // } else if ($collaborate->profile_id != $profileId) {
        //     return $this->sendError("Invalid Collaboration Project.");
        // }
        
        $profileId = $request->profileId;
        if (!isset($profileId) || $profileId == null) {
            return $this->sendError("Please enter profile id");
        }
        
        $applicant = Collaborate\Applicant::where('collaborate_id',$collaborateId)->where('profile_id',$profileId)->first();
        if (is_null($applicant)) {
            return $this->sendError("Applicant not found");
        }

        $this->model = \DB::table('profile_documents')->where('profile_id',$profileId)->where('is_verified',0)->delete();
        $this->model =  $applicant->delete();

        event(new \App\Events\DocumentRejectEvent($profileId,$company,null,$collaborate));
        return $this->sendResponse();
    }
    
    public function acceptDocument(Request $request,$collaborateId)
    {
        $collaborate = Collaborate::where('id',$collaborateId)->where('state','!=',Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;

        // if (isset($collaborate->company_id)&& (!is_null($collaborate->company_id))) {
        //     $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$profileId)->exists();
        //     if (!$checkUser) {
        //         return $this->sendError("Invalid Collaboration Project.");
        //     }
        // } else if ($collaborate->profile_id != $profileId) {
        //     return $this->sendError("Invalid Collaboration Project.");
        // }
        
        $profileId = $request->profileId;
        if (!isset($profileId) || $profileId == null) {
            return $this->sendError("Please enter profile id");
        }
        
        $applicant = Collaborate\Applicant::where('collaborate_id',$collaborateId)->where('profile_id',$profileId)->first();
        if (is_null($applicant)) {
            return $this->sendError("Applicant not found");
        }

        $update_applicant = $applicant->update(['documents_verified'=>1]);
        if (!$update_applicant) {
            return $this->sendError("Please try again. Update failed"); 
        }

        $this->model = \DB::table('profile_documents')->where('profile_id',$profileId)->update(['is_verified'=>1,'document_meta'=>json_encode($applicant->document_meta)]);
        return $this->sendResponse();
    }

    public function getOutlets(Request $request, $collaborateId, $cityId)
    {
        $this->model = \DB::table('collaborate_addresses')->select('collaborate_addresses.address_id','outlets.name')
                        ->where('collaborate_id',$collaborateId)
                        ->where('is_active',1)
                        ->join('outlets','outlets.id','=','collaborate_addresses.outlet_id')
                        ->where('city_id',$cityId)
                        ->get();
        return $this->sendResponse();
    }
    public function getCities(Request $request, $collaborateId)
    {
        $cities = \App\Collaborate\Addresses::select('city_id')->groupBy('city_id')->where('collaborate_id',$collaborateId)->where('is_active',1)->distinct()->get();
        $mod = [];
        foreach($cities as $city) {
                $modl['id'] = $city->id;
                $modl['city'] =$city->city;
                $modl['outlets'] = \DB::table('outlets')->join('collaborate_addresses','outlets.id','=','collaborate_addresses.outlet_id')
                                    ->where('is_active',1)
                                        ->where('city_id',$city->id)
                                        ->where('collaborate_id',$collaborateId)
                                        ->select('outlets.id','outlets.name','collaborate_addresses.is_active','collaborate_addresses.address_id')->get();
                $mod[] = $modl;
        }
                $this->model = $mod;
                return $this->sendResponse();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function export(Request $request,$collaborateId)
    {
        $collaborate = Collaborate::where('id',$collaborateId)
                            //->where('state','!=',Collaborate::$state[1])
                            ->first();

        $batchList = Collaborate\Batches::where("collaborate_id","=",$collaborateId)->get();

        $batches = $batchList->pluck('name')->toArray();
        $batchId = $batchList->pluck('id')->toArray();
        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;
        
        if (!$request->user()->profile->is_premium) {
            return $this->sendError("You dont have access to this premium feature.");
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

        //filters data
        $filters = $request->input('filters');
        $profileIds = $this->getFilteredProfile($filters, $collaborateId);
        $type = true;
        $boolean = 'and' ;
        if(isset($filters))
            $type = false;
        $applicants = Collaborate\Applicant::where('collaborate_id',$collaborateId)
            // ->whereIn('profile_id', $profileIds, $boolean, $type)
            ->whereIn('profile_id', $profileIds)
            ->whereNotNull('shortlisted_at')
            ->whereNull('rejected_at')
            ->orderBy("created_at","desc")
            ->get();
            $batches = Collaborate\Batches::where('collaborate_id',$collaborateId)->get();
            $batchIds = $batches->pluck("id")->toArray();
        $finalData = array();
        
        // return $this->sendResponse($applicants);
        
        foreach ($applicants as $key => $applicant) {
            $job_profile = '';
            if (isset($applicant->profile->profile_occupations)) {
                if (isset($applicant->profile->profile_occupations->toArray()['0'])) {
                    $job_profile = $applicant->profile->profile_occupations->toArray()['0']['name'];
                }
            }
            $specialization = '';
            foreach ($applicant->profile->profile_specializations as $profile_specialization_key => $profile_specialization) {
                if (isset($profile_specialization->toArray()['name'])) {
                    if ($profile_specialization_key == 0) {
                        $specialization .= $profile_specialization->toArray()['name'];
                    } else {
                        $specialization .= ", ".$profile_specialization->toArray()['name'];
                    }
                } 
            }
            $allergens = '';
            foreach ($applicant->profile->allergens as $allergens_key => $profile_allergen) {
                if (isset($profile_allergen->name)) {
                    if ($allergens_key == 0) {
                        $allergens .= $profile_allergen->name;
                    } else {
                        $allergens .= ", ".$profile_allergen->name;
                    }
                } 
            }
            
            $temp = array(
                "S. No" => $key+1,
                "Name" => htmlspecialchars_decode($applicant->profile->name),
                "Gender" => $applicant->profile->gender, 
                "Profile link" => env('APP_URL')."/@".$applicant->profile->handle,
                "Email" => $applicant->profile->email,
                "Phone Number" => $applicant->profile->getContactDetail(),
                "Occupation" => $job_profile,
                "Specialization" => $specialization,
                "Allergens" => $allergens,
                "Hometown" => $applicant->hometown,
                "Current City" => $applicant->current_city
            );
            
            if ($collaborate->collaborate_type == 'collaborate') {
                if ($collaborate->is_taster_residence && !$collaborate->is_contest) {
                    $temp['Delivery Address'] = '';
                    if($applicant->applier_address != (object)null){
                        if (isset($applicant->applier_address['label']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['label']).", ";
                        if (isset($applicant->applier_address['house_no']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['house_no']).", ";
                        if (isset($applicant->applier_address['landmark']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode("Landmark ".$applicant->applier_address['landmark']).", ";
                        if (isset($applicant->applier_address['locality']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['locality']).", ";
                        if (isset($applicant->applier_address['city']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['city']).", ";
                        if (isset($applicant->applier_address['state']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['state']).", ";
                        if (isset($applicant->applier_address['country']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['country']).", ";
                        if (isset($applicant->applier_address['pincode']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['pincode']);
                    }
                } else if (!$collaborate->is_taster_residence && $collaborate->is_contest) {
                    $submissions = $applicant->getSubmissions($applicant->id, $collaborate->id);
                    $temp['Submitted files links'] = '';
                    if (count($submissions)) {
                        foreach ($submissions as $submission_key => $submission) {
                            if (strlen($submission->file_address)) {
                                if ($submission_key == 0) {
                                    $temp['Submitted files links'] .= $submission->file_address;
                                } else {
                                    $temp['Submitted files links'] .= ", ".$submission->file_address;
                                }
                            }
                        }
                    }
                } else if ($collaborate->is_taster_residence && $collaborate->is_contest) {
                    $temp['Delivery Address'] = '';
                    if($applicant->applier_address != (object)null){
                        if (isset($applicant->applier_address['label']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['label']).", ";
                        if (isset($applicant->applier_address['house_no']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['house_no']).", ";
                        if (isset($applicant->applier_address['landmark']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode("Landmark ".$applicant->applier_address['landmark']).", ";
                        if (isset($applicant->applier_address['locality']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['locality']).", ";
                        if (isset($applicant->applier_address['city']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['city']).", ";
                        if (isset($applicant->applier_address['state']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['state']).", ";
                        if (isset($applicant->applier_address['country']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['country']).", ";
                        if (isset($applicant->applier_address['pincode']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['pincode']);
                    }
                    $submissions = $applicant->getSubmissions($applicant->id, $collaborate->id);
                    $temp['Submitted files links'] = '';
                    if (count($submissions)) {
                        foreach ($submissions as $submission_key => $submission) {
                            if (strlen($submission->file_address)) {
                                if ($submission_key == 0) {
                                    $temp['Submitted files links'] .= $submission->file_address;
                                } else {
                                    $temp['Submitted files links'] .= ", ".$submission->file_address;
                                }
                            }
                        }
                    }
                }
            } elseif ($collaborate->collaborate_type == 'product-review') {
                if ($collaborate->is_taster_residence && !$collaborate->document_required) {
                    $temp['Delivery Address'] = '';
                    if($applicant->applier_address != (object)null){
                        if (isset($applicant->applier_address['label'])){
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['label']).", ";
                        }
                        if (isset($applicant->applier_address['house_no']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['house_no']).", ";
                        if (isset($applicant->applier_address['landmark']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode("Landmark ".$applicant->applier_address['landmark']).", ";
                        if (isset($applicant->applier_address['locality']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['locality']).", ";
                        if (isset($applicant->applier_address['city']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['city']).", ";
                        if (isset($applicant->applier_address['state']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['state']).", ";
                        if (isset($applicant->applier_address['country']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['country']).", ";
                        if (isset($applicant->applier_address['pincode']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['pincode']);
                    }
                } else if (!$collaborate->is_taster_residence && $collaborate->document_required) {
                    $temp['Document Verified'] = $applicant->documents_verified;
                    $temp['Date of Birth'] = $applicant->profile->dob;
                    $temp['Age Proof Document Links'] = '';

                    if (count($applicant->document_meta)) {
                        foreach ($applicant->document_meta as $document_meta_key => $document_meta) {
                            if (strlen($document_meta->original_photo)) {
                                if ($document_meta_key == 0) {
                                    $temp['Age Proof Document Links'] .= $document_meta->original_photo;
                                } else {
                                    $temp['Age Proof Document Links'] .= ", ".$document_meta->original_photo;
                                }
                            }
                        }
                    }
                } else if ($collaborate->is_taster_residence && $collaborate->document_required) {
                    $temp['Delivery Address'] = '';
                    if($applicant->applier_address != (object)null){
                        if (isset($applicant->applier_address['label']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['label']).", ";
                        if (isset($applicant->applier_address['house_no']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['house_no']).", ";
                        if (isset($applicant->applier_address['landmark']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode("Landmark ".$applicant->applier_address['landmark']).", ";
                        if (isset($applicant->applier_address['locality']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['locality']).", ";
                        if (isset($applicant->applier_address['city']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['city']).", ";
                        if (isset($applicant->applier_address['state']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['state']).", ";
                        if (isset($applicant->applier_address['country']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['country']).", ";
                        if (isset($applicant->applier_address['pincode']))
                            $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['pincode']);
                    }
                    $temp['Document Verified'] = $applicant->documents_verified;
                    $temp['Date of Birth'] = $applicant->profile->dob;
                    $temp['Age Proof Document Links'] = '';

                    if (count($applicant->document_meta)) {
                        foreach ($applicant->document_meta as $document_meta_key => $document_meta) {
                            if (strlen($document_meta->original_photo)) {
                                if ($document_meta_key == 0) {
                                    $temp['Age Proof Document Links'] .= $document_meta->original_photo;
                                } else {
                                    $temp['Age Proof Document Links'] .= ", ".$document_meta->original_photo;
                                }
                            }
                        }
                    }
                }
            }

            if (!isset($temp["Delivery Address"])) {
               if (isset($collaborate->mandatory_fields) && count($collaborate->mandatory_fields)) {
                    foreach ($collaborate->mandatory_fields as $key => $mandatory_field) {
                        if ($mandatory_field->field == "address" && $mandatory_field->name  == "Delivery address") {
                            $temp['Delivery Address'] = '';
                            if($applicant->applier_address != (object)null){
                                if (isset($applicant->applier_address['label']))
                                    $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['label']).", ";
                                if (isset($applicant->applier_address['house_no']))
                                    $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['house_no']).", ";
                                if (isset($applicant->applier_address['landmark']))
                                    $temp['Delivery Address'] .= htmlspecialchars_decode("Landmark ".$applicant->applier_address['landmark']).", ";
                                if (isset($applicant->applier_address['locality']))
                                    $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['locality']).", ";
                                if (isset($applicant->applier_address['city']))
                                    $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['city']).", ";
                                if (isset($applicant->applier_address['state']))
                                    $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['state']).", ";
                                if (isset($applicant->applier_address['country']))
                                    $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['country']).", ";
                                if (isset($applicant->applier_address['pincode']))
                                    $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['pincode']);
                       
                            }
                        }
                    }
                }
            }
            
            $getBatchDetails = Collaborate\Review::where("collaborate_id","=",$collaborateId)->where("profile_id","=",$applicant->profile->id)->whereIn("batch_id",$batchIds)->groupBy("batch_id")->get();
            $pluckbatch = $getBatchDetails->pluck("batch_id")->toArray();
            $pluckstatus = $getBatchDetails->pluck("current_status")->toArray();
            $batchAndStatusArray = array_combine($pluckbatch,$pluckstatus);
            
            foreach($batches as $batch){
                $temp[$batch->name] = (isset($batchAndStatusArray[$batch->id]) && ($batchAndStatusArray[$batch->id]==3) ? "Yes" : "No");
                
            }

            array_push($finalData, $temp);
        }


        $relativePath = "images/collaborateApplicantExcel/$collaborateId";
        $name = "collaborate-".$collaborateId."-".uniqid();
        
        $excel = Excel::create($name, function($excel) use ($name, $finalData)  {
                // Set the title
                $excel->setTitle($name);

                // Chain the setters
                $excel->setCreator('Tagtaste')
                      ->setCompany('Tagtaste');

                // Call them separately
                $excel->setDescription('A Collaborate Applicants list');

                $excel->sheet('Sheetname', function($sheet) use($finalData) {
                    $sheet->fromArray($finalData);
                    foreach ($sheet->getColumnIterator() as $row) {
                        foreach ($row->getCellIterator() as $cell) {
                            if (!is_null($cell->getValue()) && str_contains($cell->getValue(), '/@')) {
                                $cell_link = $cell->getValue();
                                $cell->getHyperlink()
                                    ->setUrl($cell_link)
                                    ->setTooltip('Click here to access profile');
                            }

                            // if (!is_null($cell->getValue()) && str_contains($cell->getValue(), '://s3')) {
                            // }
                        }
                    }
                })->store('xlsx', false, true);
            });
        $excel_save_path = storage_path("exports/".$excel->filename.".xlsx");
        $s3 = \Storage::disk('s3');
        $resp = $s3->putFile($relativePath, new File($excel_save_path), ['visibility'=>'public']);
        $this->model = \Storage::url($resp);
        unlink($excel_save_path);

        return $this->sendResponse();
    }

    public function getRejectApplicantsExport(Request $request, $collaborateId)
    {
        $collaborate = Collaborate::where('id',$collaborateId)->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }

        $page = $request->input('page');
        $q = $request->input('q');
        $filters = $request->input('filters');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model = [];
        $list = Collaborate\Applicant::where('collaborate_id',$collaborateId)//->whereNull('shortlisted_at')
        ->whereNotNull('rejected_at');

        if (!$request->user()->profile->is_premium) {
            return $this->sendError("You dont have access to this premium feature.");
        }
        
        if(isset($q) && $q != null) {
            $ids = $this->getSearchedProfile($q, $collaborateId);
            $list = $list->whereIn('id', $ids);
        }
        
        if(isset($filters) && $filters != null) {
            $profileIds = $this->getFilteredProfile($filters, $collaborateId);
            $list = $list->whereIn('profile_id',$profileIds);
        }
        if($request->sortBy != null) {
            $archived = $this->sortApplicants($request->sortBy,$list,$collaborateId);
        }

        $rejected_applicant = $list->get();
        
        // compute archived
        $finalData = array();
        foreach ($rejected_applicant as $key => $applicant) {
            $job_profile = '';
            if (isset($applicant->profile->profile_occupations)) {
                if (isset($applicant->profile->profile_occupations->toArray()['0'])) {
                    $job_profile = $applicant->profile->profile_occupations->toArray()['0']['name'];
                }
            }
            $specialization = '';
            foreach ($applicant->profile->profile_specializations as $profile_specialization_key => $profile_specialization) {
                if (isset($profile_specialization->toArray()['name'])) {
                    if ($profile_specialization_key == 0) {
                        $specialization .= $profile_specialization->toArray()['name'];
                    } else {
                        $specialization .= ", ".$profile_specialization->toArray()['name'];
                    }
                } 
            }
            $allergens = '';
            foreach ($applicant->profile->allergens as $allergens_key => $profile_allergen) {
                if (isset($profile_allergen->name)) {
                    if ($allergens_key == 0) {
                        $allergens .= $profile_allergen->name;
                    } else {
                        $allergens .= ", ".$profile_allergen->name;
                    }
                } 
            }

            $temp = array(
                "S. No" => $key+1,
                "Name" => htmlspecialchars_decode($applicant->profile->name),
                "Gender" => $applicant->profile->gender,
                "Profile link" => env('APP_URL')."/@".$applicant->profile->handle,
                "Email" => $applicant->profile->email,
                "Phone Number" => $applicant->profile->getContactDetail(),
                "Occupation" => $job_profile,
                "Specialization" => $specialization,
                "Allergens" => $allergens,
                "Hometown" => $applicant->hometown,
                "Current City" => $applicant->current_city
            );

            if ($collaborate->collaborate_type == 'collaborate') {
                if ($collaborate->is_taster_residence && !$collaborate->is_contest) {
                    $temp['Delivery Address'] = '';
                    if (isset($applicant->applier_address['label']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['label']).", ";
                    if (isset($applicant->applier_address['house_no']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['house_no']).", ";
                    if (isset($applicant->applier_address['landmark']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode("Landmark ".$applicant->applier_address['landmark']).", ";
                    if (isset($applicant->applier_address['locality']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['locality']).", ";
                    if (isset($applicant->applier_address['city']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['city']).", ";
                    if (isset($applicant->applier_address['state']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['state']).", ";
                    if (isset($applicant->applier_address['country']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['country']).", ";
                    if (isset($applicant->applier_address['pincode']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['pincode']);

                } else if (!$collaborate->is_taster_residence && $collaborate->is_contest) {
                    $submissions = $applicant->getSubmissions($applicant->id, $collaborate->id);
                    $temp['Submitted files links'] = '';
                    if (count($submissions)) {
                        foreach ($submissions as $submission_key => $submission) {
                            if (strlen($submission->file_address)) {
                                if ($submission_key == 0) {
                                    $temp['Submitted files links'] .= $submission->file_address;
                                } else {
                                    $temp['Submitted files links'] .= ", ".$submission->file_address;
                                }
                            }
                        }
                    }
                } else if ($collaborate->is_taster_residence && $collaborate->is_contest) {
                    $temp['Delivery Address'] = '';
                    if (isset($applicant->applier_address['label']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['label']).", ";
                    if (isset($applicant->applier_address['house_no']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['house_no']).", ";
                    if (isset($applicant->applier_address['landmark']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode("Landmark ".$applicant->applier_address['landmark']).", ";
                    if (isset($applicant->applier_address['locality']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['locality']).", ";
                    if (isset($applicant->applier_address['city']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['city']).", ";
                    if (isset($applicant->applier_address['state']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['state']).", ";
                    if (isset($applicant->applier_address['country']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['country']).", ";
                    if (isset($applicant->applier_address['pincode']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['pincode']);

                    $submissions = $applicant->getSubmissions($applicant->id, $collaborate->id);
                    $temp['Submitted files links'] = '';
                    if (count($submissions)) {
                        foreach ($submissions as $submission_key => $submission) {
                            if (strlen($submission->file_address)) {
                                if ($submission_key == 0) {
                                    $temp['Submitted files links'] .= $submission->file_address;
                                } else {
                                    $temp['Submitted files links'] .= ", ".$submission->file_address;
                                }
                            }
                        }
                    }
                }
            } elseif ($collaborate->collaborate_type == 'product-review') {
                if ($collaborate->is_taster_residence && !$collaborate->document_required) {
                    $temp['Delivery Address'] = '';
                    if (isset($applicant->applier_address['label']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['label']).", ";
                    if (isset($applicant->applier_address['house_no']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['house_no']).", ";
                    if (isset($applicant->applier_address['landmark']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode("Landmark ".$applicant->applier_address['landmark']).", ";
                    if (isset($applicant->applier_address['locality']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['locality']).", ";
                    if (isset($applicant->applier_address['city']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['city']).", ";
                    if (isset($applicant->applier_address['state']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['state']).", ";
                    if (isset($applicant->applier_address['country']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['country']).", ";
                    if (isset($applicant->applier_address['pincode']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['pincode']);
                } else if (!$collaborate->is_taster_residence && $collaborate->document_required) {
                    $temp['Document Verified'] = $applicant->documents_verified;
                    $temp['Date of Birth'] = $applicant->profile->dob;
                    $temp['Age Proof Document Links'] = '';

                    if (count($applicant->document_meta)) {
                        foreach ($applicant->document_meta as $document_meta_key => $document_meta) {
                            if (strlen($document_meta->original_photo)) {
                                if ($document_meta_key == 0) {
                                    $temp['Age Proof Document Links'] .= $document_meta->original_photo;
                                } else {
                                    $temp['Age Proof Document Links'] .= ", ".$document_meta->original_photo;
                                }
                            }
                        }
                    }
                } else if ($collaborate->is_taster_residence && $collaborate->document_required) {
                    $temp['Delivery Address'] = '';
                    if (isset($applicant->applier_address['label']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['label']).", ";
                    if (isset($applicant->applier_address['house_no']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['house_no']).", ";
                    if (isset($applicant->applier_address['landmark']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode("Landmark ".$applicant->applier_address['landmark']).", ";
                    if (isset($applicant->applier_address['locality']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['locality']).", ";
                    if (isset($applicant->applier_address['city']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['city']).", ";
                    if (isset($applicant->applier_address['state']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['state']).", ";
                    if (isset($applicant->applier_address['country']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['country']).", ";
                    if (isset($applicant->applier_address['pincode']))
                        $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['pincode']);

                    $temp['Document Verified'] = $applicant->documents_verified;
                    $temp['Date of Birth'] = $applicant->profile->dob;
                    $temp['Age Proof Document Links'] = '';

                    if (count($applicant->document_meta)) {
                        foreach ($applicant->document_meta as $document_meta_key => $document_meta) {
                            if (strlen($document_meta->original_photo)) {
                                if ($document_meta_key == 0) {
                                    $temp['Age Proof Document Links'] .= $document_meta->original_photo;
                                } else {
                                    $temp['Age Proof Document Links'] .= ", ".$document_meta->original_photo;
                                }
                            }
                        }
                    }
                }
            }

            if (!isset($temp["Delivery Address"])) {
               if (isset($collaborate->mandatory_fields) && count($collaborate->mandatory_fields)) {
                    foreach ($collaborate->mandatory_fields as $key => $mandatory_field) {
                        if ($mandatory_field->field == "address" && $mandatory_field->name  == "Delivery address") {
                            $temp['Delivery Address'] = '';
                            if (isset($applicant->applier_address['label']))
                                $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['label']).", ";
                            if (isset($applicant->applier_address['house_no']))
                                $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['house_no']).", ";
                            if (isset($applicant->applier_address['landmark']))
                                $temp['Delivery Address'] .= htmlspecialchars_decode("Landmark ".$applicant->applier_address['landmark']).", ";
                            if (isset($applicant->applier_address['locality']))
                                $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['locality']).", ";
                            if (isset($applicant->applier_address['city']))
                                $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['city']).", ";
                            if (isset($applicant->applier_address['state']))
                                $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['state']).", ";
                            if (isset($applicant->applier_address['country']))
                                $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['country']).", ";
                            if (isset($applicant->applier_address['pincode']))
                                $temp['Delivery Address'] .= htmlspecialchars_decode($applicant->applier_address['pincode']);
                        }
                    }
                }
            }
            
            array_push($finalData, $temp);
        }
        $relativePath = "images/collaborateRejectedApplicantExcel/$collaborateId";
        $name = "collaborate-".$collaborateId."-".uniqid();
        
        $excel = Excel::create($name, function($excel) use ($name, $finalData)  {
                // Set the title
                $excel->setTitle($name);

                // Chain the setters
                $excel->setCreator('Tagtaste')
                      ->setCompany('Tagtaste');

                // Call them separately
                $excel->setDescription('A Collaborate Rejected Applicants list');

                $excel->sheet('Sheetname', function($sheet) use($finalData) {
                    $sheet->fromArray($finalData);
                    foreach ($sheet->getColumnIterator() as $row) {
                        foreach ($row->getCellIterator() as $cell) {
                            if (!is_null($cell->getValue()) && str_contains($cell->getValue(), '/@')) {
                                $cell_link = $cell->getValue();
                                $cell->getHyperlink()
                                    ->setUrl($cell_link)
                                    ->setTooltip('Click here to access profile');
                            }
                        }
                    }
                })->store('xlsx', false, true);
            });
        $excel_save_path = storage_path("exports/".$excel->filename.".xlsx");
        $s3 = \Storage::disk('s3');
        $resp = $s3->putFile($relativePath, new File($excel_save_path), ['visibility'=>'public']);
        $this->model = \Storage::url($resp);
        unlink($excel_save_path);

        return $this->sendResponse();
    }
}
