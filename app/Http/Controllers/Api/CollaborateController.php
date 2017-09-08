<?php

namespace App\Http\Controllers\Api;

use App\Collaborate;
use App\CollaborateCategory;
use App\Events\Actions\Like;
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
        $filters  = [];

        $filters['location'] = \App\Filter\Collaborate::select('location as value')->groupBy('location')->where('location','!=','null')->get();
        $keywords = \App\Filter\Collaborate::select('keywords as value')->groupBy('keywords')->where('keywords','!=','null')->get();
        $filters['keywords'] = [];
       if($keywords->count()){
            foreach($keywords as $keyword){
                if(empty($keyword->value)){
                    continue;
                }
        
                $filters['keywords'][] = explode(",",$keyword->value);
            }
            if(count($filters['keywords'])){
               $filters['keywords'] = array_merge(...$filters['keywords']);
            }
        }
        
        $filters['type'] = \App\CollaborateTemplate::select('id as key','name as value')->get();
        $filters['categories'] = CollaborateCategory::select("id as value","name as value")->with('children')->get();
        $this->model = $filters;
        return $this->sendResponse();
    }

	public function index(Request $request)
	{
		$collaborations = $this->model->whereNull('deleted_at')->orderBy("created_at","desc");
        $filters = $request->input('filters');
       
        if (!empty($filters['location'])) {
            $collaborations = $collaborations->whereIn('location', $filters['location']);
        }
        if (!empty($filters['keywords'])) {
            $keywords = $filters['keywords'];
            
            $collaborations = $collaborations->where(function($query) use($keywords){
                foreach($keywords as $keyword){
                    $query->orWhere('keywords','like',"%" . $keyword . "%");
                }
            });
        }
        if(!empty($filters['type']))
        {
            $collaborations = $collaborations->whereIn('template_id',$filters['type']);
        }
        
        if(!empty($filters['categories'])){
            $collaborations = $collaborations->whereIn('category_id',$filters['categories']);
        }
        $this->model = [];
        $this->model["count"] = $collaborations->count();
        $this->model["data"]=[];
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
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
		$collaboration = $this->model->whereNull('deleted_at')->find($id);
		$profileId = $request->user()->profile->id;
        if ($collaboration === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $meta = $collaboration->getMetaFor($profileId);
        $this->model = ['collaboration'=>$collaboration,'meta'=>$meta];
		return $this->sendResponse();
		
	}
    
    public function apply(Request $request, $id)
    {
        $collaborate = $this->model->where('id',$id)->first();
        
        if($collaborate === null){
            throw new \Exception("Invalid Collaboration project.");
        }
        
        if($request->has('company_id')){
            //company wants to apply
            $companyId = $request->input('company_id');
            $company =  \App\Company::where('user_id',$request->user()->id)->where('id',$companyId)->first();
            if(!$company){
                throw new \Exception("Company does not belong to the user.");
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
                        'applied_on'=>Carbon::now()->toDateTimeString(),
                        'template_values' => json_encode($request->input('fields')),
                        'message' => $request->input("message")
                    ]);
        }
        
        if($request->has('profile_id')){
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
                        'applied_on'=>Carbon::now()->toDateTimeString(),
                        'template_values' => json_encode($request->input('fields')),
                        'message' => $request->input("message")
                    ]);
    
        }
        \Redis::hIncrBy("collaborate:$id","applicationCount",1);
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
        $like = \DB::table("collaboration_likes")->where("collaboration_id",$id)->where('profile_id',$profileId)
            ->first();
        if($like){
            $unliked = \DB::table("collaboration_likes")
                ->where("collaboration_id",$id)->where('profile_id',$profileId)
                ->delete();
            //if unliked, return false;
            //yes, counter-intuitive.
            $this->model['likeCount'] = \Redis::hIncrBy("collaborate:" . $id . ":meta", "like", -1);
            $this->model['liked'] = $unliked === 1 ? false : null;
            return $this->sendResponse();
        }
        
        event(new Like($collaborate,$request->user()->profile));
        $data = \DB::table("collaboration_likes")->insert(["collaboration_id"=>$id,'profile_id'=>$profileId]);
        $this->model['likeCount'] = \Redis::hIncrBy("collaborate:" . $id . ":meta", "like", 1);
        $this->model['liked'] = true;
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
        
        return $this->sendResponse();
    }

    public function applications(Request $request, $id)
    {
        $this->model = [];
        
        $this->model['archived'] = \App\Collaboration\Collaborator::whereNotNull('archived_at')->where('collaborate_id',$id)->with('profile','collaborate')->get();
        $this->model['applications'] = \App\Collaboration\Collaborator::whereNull('archived_at')->where('collaborate_id',$id)->with('profile','collaborate')->get();

        return $this->sendResponse();

    }
    
}