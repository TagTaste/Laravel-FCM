<?php

namespace App\Http\Controllers\Api;

use App\Collaborate;
use App\CompanyUser;
use App\Events\Actions\Like;
use App\PeopleLike;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CollaborateController extends Controller
{
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
		$collaborations = $this->model->where('state',1)->whereNull('deleted_at')->orderBy("created_at","desc");
        $filters = $request->input('filters');
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        
        if(!empty($filters)){
            $this->model = [];
            $collaborations = \App\Filter\Collaborate::getModelIds($filters,$skip,$take);
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
        $this->model["count"] = $collaborations->count();
        $this->model["data"]=[];
       
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

        $profileId = $request->user()->profile->id;
        if($collaboration->state == 'Active'){
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
            
            $this->model = $collaborate->companies()->attach($companyId);
            $this->model = $collaborate->companies()
                ->updateExistingPivot($companyId,
                    [
                        'created_at'=>Carbon::now()->toDateTimeString(),
                        //'template_values' => json_encode($request->input('fields')),
                        'message' => $request->input("message"),
                        'profile_id' => $request->input('profile_id')
                    ]);

            $company = \Redis::get('company:small:' . $companyId);
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
            
            $this->model = $collaborate->profiles()->attach($profileId);
            $this->model = $collaborate->profiles()
                ->updateExistingPivot($profileId,
                    [
                        'created_at'=>Carbon::now()->toDateTimeString(),
                        //'template_values' => json_encode($request->input('fields')),
                        'message' => $request->input("message")
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
        \Redis::hIncrBy("meta:collaborate:$id","applicationCount",1);
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
        $like = \Redis::sIsMember($key,$profileId);
        if($like){
            \DB::table("collaboration_likes")
                ->where("collaboration_id",$id)->where('profile_id',$profileId)
                ->delete();
            \Redis::sRem($key,$profileId);
            $this->model['likeCount'] = \Redis::sCard($key);
            $this->model['liked'] = false;

            $peopleLike = new PeopleLike();
            $this->model['peopleLiked'] = $peopleLike->peopleLike($id, "collaborate",request()->user()->profile->id);

            return $this->sendResponse();
        }
        
        event(new Like($collaborate,$request->user()->profile));
        \DB::table("collaboration_likes")->insert(["collaboration_id"=>$id,'profile_id'=>$profileId]);
        \Redis::sAdd($key,$profileId);
        $this->model['likeCount'] = \Redis::sCard($key);
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
        $this->model['count'] = $applications->count();
        $this->model['application'] = $applications->skip($skip)->take($take)->get();
        return $this->sendResponse();

    }

    public function archived(Request $request, $id)
    {
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
            ->whereNotNull('collaborate_applicants.archived_at')->whereNull('profiles.deleted_at')
            ->where('collaborate_id',$id)->with('profile','collaborate','company');
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
        $relativePath = "images/p/$profileId/collaborate";
        $this->model = \Storage::url($request->file("image")->storeAs($relativePath, $imageName,['visibility'=>'public']));
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
        $collaborateIds = \DB::table('collaborate_batches_assign')->where('profile_id',$loggedInProfileId)->where('begin_tasting',1)
            ->get()->pluck('collaborate_id');
        $collaborates = \App\Recipe\Collaborate::whereIn('id',$collaborateIds)->get()->toArray();
        foreach ($collaborates as &$collaborate)
        {
            $batchIds = \Redis::sMembers("collaborate:".$collaborate['id'].":profile:$loggedInProfileId:");
            $count = count($batchIds);
            if($count)
            {
                foreach ($batchIds as &$batchId)
                {
                    $batchId = "batch:".$batchId;
                }
                $batchInfos = \Redis::mGet($batchIds);
                foreach ($batchInfos as &$batchInfo)
                {
                    $batchInfo = json_decode($batchInfo);
                    $currentStatus = \Redis::get("current_status:batch:$batchInfo->id:profile:".$loggedInProfileId);
                    $batchInfo->current_status = !is_null($currentStatus) ? (int)$currentStatus : 0;
                }
            }
            $collaborate['batches'] = $count > 0 ? $batchInfos : [];
        }
        $this->model = $collaborates;

        return $this->sendResponse();
    }



    public function seenBatchesList(Request $request)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $now = Carbon::now()->toDateTimeString();
        $this->model = \DB::table('collaborate_batches_assign')->where('profile_id',$loggedInProfileId)->update(['last_seen'=>$now]);
        return $this->sendResponse();

    }
}
