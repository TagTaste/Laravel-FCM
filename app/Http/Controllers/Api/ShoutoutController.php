<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Shoutout;
use Illuminate\Http\Request;

class ShoutoutController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var shoutout
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(Shoutout $model)
	{
		$this->model = $model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//we never return all of the shoutouts
        return;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$inputs = $request->all();
		
		//move this to validator
        if(empty($inputs['profile_id']) && empty($inputs['company_id'])){
            throw new \Exception("Missing owner information");
        }
  
		try {
            $this->verifyOwner($request);
        } catch (\Exception $e){
		    //if there's an error, just throw it.
		    throw $e;
        }
        
		$this->model = $this->model->create($inputs);
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
		$shoutout = $this->model->findOrFail($id);
        $profileId = $request->user()->profile->id;
        $meta = $shoutout->getMetaFor($profileId);
        $this->model = ['shoutout'=>$shoutout,'meta'=>$meta];
		
		return $this->sendResponse();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
        try {
            $this->verifyOwner($request);
        } catch (\Exception $e){
            //if there's an error, just throw it.
            throw $e;
        }
        
		$inputs = $request->all();

		$this->model = $this->model->findOrFail($id);
		$this->model = $this->model->update($inputs);

		return $this->sendResponse();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
        try {
            $this->verifyOwner($request);
        } catch (\Exception $e){
            //if there's an error, just throw it.
            throw $e;
        }
        
		$this->model = $this->model->destroy($id);
        return $this->sendResponse();
	}
    
    private function verifyOwner(Request &$request)
    {
        if($request->has('company_id') && $request->input('company_id') !== null){
            $company = Company::find($request->input('company_id'));
            if(!$company){
                throw new \Exception("Company doesn't exist.");
            }
            $userId = $request->user()->id;
            $userBelongsToCompany = $company->checkCompanyUser($userId);
            if(!$userBelongsToCompany){
                throw new \Exception("User doesn't belong to this company");
            }
        }
    
        if($request->has('profile_id') && $request->input('profile_id') !== null){
            $profile = $request->user()->profile()->where('id',$request->input('profile_id'))->first();
            if(!$profile){
                throw new \Exception("User doesn't belong to this profile.");
            }
        }
        
        if($request->input('company_id') !== null && $request->input('profile_id') !== null){
            throw new \Exception("Missing Profile Id or company id");
        }
	}
    
    public function like(Request $request, $id)
    {
        return;
	}
}