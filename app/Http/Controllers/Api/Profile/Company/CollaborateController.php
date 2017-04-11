<?php

namespace App\Http\Controllers\Api\Profile;

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
		$this->model = $this->model->where('company_id',$companyId)->paginate();
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
        $company = $request->user()->companies()->where('id',$companyId)->first();
        
        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }
		$inputs = $request->all();
		$inputs['company_id'] = $company->id;
		$this->model = $this->model->create($inputs);

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
        $company = $request->user()->companies()->where('id',$companyId)->first();
        
        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }
		$collaborate = $this->model->where('company_id',$company->id)->first();
		
		if($collaborate === null){
		    $this->errors[] = "Could not find the specified collaborate project.";
		    return $this->sendResponse();
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
        $company = $request->user()->companies()->where('id',$companyId)->first();
        
        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }
        
        $collaborate = $this->model->where('company_id',$companyId)->first();
        
        if($collaborate === null){
            $this->errors[] = "Could not find the specified collaborate project.";
            return $this->sendResponse();
        }
        
        $this->model = $collaborate->delete();
        return $this->sendResponse();
	}
}