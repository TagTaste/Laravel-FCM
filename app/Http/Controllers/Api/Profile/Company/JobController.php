<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Application;
use App\Company;
use App\CompanyUser;
use App\Events\DeleteFeedable;
use App\Events\NewFeedable;
use App\Http\Controllers\Api\Controller;
use App\Job;
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
    public function index(Request $request, $profileId, $companyId)
    {
        $profileId = $request->user()->id;
        $this->model = [];
        $this->model['jobs'] = Job::where('company_id', $companyId)->whereNull('deleted_at');
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model['count'] = $this->model['jobs']->count();
        $this->model['jobs'] = $this->model['jobs']->orderBy('created_at', 'desc')->skip($skip)->take($take)->get();

        return $this->sendResponse();
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, $profileId, $companyId)
    {
        $inputs = $request->except(['_method', '_token']);
        $profile = $request->user()->profile;
        $inputs['profile_id'] = $profile->id;
        $inputs['company_id'] = $companyId;
        $inputs['state'] = Job::$state[0];
        $inputs['expires_on'] = Carbon::now()->addMonth()->toDateTimeString();
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

        $job = Job::create($inputs);
        $this->model = Job::find($job->id);
        \App\Filter\Job::addModel($this->model);
    
        //add subscriber
        event(new \App\Events\Model\Subscriber\Create($this->model,$profile));
        
        return $this->sendResponse();
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($profileId, $companyId, $id)
    {
        $job = $this->model->where('company_id', $companyId)->where('id', $id)->where('state','!=',Job::$state[1])->first();
        
        if (!$job) {
            return $this->sendError("No job found with the given Id.");
        }
        
        $meta = $job->getMetaFor($profileId);
        $this->model = ['job' => $job, 'meta' => $meta];
        
        return $this->sendResponse();
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $profileId, $companyId, $id)
    {
        $profileId = $request->user()->profile->id;
        $inputs = $request->except(['_token','_method','company_id','profile_id','expires_on']);

        $job = $this->model->where('company_id', $companyId)->where('id', $id)->first();

        if ($job === null) {
            throw new \Exception("Could not find the specified job.");
        }


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

        if($job->state == 3)
        {
            $inputs['state'] = Job::$state[0];
            $inputs['deleted_at'] = null;
            $inputs['expires_on'] = Carbon::now()->addMonth()->toDateTimeString();

            $this->model = $job->update($inputs);

            $company = Company::find($companyId);
            $this->model = Job::find($id);

            event(new NewFeedable($this->model, $company));
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
     * @param  int $id
     * @return Response
     */
    public function destroy(Request $request, $profileId, $companyId, $id)
    {
        $job = $this->model->where('company_id', $companyId)->where('id', $id)->first();

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

        //remove filters
        \App\Filter\Job::removeModel($id);

        $this->model = $job->update(['deleted_at'=>Carbon::now()->toDateTimeString(),'state'=>Job::$state[1]]);
        
        return $this->sendResponse();
        
    }
    
    public function apply(Request $request, $profileId, $companyId, $id)
    {
        $job = \App\Job::whereNull('deleted_at')->where('id',$id)->first();
    
        if (!$job) {
            return $this->sendError("Job not found.");
        }
        
        $resume = $request->user()->completeProfile->resume;
        if($job->resume_required && !$request->hasFile("resume") && !$resume){
            return $this->sendError("Resume required");
        }
        
        $path = "profile/$profileId/job/$id/resume";
        //profileId is now the logged in user.
        $profileId = $request->user()->profile->id;
        $status = \Storage::makeDirectory($path, 0644, true);
        
        $resumeName = null;
        if($request->hasFile('resume')) {
            $ext = \File::extension($request->file('resume')->getClientOriginalName());
            $resumeName = str_random("32") . "." . $ext;
            $resume = $request->file("resume")->storeAs($path, $resumeName,['visibility'=>'public']);
            if (!$resume) {
                return $this->sendError("Could not save resume.");
            }
        }
        
        $this->model = $job->apply($profileId, $resume,$request->input("message"));
        
        if($this->model){
            $profileIds = CompanyUser::where('company_id',$companyId)->get()->pluck('profile_id');
            foreach ($profileIds as $profileId)
            {
                $job->profile_id = $profileId;
                event(new \App\Events\Actions\Apply($job, $request->user()->profile));
        
            }
            return $this->sendResponse();
        }
        
        return $this->sendError("Could not apply at this time.");
        

        
    }
    
    public function unapply(Request $request, $profileId, $companyId, $id)
    {
        $job = \App\Job::where('company_id',$companyId)->where('id',$id)->whereNull('deleted_at')->first();

        if (!$job) {
            return $this->sendError("Job not found.");
        }
        $profileId = $request->user()->profile->id;
        
        $this->model = $job->unapply($profileId);
        return $this->sendResponse();
    }
    
    public function applications(Request $request, $profileId, $companyId, $id)
    {
        $job = \App\Job::where('id', $id)->where('company_id',$companyId)->first();
        
        if (!$job) {
            return $this->sendError("Job not found.");
        }
    
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $applications = $job->applications()->skip($skip)->take($take);
    
        $this->model = [];
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
    
    public function shortlist(Request $request,$profileId, $companyId, $id, $shortlistedProfileId)
    {
        $job = \App\Job::where('id', $id)->where('company_id',$companyId)->first();

        if (!$job) {
            return $this->sendError("Job not found.");
        }

        $profile = $request->user()->profile;

        $shortlistedApplication = $job->applications()->where('profile_id',$shortlistedProfileId)->first();

        if(!$shortlistedApplication){
            return $this->sendError("Application not found.");
        }

        $this->model = [];
        $this->model['success'] = $shortlistedApplication->shortlist($profile, $request->input("tag"));
        $this->model['count'] = Application::getCounts($job->id);
        return $this->sendResponse();
    }

    public function expired(Request $request,$profileId, $companyId)
    {
        $this->model = [];
        $this->model['jobs'] = Job::where('company_id', $companyId)->where('state',Job::$state[2]);
        $this->model['count'] = $this->model['jobs']->count();

        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model['jobs'] = $this->model['jobs']->orderBy('expires_on', 'desc')->skip($skip)->take($take)->get();

        return $this->sendResponse();

    }
}
