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
        
        $filters['location'] = \App\Filter\Job::select('location as value')->groupBy('location')
                ->where('location','!=','null')->get();
        $filters['types'] = Job\Type::with([])->select('id as key', 'name as value')->get();
        $filters['Expected Role'] = \App\Filter\Job::select('expected_role as value')->groupBy('expected_role')
            ->where('expected_role','!=','null')->get();
        $filters['experience_required'] = \DB::table('jobs')->selectRaw("distinct(experience_required) as value")->whereNotNull('experience_required')->get();
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
		
        $jobs = $this->model->whereNull('deleted_at');
        $this->model = [];
        $filters = $request->input('filters');
        if (!empty($filters['location'])) {
            $jobs = $jobs->whereIn('location', $filters['location']);
        }
        
        if (!empty($filters['expected_role'])) {
            $jobs = $jobs->whereIn('expected_role', $filters['expected_role']);
        }
        
        if (!empty($filters['type_id'])) {
            $jobs = $jobs->whereIn('type_id', $filters['type_id']);
        }
        
        if(!empty($filters['experience_required'])){
            $jobs = $jobs->whereIn('experience_required', $filters['experience_required']);
        }
        
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