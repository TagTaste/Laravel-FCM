<?php

namespace App\Http\Controllers\Api;

use App\Collaborate;
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
	public function index()
	{
		$this->model = $this->model->paginate();

		return $this->sendResponse();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$this->model = $this->model->findOrFail($id);
		return $this->sendResponse();
		
	}
    
    public function apply(Request $request, $id)
    {
        
        $collaborate = $this->model->where('id',$id)->first();
    
        if($request->has('company_id')){
            //company wants to apply
            $this->model = $collaborate->companies()->attach($companyId);
    
        }
        
        if($request->has('profile_id')){
            //individual wants to apply
            $this->model = $collaborate->profiles()->attach($profileId);
    
        }
        return $this->sendResponse();
    }
}