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
        $this->model = \App\Filter::getFilters("collaborate");
        return $this->sendResponse();
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function index(Request $request)
	{
        $filters = $request->input('filters');
        
        if(!empty($filters)){
            $this->model = \App\Filter\Job::getModels($filters);
            return $this->sendResponse();
        }
        
        $jobs = $this->model->whereNull('deleted_at');
        $this->model = [];
        
        $profileId = $request->user()->profile->id;

        $this->model = [];
        $this->model["count"] = $jobs->count();
        
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model['data'] = $jobs->skip($skip)->take($take)->get();

		return $this->sendResponse();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request,$id)
	{
	    $job = $this->model->whereNull('deleted_at')->find($id);
        if (!$job) {
            return $this->sendError("No job found with the given Id.");
        }
        $profileId = $request->user()->profile->id;
        $meta = $job->getMetaFor($profileId);
        $this->model = ['job'=>$job,'meta'=>$meta];


        return $this->sendResponse();
	}
    
    /**
     * Returns all Jobs created by an individual and by all of his companies
     *
     */
    public function all(Request $request)
    {
        $userId = $request->user()->id;
        $this->model = \App\Job::
            whereHas('company',function($query) use ($userId) {
            $query->where('user_id',$userId);
        })->orWhereHas('profile',function($query) use ($userId){
            $query->where('user_id',$userId);
        })->orderBy('jobs.created_at','desc')->get();
        
        return $this->sendResponse();
	}
}