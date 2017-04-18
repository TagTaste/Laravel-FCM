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
    
    public function filters()
    {
        $filters = [];
        
        $filters['location'] = Job::select('location')->groupBy('location')->get();
        $filters['types'] = Job\Type::select('id', 'name')->get();
        $this->model = $filters;
        return $this->sendResponse();
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
        $profileId = $request->user()->profile->id;
        
        $this->model = $jobs->with(['applications' => function ($query) use ($profileId) {
            $query->where('applications.profile_id', $profileId);
        }])->paginate();

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