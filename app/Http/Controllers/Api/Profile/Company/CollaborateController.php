<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Collaborate;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

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
	public function index($profileId,$companyId)
	{
		$this->model = $this->model->where('company_id',$companyId)->orderBy('created_at','desc')->paginate();
        return $this->sendResponse();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $profileId, $companyId)
	{
        $isPartOfCompany = $request->user()->isPartOfCompany($companyId);
        
        if(!$isPartOfCompany){
           $this->sendError("This company does not belong to user.");
        }
        
		$inputs = $request->all();
		$inputs['company_id'] = $companyId;
        
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
	public function show($profileId, $companyId, $id)
	{
		$this->model = $this->model->where('company_id',$companyId)->find($id);
		return $this->sendResponse();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $profileId, $companyId, $id)
	{
		$inputs = $request->all();
        $company = $request->user()->isPartOfCompany($companyId);
        
        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }
		$collaborate = $this->model->where('company_id',$company->id)->first();
		
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
	public function destroy(Request $request, $profileId, $companyId, $id)
	{
        $company = $request->user()->isPartOfCompany($companyId);
        
        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }
        
        $collaborate = $this->model->where('company_id',$companyId)->where('id',$id)->first();
        
        if($collaborate === null){
            throw new \Exception( "Could not find the specified Collaborate project.");
        }
        
        $this->model = $collaborate->delete();
        return $this->sendResponse();
	}
    
    public function approve(Request $request, $profileId, $companyId, $id)
    {
        $collaborate = $this->model->where('company_id',$companyId)->where('id',$id)->first();
        
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
    
    public function reject(Request $request, $profileId, $companyId, $id)
    {
        $collaborate = $this->model->where('company_id',$companyId)->where('id',$id)->first();
    
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