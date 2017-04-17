<?php

namespace App\Http\Controllers;

use App\Job;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

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
	public function index($filters = [])
	{
        $jobs = $this->model;
        
        if(!empty($filters['location'])){
	       $jobs = $jobs->whereIn('location',$filters['location']);
        }
        
        if(!empty($filters['type'])){
            $jobs = $jobs->whereIn('type',$filters['type']);
        }
        
        $jobs = $jobs->paginate();

		return view('jobs.index', compact('jobs'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$job = $this->model->findOrFail($id);
		
		return view('jobs.show', compact('job'));
	}
}