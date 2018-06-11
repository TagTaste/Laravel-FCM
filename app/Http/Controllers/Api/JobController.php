<?php

namespace App\Http\Controllers\Api;

use App\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Profile;
use Illuminate\Support\Facades\DB;
use App\Mail\JobResponse;

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
        $this->model = \App\Filter\Job::getFilters("job");
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
        
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        
        if(!empty($filters)){
            $this->model = [];
            $jobIds = \App\Filter\Job::getModelIds($filters,$skip,$take);
            $this->model["data"]=[];
            $this->model['data'] = \App\Job::whereIn('id',$jobIds)->orderBy('created_at','desc')->get();
            $this->model['count'] = count($this->model['data']);
            return $this->sendResponse();
        }
        
        $jobs = $this->model->whereNull('deleted_at')->orderBy('created_at','desc');
        $this->model = [];
        
        $profileId = $request->user()->profile->id;

        $this->model = [];
        $this->model["count"] = $jobs->count();
        
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
	    $job = $this->model->where('id',$id)->where('state','!=',Job::$state[1])->first();
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
    public function jobMailer(Request $request)
    {
        $ids = $request->profile_id;
        foreach ($ids as $id) {
            $user_info= DB::table('users')->leftjoin('profiles','users.id','=','profiles.user_id')->where('profiles.id',$id)->value('email');
            Mail::to($user_info)->send(new JobResponse());
            return "Mailed users";
        }

    }
}