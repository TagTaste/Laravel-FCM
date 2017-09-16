<?php

namespace App\Http\Controllers\Api\Profile;

use App\Application;
use App\Http\Controllers\Api\Controller;
use App\Job;
use App\Profile;
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
    public function index(Request $request, $profileId)
    {
        $this->model = [];
        $this->model['jobs'] = Job::where('profile_id', $profileId)->whereNull('deleted_at');

        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model['jobs'] = $this->model['jobs']->skip($skip)->take($take)->get();

        $this->model['count'] = Job::where('profile_id',$profileId)->count();

        return $this->sendResponse();
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $profile = $request->user()->profile;
        
        $inputs = $request->except(['_method','_token','company_id']);
        $this->model = $profile->jobs()->create($inputs);
        return $this->sendResponse();
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($profileId, $id)
    {
        $job = $this->model->where('profile_id',$profileId)->whereNull('deleted_at')->where('id',$id)->first();
        
        if (!$job) {
            return $this->sendError("No job found with the given Id.");
        }
        $meta = $job->getMetaFor($profileId);
        $this->model = ['job'=>$job,'meta'=>$meta];

        return $this->sendResponse();
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $profileId, $id)
    {
        $profileId = $request->user()->profile->id;
        
        $this->model = Job::where('id',$id)->where('profile_id',$profileId)->update($request->except(['_token','_method','company_id']));
        return $this->sendResponse();
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $profileId, $id)
    {
        $profileId = $request->user()->profile->id;
        $this->model = Job::where('id',$id)->where('profile_id',$profileId)->delete();
        return $this->sendResponse();
        
    }
    
    public function apply(Request $request, $profileId, $id)
    {
        $applierProfileId = $request->user()->profile->id;

        if($profileId==$applierProfileId)
        {
            $this->sendError("You can't apply your own job");
        }
        
        $job = Job::where('id',$id)->where('profile_id',$profileId)->first();
        
        if(!$job){
            throw new \Exception("Job not found.");
        }
    
        $path = "profile/$profileId/job/$id/resume";
        $status = \Storage::makeDirectory($path, 0644, true);
        $response = null;
        if ($request->hasFile('resume')) {
            $ext = \File::extension($request->file('resume')->getClientOriginalName());
            $resumeName = str_random("32") .".". $ext;
            $response = $request->file("resume")->storeAs($path, $resumeName,['visibility'=>'public']);
            if (!$response) {
                throw new \Exception("Could not save resume " . $resumeName . " at " . $path);
            }
        }
        $this->model = $job->apply($applierProfileId, $response,$request->input("message"));
        \Redis::hIncrBy("meta:job:" . $id,"count",1);
        return $this->sendResponse();
    }
    
    public function unapply(Request $request, $profileId, $id)
    {
        $profile = Profile::find($profileId);
        if(!$profile){
            throw new \Exception("Invalid profile.");
        }
    
        $job = $profile->jobs()->where('id',$id)->first();
        if(!$job){
            throw new \Exception("Job not found.");
        }
        
        $applierProfileId = $request->user()->profile->id;
        $this->model = $job->unapply($applierProfileId);
        \Redis::hDecrBy("meta:job:" . $id,"count",1);
    
        return $this->sendResponse();
    }
    
    public function applications(Request $request, $profileId, $id)
    {
        $job = $request->user()->profile->jobs()->where('id',$id)->first();
        
        if(!$job){
            throw new \Exception("Job not found.");
        }
        $this->model = [];
    
    
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $applications = $job->applications()->skip($skip)->take($take);
    
        $count = null;
        if($request->has('tag')){
            $tag = $request->input("tag");
            $applications = $applications->where('shortlisted', $tag);
            $count = $applications->count();
        }
        $this->model['applications'] = $applications->get();
        
        if(!$count){
            $count = Application::getCounts($job->id);
        }
        
        $this->model['count'] = $count;
        return $this->sendResponse();
    }
    
    public function shortlist(Request $request, $profileId, $id, $shortlistedProfileId)
    {
        $profile = $request->user()->profile;
        
        $job = $profile->jobs()->where('id',$id)->first();
        
        if(!$job){
            throw new \Exception("Job not found.");
        }
        
        $shortlistedApplication = $job->applications()->where('profile_id',$shortlistedProfileId)->first();
        
        if(!$shortlistedApplication){
            throw new \Exception("Application not found.");
        }
        $this->model = [];
        $this->model['success'] = $shortlistedApplication->shortlist($profile,$request->input("tag"));
        $this->model['count'] = Application::getCounts($job->id);
        return $this->sendResponse();
    }

    public function applied(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $applications = Application::where('profile_id',$profileId)->get();
        $ids = $applications->pluck('job_id');

        $jobs = Job::whereIn('id',$ids);

        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model = $jobs->skip($skip)->take($take)->get();

        return $this->sendResponse();
    }
}