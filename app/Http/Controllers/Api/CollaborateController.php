<?php

namespace App\Http\Controllers\Api;

use App\Collaborate;
use App\Company;
use App\Profile;
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
	public function index(Request $request)
	{
		$collaborations = $this->model->orderBy("created_at","desc")->paginate();
		$this->model = [];
		$profileId = $request->user()->profile->id;
        $allShortlist = \DB::table("collaborate_shortlist")->where('profile_id',$profileId)->get();
        foreach($collaborations as $collaboration){
		    $meta = $collaboration->getMetaFor($profileId);
            $meta['isShortlisted']=$collaboration->isShortList($allShortlist,$collaboration->id);
            $this->model[] = ['collaboration'=>$collaboration,'meta'=>$meta];
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
		$collaboration = $this->model->findOrFail($id);
		$profileId = $request->user()->profile->id;
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
            $company =  $request->user()->companies()->find($companyId);
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
            $collaborate->companies()
                ->updateExistingPivot($companyId,
                    [
                        'applied_on'=>Carbon::now()->toDateTimeString(),
                        'template_values' => json_encode($request->input('fields'))
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
            $collaborate->profiles()
                ->updateExistingPivot($profileId,
                    [
                        'applied_on'=>Carbon::now()->toDateTimeString(),
                        'template_values' => json_encode($request->input('fields'))
                    ]);
    
        }
        return $this->sendResponse();
    }
    
    public function like(Request $request, $id)
    {
        
        $collaborate = Collaborate::find($id);

        if(!$collaborate){
            return $this->sendError("Collaboration not found");
        }
        
        $profileId = $request->user()->profile->id;
        $like = \DB::table("collaboration_likes")->where("collaboration_id",$id)->where('profile_id',$profileId)
            ->first();
        if($like){
            $unliked = \DB::table("collaboration_likes")
                ->where("collaboration_id",$id)->where('profile_id',$profileId)
                ->delete();
            //if unliked, return false;
            //yes, counter-intuitive.
            $this->model = $unliked === 1 ? false : null;
            return $this->sendResponse();
        }
        
        $this->model = \DB::table("collaboration_likes")->insert(["collaboration_id"=>$id,'profile_id'=>$profileId]);
        return $this->sendResponse();
        
    }

    public function shortlist(Request $request, $id,$profileId)
    {
        $collaborate = Collaborate::find($id);

        if(!$collaborate){
            return $this->sendError("Collaboration not found");
        }

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
    
}