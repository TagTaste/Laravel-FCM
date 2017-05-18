<?php

namespace App\Http\Controllers\Api\Profile;

use App\Collaborate;
use App\Field;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Profile;
use App\Company;

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
	public function index($profileId)
	{
		$this->model = $this->model->where('profile_id',$profileId)->paginate();
        return $this->sendResponse();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $profileId)
	{
	    $profileId = $request->user()->profile->id;
		$inputs = $request->all();
		$inputs['profile_id'] = $profileId;
		$inputs['expires_on'] = Carbon::now()->addMonth()->toDateTimeString();
		$fields = $request->has("fields") ? $request->input('fields') : [];
		
		if(!empty($fields)){
		    unset($inputs['fields']);
        }
		$this->model = $this->model->create($inputs);
  
		$fields = Field::select('id')->whereIn('id',$fields)->get();
		
		if($fields->count()){
            $this->model->fields()->sync($fields->pluck('id')->toArray());
        }
        $this->model = $this->model->fresh();
		return $this->sendResponse();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($profileId, $id)
	{
		$this->model = $this->model->where('profile_id',$profileId)->find($id);
		if($this->model === null){
		   throw new \Exception("Invalid Collaboration Project.");
        }
		return $this->sendResponse();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $profileId, $id)
	{
		$inputs = $request->all();
        $profileId = $request->user()->profile->id;
        
		$collaborate = $this->model->where('profile_id',$profileId)->first();
		
		if($collaborate === null){
		    throw new \Exception("Could not find the specified Collaborate project.");
        }
        
        $fields = $request->has("fields") ? $request->input('fields') : [];
        
        if(!empty($fields)){
            unset($inputs['fields']);
        }
        
        if($fields->count()){
            $this->model->fields()->sync($fields->pluck('id')->toArray());
        }
        
        $this->model = $collaborate->update($inputs);
        return $this->sendResponse();
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $profileId, $id)
	{
        $profileId = $request->user()->profile->id;
        
        $collaborate = $this->model->where('profile_id',$profileId)->where('id',$id)->first();
        
        if($collaborate === null){
            throw new \Exception("Could not find the specified Collaborate project.");
        }
        
        $this->model = $collaborate->delete();
        return $this->sendResponse();
	}
    
    public function approve(Request $request, $profileId, $id)
    {
        $collaborate = $this->model->where('profile_id',$profileId)->where('id',$id)->first();
        
        if($collaborate === null){
            throw new \Exception("Invalid Collaboration project.");
        }
        
        if($request->has('company_id')){
            $companyId = $request->input('company_id');
            $company =  Company::find($companyId);
            if(!$company){
                throw new \Exception("Company does not exist.");
            }
            
            return $collaborate->approveCompany($company);
        }
        
        if($request->has('profile_id')){
            $inputProfileId = $request->input('profile_id');
            $profile =  Profile::find($inputProfileId);
            if(!$profile){
                throw new \Exception("Profile does not exist.");
            }
            
            return $collaborate->approveProfile($profile);
        }
    }
    
    public function reject(Request $request, $profileId, $id)
    {
        $collaborate = $this->model->where('profile_id',$profileId)->where('id',$id)->first();
    
        if($collaborate === null){
            throw new \Exception("Invalid Collaboration project.");
        }
    
        if($request->has('company_id')){
            $companyId = $request->input('company_id');
            $company =  Company::find($companyId);
            if(!$company){
                throw new \Exception("Company does not exist.");
            }
        
            return $collaborate->rejectCompany($company);
        }
    
        if($request->has('profile_id')){
            $inputProfileId = $request->input('profile_id');
            $profile =  Profile::find($inputProfileId);
            if(!$profile){
                throw new \Exception("Profile does not exist.");
            }
        
            return $collaborate->rejectProfile($profile);
        }
    }
}