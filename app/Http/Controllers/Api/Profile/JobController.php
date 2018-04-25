<?php

namespace App\Http\Controllers\Api\Profile;

use App\Application;
use App\Events\DeleteFeedable;
use App\Events\NewFeedable;
use App\Http\Controllers\Api\Controller;
use App\Job;
use App\Profile;
use Carbon\Carbon;
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
        $this->model['jobs'] = Job::where('profile_id', $profileId)->whereNull('deleted_at')->whereNull('company_id');
        $this->model['count'] = $this->model['jobs']->count();
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model['jobs'] = $this->model['jobs']->orderBy('created_at', 'desc')->skip($skip)->take($take)->get();

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
        
        $inputs = $request->except(['_method','_token','company_id','profile_id']);
        $inputs['expires_on'] = Carbon::now()->addMonth()->toDateTimeString();
        $inputs['state'] = Job::$state[0];

        if(empty($inputs['salary_min'])&&isset($inputs['salary_min_remove'])&&$inputs['salary_min_remove']){
            $inputs['salary_min'] = null;
        }
        if(empty($inputs['salary_max'])&&isset($inputs['salary_max_remove'])&&$inputs['salary_max_remove']){
            $inputs['salary_max'] = null;
        }
        if(empty($inputs['experience_min'])&&isset($inputs['experience_min_remove'])&&$inputs['experience_min_remove']){
            $inputs['experience_min'] = null;
        }
        if(empty($inputs['experience_max'])&&isset($inputs['experience_max_remove'])&&$inputs['experience_max_remove']){
            $inputs['experience_max'] = null;
        }
        $this->model = $profile->jobs()->create($inputs);
     
        \App\Filter\Job::addModel($this->model);
    
        //add subscriber
        event(new \App\Events\Model\Subscriber\Create($this->model,$profile));

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
        $job = $this->model->where('profile_id',$profileId)->where('id',$id)->where('state','!=',Job::$state[1])->first();
        
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
        $inputs = $request->except(['_token','_method','company_id','profile_id','expires_on']);

        if(empty($inputs['salary_min'])&&isset($inputs['salary_min_remove'])&&$inputs['salary_min_remove']){
            $inputs['salary_min'] = null;
        }
        if(empty($inputs['salary_max'])&&isset($inputs['salary_max_remove'])&&$inputs['salary_max_remove']){
            $inputs['salary_max'] = null;
        }
        if(empty($inputs['experience_min'])&&isset($inputs['experience_min_remove'])&&$inputs['experience_min_remove']){
            $inputs['experience_min'] = null;
        }
        if(empty($inputs['experience_max'])&&isset($inputs['experience_max_remove'])&&$inputs['experience_max_remove']){
            $inputs['experience_max'] = null;
        }

        $job = $this->model->where('profile_id', $profileId)->where('id', $id)->first();

        if ($job === null) {
            throw new \Exception("Could not find the specified job.");
        }

        if($job->state == 3)
        {
            $inputs['state'] = Job::$state[0];
            $inputs['deleted_at'] = null;
            $inputs['expires_on'] = Carbon::now()->addMonth()->toDateTimeString();
            $this->model = $job->update($inputs);


            $profile = Profile::find($profileId);
            $this->model = Job::find($id);
            event(new NewFeedable($this->model, $profile));
            \App\Filter\Job::addModel($this->model);

            return $this->sendResponse();

        }
        $this->model = $job->update($inputs);
        $job->addToCache();

        \App\Filter\Job::addModel(Job::find($id));
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
        $job = $this->model->where('profile_id', $profileId)->where('id', $id)->whereNull('deleted_at')->first();

        if ($job === null) {
            throw new \Exception("Could not find the specified job.");
        }

        event(new DeleteFeedable($job));

        //send notificants to applicants for delete job
        $profileIds = Application::where('job_id',$id)->get()->pluck('profile_id');
        foreach ($profileIds as $profileId)
        {
            $job->profile_id = $profileId;
            event(new \App\Events\Actions\DeleteModel($job, $request->user()->profile));
        }

        \App\Filter\Job::removeModel($id);

        $this->model = $job->update(['deleted_at'=>Carbon::now()->toDateTimeString(),'state'=>Job::$state[1]]);

        return $this->sendResponse();
        
    }
    
    public function apply(Request $request, $profileId, $id)
    {
        $applierProfileId = $request->user()->profile->id;

        if($profileId==$applierProfileId)
        {
            $this->sendError("You can't apply your own job");
        }
        
        $job = Job::where('id',$id)->whereNull('deleted_at')->first();
        
        if(!$job){
            throw new \Exception("Job not found.");
        }
    
        $resume = $request->user()->completeProfile->resume;
        if($job->resume_required && !$request->hasFile("resume") && !$resume){
            return $this->sendError("Resume required");
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
        else {
            $response = $request->user()->completeProfile->resume;
        }
    
        $this->model = $job->apply($applierProfileId, $response,$request->input("message"));
        
        if($this->model){
            event(new \App\Events\Actions\Apply($job, $request->user()->profile));
            return $this->sendResponse();
        }
        
        return $this->sendError("Could not apply to this job.");
    }
    
    public function unapply(Request $request, $profileId, $id)
    {
        $profile = Profile::find($profileId);
        if(!$profile){
            throw new \Exception("Invalid profile.");
        }
    
        $job = $profile->jobs()->where('id',$id)->whereNull('deleted_at')->first();
        if(!$job){
            throw new \Exception("Job not found.");
        }
        
        $applierProfileId = $request->user()->profile->id;
        $this->model = $job->unapply($applierProfileId);

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
        $this->model = [];
        $profileId = $request->user()->profile->id;
        $applications = Application::where('profile_id',$profileId)->get();
        $ids = $applications->pluck('job_id');
        $jobs = Job::whereIn('id',$ids)->where('state',Job::$state[0]);
        $this->model['count'] = $jobs->count();
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model['jobs'] = $jobs->skip($skip)->take($take)->get();

        return $this->sendResponse();
    }

    public function expired(Request $request)
    {
        $this->model = [];
        $profileId = $request->user()->profile->id;
        $this->model['jobs'] = Job::where('profile_id', $profileId)->where('state',Job::$state[2])->whereNull('company_id');
        $this->model['count'] = $this->model['jobs']->count();

        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model['jobs'] = $this->model['jobs']->orderBy('expires_on', 'desc')->skip($skip)->take($take)->get();

        return $this->sendResponse();

    }
}
