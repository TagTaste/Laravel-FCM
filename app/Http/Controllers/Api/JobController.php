<?php

namespace App\Http\Controllers\Api;

use App\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
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
    public function index(Request $request)
	{
        $jobs = $this->model;
        $filters = $request->input('filters');
        if (!empty($filters['location'])) {
            $jobs = $jobs->whereIn('location', $filters['location']);
        }
        
        if (!empty($filters['type_id'])) {
            $jobs = $jobs->whereIn('type_id', $filters['type_id']);
        }
        
        $this->model = $jobs->paginate();

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
}