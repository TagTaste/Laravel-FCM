<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Job;
use App\Scopes\SendsJsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JobController extends Controller
{
    use SendsJsonResponse;
	/**
	 * Variable to model
	 *
	 * @var job
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(Job $model)
	{
		$this->model = $model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($profileId, $companyId)
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
        
		$inputs = $request->except(['_method','_token']);
		$this->model = $company->jobs()->create($inputs);

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
		$this->model = $this->model->where('company_id',$companyId)->where('id',$id)->first();
		
		return $this->sendResponse();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request,$profileId, $companyId, $id)
	{
        $company = $request->user()->companies()->where('id',$companyId)->first();
        
        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }
        
        $this->model = $company->jobs()->where('id',$id)->update($request->except(['_token','_method']));
		return $this->sendResponse();
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($request, $profileId, $companyId, $id)
	{
		$company = $request->user()->companies()->where('id',$companyId)->first();
        
        if(!$company){
            throw new \Exception("This company does not belong to user.");
        }
        
        $this->model = $company->jobs()->where('id',$id)->delete();
        
        return $this->sendResponse();
        
    }
}