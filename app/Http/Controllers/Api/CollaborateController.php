<?php

namespace App\Http\Controllers\Api;

use App\Collaborate;
use App\CompanyUser;
use App\Events\Actions\Like;
use App\PeopleLike;
use App\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\SearchClient;
use App\Collaborate\Applicant;
use App\PaymentHelper;
use App\Traits\FilterFactory;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\File;
use App\Helper;

class CollaborateController extends Controller
{
    use FilterFactory;
	/**
	 * Variable to model
	 *
	 * @var collaborate
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(Collaborate $model)
	{
		$this->model = $model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function filters()
    {
        $this->model = \App\Filter::getFilters("collaborate");
        return $this->sendResponse();
    }

	public function index(Request $request)
	{
        $collaborations = $this->model->where('step',3)->where('state',Collaborate::$state[0]);
        if($request->q == null) {
        $collaborations = $collaborations->orderBy("created_at","desc"); 
        $isSearched = 0;
        } else {
        $collabIds = $this->searchCollabs($request->q);
                if(count($collabIds) != 0) {
                    $placeholders = implode(',',array_fill(0, count($collabIds), '?'));
                    $collaborations = $collaborations->whereIn('id',$collabIds)->orderByRaw("field(id,{$placeholders})", $collabIds);
                    $isSearched = 1;
                }
        }
        $filters = $request->input('filters');
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        
        if(!empty($filters)){
            $this->model = [];
            $collaborations = \App\Filter\Collaborate::getModelIds($filters,$skip,$take);
            if($isSearched)
            $collaborations = \App\Collaborate::whereIn('id',$collaborations)->whereIn('id',$collabIds)->orderByRaw("field(id,{$placeholders})", $collabIds)->get();
            else
            $collaborations = \App\Collaborate::whereIn('id',$collaborations)->get();
            $profileId = $request->user()->profile->id;
            $this->model["data"]=[];
            foreach($collaborations as $collaboration){
                $meta = $collaboration->getMetaFor($profileId);
                $this->model['data'][] = ['collaboration'=>$collaboration,'meta'=>$meta];
            }
            
            $this->model['count'] = $collaborations->count();
            return $this->sendResponse();
        }
        $this->model = [];
        $this->model["data"]=[];
        $this->model['count'] = $collaborations->count();
        $collaborations = $collaborations->skip($skip)->take($take)->get();
        
        $profileId = $request->user()->profile->id;
        foreach($collaborations as $collaboration){
		    $meta = $collaboration->getMetaFor($profileId);
            $this->model['data'][] = ['collaboration'=>$collaboration,'meta'=>$meta];
        }
        return $this->sendResponse();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{
        $collaboration = $this->model->where('id',$id)->where('state','!=',Collaborate::$state[1])->first();
        if ($collaboration === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }

        if (isset($collaboration->description) && !is_null($collaboration->description)) {
            $collaboration->description = strip_tags($collaboration->description);    
        }

        $profileId = $request->user()->profile->id;
        if($collaboration->state == 'Active' || $collaboration->state == 'Close' || $collaboration->state == 'Expired'){
            $meta = $collaboration->getMetaFor($profileId);
            $this->model = ['collaboration'=>$collaboration,'meta'=>$meta];
            return $this->sendResponse();
        }

        if($collaboration->company_id != null){
            $checkUser = CompanyUser::where('company_id',$collaboration->company_id)->where('profile_id',$profileId)->exists();
            if(!$checkUser){
                return $this->sendError("Invalid Collaboration Project.");
            }
        }
        else if($collaboration->profile_id != $profileId){
            return $this->sendError("Invalid Collaboration Project.");
        }


        $meta = $collaboration->getMetaFor($profileId);
        $this->model = ['collaboration'=>$collaboration,'meta'=>$meta];
        return $this->sendResponse();
		
	}
    
    public function apply(Request $request, $id)
    {
        $collaborate = $this->model->where('id',$id)->where('state','!=',Collaborate::$state[1])->first();
        if($collaborate === null){
            throw new \Exception("Invalid Collaboration project.");
        }
        // if($collaborate->is_taster_residence && !$request->has('applier_address')) {
        //     return $this->sendError('Please provide your address as it is mandatory for this application or Update your app');
        // }
        //should uncomment it for module force update
        $address = $request->has('applier_address') ? $request->applier_address : null;
        if($request->has('company_id')){
            //company wants to apply
            $companyId = $request->input('company_id');
            $checkAdmin = \App\CompanyUser::where("company_id",$companyId)->where('profile_id', $request->user()->profile->id)->exists();
    
            if(!$checkAdmin){
                throw new \Exception("User does not belong to the company");
            }
            
            $exists = $collaborate->companies()->find($companyId);
    
            if($exists !== null){
                $this->errors[] = "You have already applied on " . (new Carbon($exists->applied_on))->toFormattedDateString();
                $this->model = $exists->pivot;
                return $this->sendResponse();
            }
            $canShareNumber = $request->share_number != null ? $request->share_number: 0;
            $this->model = $collaborate->companies()->attach($companyId);
            $this->model = Applicant::where('collaborate_id',$id)->where('company_id',$companyId)
                            ->update([
                                'created_at'=>Carbon::now()->toDateTimeString(),
                                'shortlisted_at'=>Carbon::now()->toDateTimeString(),
                                //'template_values' => json_encode($request->input('fields')),
                                'message' => $request->input("message"),
                                'profile_id' => $request->user()->profile->id,
                                'share_number' => $canShareNumber,
                                'applier_address' => $address
                            ]);

            $company = Redis::get('company:small:' . $companyId);
            $company = json_decode($company);
            if(isset($collaborate->company_id) && (!is_null($collaborate->company_id)))
            {
                $profileIds = CompanyUser::where('company_id',$collaborate->company_id)->get()->pluck('profile_id');
                foreach ($profileIds as $profileId)
                {
                    $collaborate->profile_id = $profileId;
                    event(new \App\Events\Actions\Apply($collaborate,$request->user()->profile,$request->input("message",""),null,null, $company));
                }
            }
            else
            {
                event(new \App\Events\Actions\Apply($collaborate,$request->user()->profile,$request->input("message",""),null,null, $company));
            }

        }
        elseif($request->has('profile_id')){
            //individual wants to apply
            $profileId = $request->user()->profile->id;
            $exists = $collaborate->profiles()->find($profileId);
            
            if($exists !== null){
                $this->errors[] = "You have already applied on " . (new Carbon($exists->applied_on))->toFormattedDateString();
                $this->model = $exists->pivot;
                return $this->sendResponse();
            }
            $profile = \App\Recipe\Profile::where('id',$profileId)->first();
            $canShareNumber = $request->share_number != null ? $request->share_number: 0;
            $terms_verified = null;
            $document_meta = null;
            $documents_verified = null;
            if ($collaborate->document_required) {
                $doc = \DB::table('profile_documents')->where('profile_id',$profileId)->first();
                if (is_null($doc)) {
                    return $this->sendError("please upload document");
                } else {
                    $document_meta = $doc->document_meta;
                    $documents_verified = $doc->is_verified;
                }
            }

            $dob = isset($profile->dob) ? date("Y-m-d", strtotime($profile->dob)) : null;
            $this->model = $collaborate->profiles()->attach($profileId);
            $this->model = $collaborate->profiles()
                ->updateExistingPivot($profileId,
                    [
                        'created_at'=>Carbon::now()->toDateTimeString(),
                        //'template_values' => json_encode($request->input('fields')),
                        'message' => $request->input("message"),
                        'shortlisted_at'=>Carbon::now()->toDateTimeString(),
                        'share_number' => $canShareNumber,
                        'applier_address' => $address,
                        'gender'=>$profile->gender,
                        'age_group'=>$profile->ageRange,
                        'hometown'=>$profile->hometown,
                        'current_city'=>$profile->city,
                        'terms_verified'=>$terms_verified,
                        'document_meta'=>$document_meta,
                        'documents_verified'=>$documents_verified,
                        'dob' => $dob,
                        'generation' => Helper::getGeneration($profile->dob)
                    ]);

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
        }
        Redis::hIncrBy("meta:collaborate:$id","applicationCount",1);
        if($collaborate->is_contest && $request->file != null) {
            $loggedInProfileId = $request->user()->profile->id;
            $applicant = Applicant::where('collaborate_id',$id)->where('profile_id',$loggedInProfileId)->whereNull('rejected_at');
            $clientSubmissionCount = Applicant::countSubmissions($applicant->first()->id,$id);
            $clientSubmissionCount += count($request->file);
                if($clientSubmissionCount > $collaborate->first()->max_submissions){
                    return $this->sendError('Invalid Number Of Submissions');
                }
            $applicantId = $applicant->first()->id;
            $this->storeContestDocs($request->file, $applicantId);
        }
        return $this->sendResponse();
    }

    public function addAddress(Request $request,$id)
    {
        //$applierAddress = $request->has('applier_address') ? $request->applier_address : null;
        $applicant = Applicant::where('profile_id',$request->user()->profile->id)
                                ->where('collaborate_id',$id);
        $input = $request->except(['_method','_token']);
        if($applicant->exists()) {
            $this->model = $applicant->update($input);
        }
        return $this->sendResponse();
    }
    
    public function like(Request $request, $id)
    {
        
        $collaborate = Collaborate::find($id);
        if(!$collaborate){
            return $this->sendError("Collaboration not found");
        }
        $this->model = [];
        $profileId = $request->user()->profile->id;
        $key = "meta:collaborate:likes:$id";
        $like = Redis::sIsMember($key,$profileId);
        if($like){
            \DB::table("collaboration_likes")
                ->where("collaboration_id",$id)->where('profile_id',$profileId)
                ->delete();
            Redis::sRem($key,$profileId);
            $this->model['likeCount'] = Redis::sCard($key);
            $this->model['liked'] = false;

            $peopleLike = new PeopleLike();
            $this->model['peopleLiked'] = $peopleLike->peopleLike($id, "collaborate",request()->user()->profile->id);

            return $this->sendResponse();
        }
        
        event(new Like($collaborate,$request->user()->profile));
        \DB::table("collaboration_likes")->insert(["collaboration_id"=>$id,'profile_id'=>$profileId]);
        Redis::sAdd($key,$profileId);
        $this->model['likeCount'] = Redis::sCard($key);
        $this->model['liked'] = true;

        $peopleLike = new PeopleLike();
        $this->model['peopleLiked'] = $peopleLike->peopleLike($id, "collaborate",request()->user()->profile->id);

        return $this->sendResponse();
        
    }

    public function shortlist(Request $request, $id)
    {
        $collaborate = Collaborate::find($id);

        if(!$collaborate){
            return $this->sendError("Collaboration not found");
        }
        $profileId = $request->user()->profile->id;
        $shortlist = \DB::table("collaborate_shortlist")->where("collaborate_id",$id)->where('profile_id',$profileId)
            ->first();
        if($shortlist){
            $unshortlist = \DB::table("collaborate_shortlist")
                ->where("collaborate_id",$id)->where('profile_id',$profileId)
                ->delete();
            $this->model = $unshortlist === 1 ? false : null;
            return $this->sendResponse();
        }
        $this->model = \DB::table("collaborate_shortlist")->insert(["collaborate_id"=>$id,'profile_id'=>$profileId]);
        return $this->sendResponse();
    }
    
    public function shortlisted(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $this->model = Collaborate::join('collaborate_shortlist','collaborate_shortlist.collaborate_id','=','collaborates.id')
            ->where('collaborate_shortlist.profile_id',$profileId)->get();
        $this->model = $this->model->makeHidden(['commentCount','likeCount','notify','template_fields','interested']);
        return $this->sendResponse();
    }
    
    public function all(Request $request)
    {
        $userId = $request->user()->id;
        $profileId = $request->user()->profile->id;
        $this->model = Collaborate::whereHas('company',function($query) use ($userId) {
            $query->where('user_id',$userId);
            })
            ->orWhere('profile_id',$profileId)
            ->orderBy('collaborates.created_at','desc')
            ->get();
        
        $profileId = $request->user()->profile->id;
        foreach($this->model as $collaboration){
            $meta = $collaboration->getMetaFor($profileId);
            $this->model['data'][] = ['collaboration'=>$collaboration,'meta'=>$meta];
        }
        
        return $this->sendResponse();
    }
    
    public function Oldapplications(Request $request, $id)
    {
        $this->model = [];

        $this->model['archived'] = \App\Collaborate\Applicant::whereNotNull('rejected_at')->where('collaborate_id',$id)->with('profile','company','collaborate')->get();
        $this->model['applications'] = \App\Collaborate\Applicant::whereNotNull('shortlisted_at')->where('collaborate_id',$id)->with('profile','company','collaborate')->get();
        return $this->sendResponse();
    }

    public function applications(Request $request, $id)
    {
        $filters = $request->input('filters');
        $q = $request->input('q');
        $collaborate = $this->model->where('id',$id)->where('state','!=',Collaborate::$state[1])->first();

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

        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $applications = \App\Collaborate\Applicant::whereNotNull('collaborate_applicants.shortlisted_at')->where('collaborate_id',$id);

        if(isset($q) && $q != null) {
            $ids = $this->getSearchedProfile($q, $id);
            $applications = $applications->whereIn('id', $ids);
        }
        
        if(isset($filters) && $filters != null) {
            $profileIds = $this->getFilteredProfile($filters, $id);
            $applications = $applications->whereIn('profile_id',$profileIds);
        }
        
        if($request->sortBy != null) {
            $applications = $this->sortApplicants($request->sortBy,$applications,$id);
        }
        
        $this->model['count'] = $applications->count();
        $applications = $applications->skip($skip)->take($take)->get();
        $this->model['application'] = $applications;
        return $this->sendResponse();

    }

    private function sortApplicants($sortBy,$applications,$collabId)
    {
        $key = array_keys($sortBy)[0];
        $value = $sortBy[$key];
        if($key == 'name') {
            $userNames = $this->getUserNames($collabId);
           $companyNames = $this->getCompanyNames($collabId);
            $users = $userNames->merge($companyNames);
            if($value == 'asc')
            $order = array_column($users->sortBy('name')->values()->all(),'id');
            else
            $order = array_column($users->sortByDesc('name')->values()->all(),'id');
            $placeholders = implode(',',array_fill(0, count($order), '?'));
            return $applications->orderByRaw("field(collaborate_applicants.id,{$placeholders})", $order)
                    ->select('collaborate_applicants.*');
        } 
        return $applications->orderBy('collaborate_applicants.created_at',$value)->select('collaborate_applicants.*');
    }
    private function getCompanyNames($id)
    {   
        return \App\Collaborate\Applicant::where('collaborate_id',$id)
        ->leftJoin('companies',function($q){
            $q->on('collaborate_applicants.company_id','=','companies.id')
            ;
        })->where('collaborate_applicants.company_id','!=',null)
        ->select('companies.name AS name','collaborate_applicants.id')
        ->get();
    }

    private function getUserNames($id)
    {   
        return \App\Collaborate\Applicant::where('collaborate_id',$id)
        ->leftJoin('profiles AS p',function($q){
            $q->on('collaborate_applicants.profile_id','=','p.id')
            ->where('collaborate_applicants.company_id','=',null);
        })->leftJoin('users','p.user_id','=','users.id')->where('users.name','!=',null)
        ->select('users.name as name','collaborate_applicants.id')
        ->get();
    }

    public function archived(Request $request, $id)
    {
        $filters = $request->input('filters');
        $q = $request->input('q');
        $collaborate = $this->model->where('id',$id)->where('state','!=',Collaborate::$state[1])->first();

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

        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
	    $archived = \App\Collaborate\Applicant::join('profiles','collaborate_applicants.profile_id','=','profiles.id')
            ->whereNotNull('collaborate_applicants.rejected_at')->whereNull('profiles.deleted_at')
            //->select('collaborate_applicants.*')
            ->where('collaborate_id',$id)->with('profile','company');
            if(isset($q) && $q != null) {
                $ids = $this->getSearchedProfile($q, $id);
                $archived = $archived->whereIn('collaborate_applicants.id', $ids);
            }
            
            if(isset($filters) && $filters != null) {
                $profileIds = $this->getFilteredProfile($filters, $id);
                $archived = $archived->whereIn('collaborate_applicants.profile_id',$profileIds);
            }
            if($request->sortBy != null) {
                $archived = $this->sortApplicants($request->sortBy,$archived,$id);
            }
        $this->model['count'] = $archived->count();
        $this->model['archived'] = $archived->skip($skip)->take($take)->get();
        return $this->sendResponse();

    }

    public function types()
    {
        $this->model = \DB::table('collaborate_types')->get();

        return $this->sendResponse();
    }

    public function uploadImageCollaborate(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $imageName = str_random("32") . ".jpg";
        $path = "images/p/$profileId/collaborate";
        $randnum = rand(10,1000);
        $response['original_photo'] = \Storage::url($request->file('image')->storeAs($path."/original/$randnum",$imageName,['visibility'=>'public']));
        //create a tiny image
        $path = $path."/tiny/$randnum" . str_random(20) . ".jpg";
        $thumbnail = \Image::make($request->file('image'))->resize(50, null,function ($constraint) {
            $constraint->aspectRatio();
        })->blur(1)->stream('jpg',70);
        \Storage::disk('s3')->put($path, (string) $thumbnail,['visibility'=>'public']);
        $response['tiny_photo'] = \Storage::url($path);
        $meta = getimagesize($request->input('image'));
        $response['meta']['width'] = $meta[0];
        $response['meta']['height'] = $meta[1];
        $response['meta']['mime'] = $meta['mime'];
        $response['meta']['size'] = null;
        $response['meta']['tiny_photo'] = $response['tiny_photo'];
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        $this->model = $response;
        return $this->sendResponse();

    }

    public function deleteImages(Request $request,$imageUrl)
    {
        $this->model = \Storage::delete($imageUrl);
        return $this->sendResponse();

    }

    public function batchesColor()
    {
        $this->model = \DB::table('collaborate_batches_color')->get();
        return $this->sendResponse();
    }

    public function userBatches(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
//        $collaborateIds = \DB::table('collaborate_batches_assign')->where('profile_id',$loggedInProfileId)->where('begin_tasting',1)
//            ->get()->pluck('collaborate_id');
        $collaborates = \App\Recipe\Collaborate::join('collaborate_batches_assign','collaborate_batches_assign.collaborate_id','=','collaborates.id')
            ->where('collaborate_batches_assign.profile_id',$loggedInProfileId)->where('collaborate_batches_assign.begin_tasting',1)
            ->where('collaborates.state', 1)->where('collaborates.account_deactivated',0)
            ->orderBy('collaborate_batches_assign.created_at','desc')->get()->toArray();
        $collaborateIds = [];
        $data = [];
        foreach ($collaborates as $key=> &$collaborate)
    {
            if(in_array($collaborate['id'],$collaborateIds))
            {
                continue;
            }
            $batchIds = Redis::sMembers("collaborate:".$collaborate['id'].":profile:$loggedInProfileId:");
            $count = count($batchIds);
            if($count)
            {
                foreach ($batchIds as &$batchId)
                {
                    $batchId = "batch:".$batchId;
                }
                $batchInfos = Redis::mGet($batchIds);
                $batches = [];
                foreach ($batchInfos as &$batchInfo)
                {
                    $batchInfo = json_decode($batchInfo);
                    $currentStatus = Redis::get("current_status:batch:$batchInfo->id:profile:".$loggedInProfileId);
                    $batch = \DB::table('collaborate_batches_assign')
                                ->where('batch_id',$batchInfo->id)
                                ->where('profile_id',$loggedInProfileId)
                                ->first();
                    $batchInfo->current_status = !is_null($currentStatus) ? (int)$currentStatus : 0;
                    $batchInfo->address_id = $batch->address_id;
                    $batchInfo->bill_verified = $batch->bill_verified;
                    $paymentOnBacth = \DB::table('payment_details')
                                            ->where('model_id',$batchInfo->collaborate_id)
                                            ->where('sub_model_id',$batchInfo->id)
                                            ->where('is_active',1)
                                            ->first();
                    $batchInfo->isPaid =  PaymentHelper::getisPaidMetaFlag($paymentOnBacth);
                    if($currentStatus != 3 && $currentStatus !=0)
                    {
                        $batches[] = $batchInfo;
                    }
                }
            }
            $collaborateIds[] = $collaborate['id'];
            $collaborate['batches'] = $count > 0 ? $batches : [];
            if( sizeof($collaborate['batches']) !=0 ){
            $data[] = $collaborate;
            }
        }
        $this->model = $data;

        return $this->sendResponse();
    }



    public function seenBatchesList(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $now = Carbon::now()->toDateTimeString();
        $this->model = \DB::table('collaborate_batches_assign')->where('profile_id',$loggedInProfileId)->update(['last_seen'=>$now]);
        return $this->sendResponse();

    }

    public function tastingMethodology()
    {
        $this->model = \DB::table('collaborate_tasting_methodology')->get();
        return $this->sendResponse();
    }

    public function profilesJobs()
    {
        $this->model = \DB::table('occupations')->get();
        return $this->sendResponse();
    }

    public function profilesSpecialization()
    {
        $this->model = \DB::table('specializations')->orderBy("order","ASC")->get();
        return $this->sendResponse();
    }

    public function globalQuestion(Request $request)
    {
        $this->model = [];
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page, 10);
        $keywords = isset($request->keywords)?$request->keywords:null;

        if($request->track_consistency != null && $request->track_consistency == 1) {
            $questionaire = \DB::table('global_questions')->where('track_consistency',1);
        } else {
            $questionaire = \DB::table('global_questions')->where('track_consistency',0);
        }

        if (!is_null($keywords)) {
            $questionaire = $questionaire->where('name','like','%'.$keywords.'%');
        }


        $this->model['count'] = $questionaire->count();
        $this->model['questionnaire'] = $questionaire
            ->orderBy('id', 'DESC')
            ->skip($skip)
            ->take($take)
            ->get();

        return $this->sendResponse();
    }

    public function globalQuestionParticular(Request $request,$id)
    {
        $this->model = [];
        $this->model['questionnaire'] = \DB::table('global_questions')->where('id',$id)->get();
        $this->model['count'] = \DB::table('global_questions')->where('id',$id)->count();
        return $this->sendResponse();
    }

    public function profilesAllergens()
    {
        $this->model = \DB::table('allergens')->get();
        return $this->sendResponse();
    }

    public function getCities(Request $request)
    {
        $this->model = \DB::table('cities')->where('is_active',1)->get();
        return $this->sendResponse();
    }

    public function addCities(Request $request)
    {
        $filename = str_random(32) . ".xlsx";
        $path = "images/city";
        $file = $request->file('file')->storeAs($path,$filename,['visibility'=>'public']);
        //$fullpath = env("STORAGE_PATH",storage_path('app/')) . $path . "/" . $filename;
        //$fullpath = \Storage::url($file);

        //load the file
        $data = [];
        try {
            $fullpath = $request->file->store('temp', 'local');
            \Excel::load("storage/app/" . $fullpath, function($reader) use (&$data){
                $data = $reader->toArray();
            })->get();
            if(empty($data)){
                return $this->sendError("Empty file uploaded.");
            }
            \Storage::disk('local')->delete($file);
        } catch (\Exception $e){
            \Log::info($e->getMessage());
            return $this->sendError($e->getMessage());

        }
        $cities = [];
        foreach ($data as $item)
        {

            foreach ($item as $datum)
            {
                if(is_null($datum))
                    break;
                if(isset($datum['selected']))
                {
                    if($datum['selected'] == 'Yes')
                        $cities[] = ['city'=>$datum['city'],'state'=>$datum['state'],'region'=>$datum['region'],'is_active'=>1];
                    else
                        $cities[] = ['city'=>$datum['city'],'state'=>$datum['state'],'region'=>$datum['region'],'is_active'=>0];
                }

            }
        }
        $this->model = \DB::table('cities')->insert($cities);
        return $this->sendResponse();
    }

    public function uploadGlobalQuestion(Request $request)
    {
        $name = $request->input('name');
        $keywords = $request->input('keywords');
        $description = $request->input('description');
        $questions = $request->input('question_json');
        $headers = $request->input("header_info");
        $data = ['name'=>$name,'keywords'=>$keywords,'description'=>$description,'question_json'=>$questions,'header_info'=>json_encode($headers,true)];
        $this->model = \DB::table('global_questions')->insert($data);
        return $this->sendResponse();
    }

    public function dynamicMandatoryFields(Request $request)
    {
        $type = $request->has('type') ? $request->type : [];
        $this->model = [];
        $fields = \DB::table('mandatory_fields')->get();
        foreach($fields as $field) {
            $is_selected = 0;
            $data = [];
            foreach($type as $t) {
                if($field->field == 'document_meta' && $t == 'document_required'){
                    $is_selected = 1;
                } else if($field->field == 'address' && $t == 'hut') {
                    $is_selected = 1;
                }
            }
            $data['id'] = $field->id;
            $data['is_selected'] = $is_selected;
            $data['name'] = $field->name;
            $data['field'] = $field->field;
            $data['is_mandatory'] = $field->is_mandatory;
            $data = (object)$data;
            $this->model[] = $data;
        }         

        return $this->sendResponse();
    }

    public function collaborationMandatoryFields(Request $request,$id)
    {
        unset($this->model);
        $this->model = [];
        $fields = \DB::table('mandatory_fields')
                        ->join('collaborate_mandatory_mapping','mandatory_fields.id','=','collaborate_mandatory_mapping.mandatory_field_id')
                        ->where('collaborate_mandatory_mapping.collaborate_id',$id)
                        ->pluck('mandatory_fields.field');
        $this->model['mandatory_fields'] = $fields;                
        $this->model['remaining_mandatory_fields'] = $request->user()->profile->getProfileCompletionAttribute($fields);
        return $this->sendResponse();

    }

    public function mandatoryField(Request $request,$type)
    {
        if($type == 'product-review')
            $this->model = $request->user()->profile->getProfileCompletionAttribute();
        else if($type == 'collaborate')
            $this->model = $request->user()->profile->getProfileCompletionAttribute();
        else
            $this->model = [];
        return $this->sendResponse();
    }

    public function uploadGlobalNestedOption(Request $request)
    {
        $filename = str_random(32) . ".xlsx";
        $path = "images/collaborate/global/nested/option";
        $file = $request->file('file')->storeAs($path,$filename,['visibility'=>'public']);
        //$fullpath = env("STORAGE_PATH",storage_path('app/')) . $path . "/" . $filename;
        //$fullpath = \Storage::url($file);

        //load the file
        $data = [];
        try {
            $fullpath = $request->file->store('temp', 'local');
            \Excel::load("storage/app/" . $fullpath, function($reader) use (&$data){
                $data = $reader->toArray();
            })->get();
            if(empty($data)){
                return $this->sendError("Empty file uploaded.");
            }
            \Storage::disk('local')->delete($file);
        } catch (\Exception $e){
            \Log::info($e->getMessage());
            return $this->sendError($e->getMessage());

        }
        $questions = [];
        $extra = [];
        foreach ($data as $item)
        {

            foreach ($item as $datum)
            {
                if(is_null($datum['parent_id'])||is_null($datum['categories']))
                    break;
                $extra[] = $datum;
                $parentId = $datum['parent_id'] == 0 ? null : $datum['parent_id'];
                $active = isset($datum['is_active']) ? $datum['is_active'] : 1;
                $description = isset($datum['description']) ? $datum['description'] : null;
                $questions[] = ["s_no"=>$datum['sequence_id'],'parent_id'=>$parentId,'value'=>$datum['categories'],'type'=>'AROMA','is_active'=>$active,'description'=>$description];
            }
        }
        $data = [];
        foreach ($questions as $item)
        {
            $data[] = ['type'=>'AROMA','s_no'=>$item['s_no'],'parent_id'=>$item['parent_id'],'value'=>$item['value'],'is_active'=>$item['is_active'],'description'=>$item['description']];
        }
        $this->model = \DB::table('global_nested_option')->insert($data);
        return $this->sendResponse();
    }

    public function collaborateCloseReason()
    {
        $data[] = ['id'=>1,'reason'=>'Completed'];
        $data[] = ['id'=>2,'reason'=>'Not enough responses'];
        $data[] = ['id'=>3,'reason'=>'Other'];
        $this->model = $data;
        return $this->sendResponse();
    }

    public function uploadBrandLogo(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $imageName = str_random("32") . ".jpg";
        $path = "images/p/$profileId/collaborate";
        $randnum = rand(10,1000);
        //create a tiny image
        $path = $path."/brand_logo/$randnum";
        $thumbnail = \Image::make($request->file('image'))->resize(320, null,function ($constraint) {
            $constraint->aspectRatio();
        })->blur(1)->stream('jpg',70);
        \Storage::disk('s3')->put($path, (string) $thumbnail,['visibility'=>'public']);
        $response = \Storage::url($path);
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        $this->model = $response;
        return $this->sendResponse();

    }

    public function searchCollabs($query)
    {
        $params = [
            'index' => "api",
            'body' => [
                "from" => 0, "size" => 1000,
                'query' => [
                    'query_string' => [
                        'query' => $query.'*',
                        'fields'=>['title^3','keywords^2']

                    ]
                ],
                'suggest' => [
                    'my-suggestion-1'=> [
                            'text'=> $query,
                            'term'=> [
                                 'field'=> 'name'
                            ]
                    ],
                    'my-suggestion-2'=> [
                            'text'=> $query,
                            'term'=> [
                                 'field'=> 'title'
                            ]
                    ]
                ]

            ]
        ];

            $params['type'] = 'collaborate';
        $client = SearchClient::get();

        $response = $client->search($params);
        if($response['hits']['total'] == 0) {
            $suggestionByElastic = $this->elasticSuggestion($response,'collaborate');
            $response = $suggestionByElastic!=null ? $suggestionByElastic : $response;   
        }
        $this->model = [];
        //return $response;
        //$page = $request->input('page');
        //list($skip,$take) = \App\Strategies\Paginator::paginate($page);

        if($response['hits']['total'] > 0){
            $hits = collect($response['hits']['hits']);
            $hits = $hits->groupBy("_type");

            foreach($hits as $name => $hit){
                $ids = $hit->pluck('_id')->toArray();
            }
            return $ids;
        }
            return [];
    }
    public function elasticSuggestion($response,$type) {
        $query = "";
            $elasticSuggestions = $response["suggest"];
            if(isset($elasticSuggestions["my-suggestion-1"][0]["options"][0]["text"]) && $elasticSuggestions["my-suggestion-1"][0]["options"][0]["text"] != "") {
                    $query = $query.($elasticSuggestions["my-suggestion-1"][0]["options"][0]["text"])." ";
                    if(isset($elasticSuggestions["my-suggestion-2"][0]["options"][0]["text"]) &&  $elasticSuggestions["my-suggestion-2"][0]["options"][0]["text"] != "") {
                    
                        $query= $query."OR ".$elasticSuggestions["my-suggestion-2"][0]["options"][0]["text"];
                    }
                } else if(isset($elasticSuggestions["my-suggestion-2"][0]["options"][0]["text"]) && $elasticSuggestions["my-suggestion-2"][0]["options"][0]["text"] != "") {
                    
                    $query = $query.$elasticSuggestions["my-suggestion-2"][0]["options"][0]["text"];
                }
                if($query != "") {
                    $params = [
                        'index' => "api",
                        'body' => [
                            'query' => [
                                'query_string' => [
                                    'query' => $query,
                                    'fields'=>['name^3','title^3','brand_name^2','company_name^2','handle^2','keywords^2','productCategory','subCategory']
                                ]
                            ],
                        ]
                    ];

                    if($type){
                        $params['type'] = $type;
                    }
                    $client = SearchClient::get();

                    $response = $client->search($params);
                    return $response;    
                } else {
                    return null;
                }
    }
    public function contestSubmission(Request $request, $collaborateId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $companyId = $request->company_id;
        $collaborate = $this->model->where('id',$collaborateId)->where('is_contest',1);
        $applicant = Applicant::where('collaborate_id',$collaborateId)->where('profile_id',$loggedInProfileId)->whereNull('rejected_at'); 
        if(!$collaborate->exists() || !$applicant->exists()) {
            return $this->sendError('Invalid Collaboration Id given or applicant');
        }
        $clientSubmissionCount = Applicant::countSubmissions($applicant->first()->id,$collaborateId);
        $clientSubmissionCount += count($request->file);
        if($clientSubmissionCount > $collaborate->first()->max_submissions){
            return $this->sendError('Invalid Number Of Submissions');
         }
         $applicantId = $applicant->first()->id;
         $this->model = $this->storeContestDocs($request->file, $applicantId);
         $company = $applicant->first()->company_id != null ? \App\Company::where('id',$applicant->first()->company_id)->first() : null;
         $this->triggerDocSubmissions($collaborate->first(),$request->file,$request->user()->profile,$company);
        return $this->sendResponse();
    }

     public function getSubmissions(Request $request, $collaborateId) 
    {
        $loggedInProfileId = $request->user()->profile->id;
        $collaborate = $this->model->where('id',$collaborateId)->where('is_contest',1);
        $applicant = Applicant::where('collaborate_id',$collaborateId)->where('profile_id',$loggedInProfileId)->whereNull('rejected_at');
        if(!$collaborate->exists() || !$applicant->exists()) {
            return $this->sendError('Invalid Collaboration Id given or applicant');
        }

         $this->model = Applicant::getSubmissions($applicant->first()->id, $collaborateId);
        return $this->sendResponse();
    }

    protected function storeContestDocs($files, $applicantId)
    {
        $this->removeRejectedDocs($applicantId);
        $mapTable = [];
         foreach($files as $url) {
             $submissionId = \DB::table('submissions')
                                ->insertGetId(['file_address'=>$url['url'],'original_name'=>$url['original_name']]);
            $mapTable[] = ['applicant_id'=>$applicantId,'submission_id'=>$submissionId];
         }
         return \DB::table('contest_submissions')
                            ->insert($mapTable);
    }
    protected function removeRejectedDocs($applicantId)
    {
        $query = 'DELETE contest_submissions,submissions 
                    from contest_submissions 
                    join submissions 
                        on submissions.id = contest_submissions.submission_id 
                    where submissions.status = 2 
                        and applicant_id = '.$applicantId;
        \DB::delete($query);
    }
    protected function triggerDocSubmissions($collaborate,$files,$profile,$company)
    {
        if(isset($collaborate->company_id) && (!is_null($collaborate->company_id)))
            {
                $profileIds = CompanyUser::where('company_id',$collaborate->company_id)->get()->pluck('profile_id');
                foreach ($profileIds as $profileId)
                {
                    $collaborate->profile_id = $profileId;
                    event(new \App\Events\DocSubmissionEvent($profileId,$collaborate,$profile,$company,$files));
                }
            }
            else
            {
                event(new \App\Events\DocSubmissionEvent($collaborate->profile_id,$collaborate,$profile,$company,$files));
            }
    }

    public function applicantFilters(Request $request, $collaborateId)
    {
        $filters = $request->input('filter');
        $this->model = $this->getFilters($filters, $collaborateId);
        return $this->sendResponse();
    }

    public function archivedExport(Request $request, $id)
    {
        $filters = $request->input('filters');
        $q = $request->input('q');
        $collaborate = $this->model->where('id',$id)->where('state','!=',Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;

        if (!$request->user()->profile->is_premium) {
            return $this->sendError("You dont have access to this premium feature.");
        }
        
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

        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $archived = \App\Collaborate\Applicant::join('profiles','collaborate_applicants.profile_id','=','profiles.id')
            ->whereNotNull('collaborate_applicants.rejected_at')->whereNull('profiles.deleted_at')
            //->select('collaborate_applicants.*')
            ->where('collaborate_id',$id)->with('profile','company');
            if(isset($q) && $q != null) {
                $ids = $this->getSearchedProfile($q, $id);
                $archived = $archived->whereIn('id', $ids);
            }
            
            if(isset($filters) && $filters != null) {
                $profileIds = $this->getFilteredProfile($filters, $id);
                $archived = $archived->whereIn('profile_id',$profileIds);
            }
            if($request->sortBy != null) {
                $archived = $this->sortApplicants($request->sortBy,$archived,$id);
            }

        $archived_applicant = $archived->get();
        
        // compute archived
        $finalData = array();
        foreach ($archived_applicant as $key => $applicant) {
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
        $relativePath = "images/collaborateArchivedApplicantExcel/$id";
        $name = "collaborate-".$id."-".uniqid();
        
        $excel = Excel::create($name, function($excel) use ($name, $finalData)  {
                // Set the title
                $excel->setTitle($name);

                // Chain the setters
                $excel->setCreator('Tagtaste')
                      ->setCompany('Tagtaste');

                // Call them separately
                $excel->setDescription('A Collaborate Archived Applicants list');

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

    public function getProductReviewType(Request $request)
    {
        $this->model = \DB::table('collaborate_product_review_type')
                            ->get();
        return $this->sendResponse();
    }
}