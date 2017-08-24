<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Application;
use App\Company;
use App\Http\Controllers\Api\Controller;
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
    public function index(Request $request, $profileId, $companyId)
    {
        $profileId = $request->user()->id;
        $this->model = [];
        $this->model['jobs'] = Job::where('company_id', $companyId)->whereNull('deleted_at')
            ->with(['applications' => function ($query) use ($profileId) {
                $query->where('applications.profile_id', $profileId);
            }]);
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model['jobs'] = $this->model['jobs']->skip($skip)->take($take)->get();

        $this->model['count'] = Job::where('company_id', $companyId)->count();
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
        $company = \App\Company::where('user_id',$request->user()->id)->where('id', $companyId)->first();
        
        if (!$company) {
            return $this->sendError("This company does not belong to user.");
        }
        
        $inputs = $request->except(['_method', '_token']);
        $inputs['profile_id'] = $request->user()->profile->id;
        $job = $company->jobs()->create($inputs);
        $this->model = Job::find($job->id);
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
        $job = $this->model->where('company_id', $companyId)->whereNull('deleted_at')->where('id', $id)->first();
        
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
        $company = \App\Company::where('user_id',$request->user()->id)->where('id', $companyId)->first();
        
        if (!$company) {
            return $this->sendError("This company does not belong to user.");
        }
        
        $this->model = $company->jobs()->where('id', $id)->update($request->except(['_token', '_method']));
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
        $company = \App\Company::where('user_id',$request->user()->id)->where('id', $companyId)->first();
        if (!$company) {
            return $this->sendError("This company does not belong to user.");
        }
        
        $this->model = $company->jobs()->where('id', $id)->delete();
        
        return $this->sendResponse();
        
    }
    
    public function apply(Request $request, $profileId, $companyId, $id)
    {
        $job = \App\Job::where('company_id',$companyId)->where('id',$id)->first();
    
        if (!$job) {
            return $this->sendError("Job not found.");
        }
    
        if($job->resume_required && !$request->hasFile("resume")){
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
            $response = $request->file("resume")->storeAs($path, $resumeName,['visibility'=>'public']);
            if (!$response) {
                return $this->sendEerror("Could not save resume.");
            }
//            for update resume in profiles table
//            $data = \App\Profile::where('id', $profileId)->update(['resume' => $resumeName]);
        }
//        else {
//            $resumeName = $request->user()->profile->resume;
//        }
        $this->model = $job->apply($profileId, $resumeName,$request->input("message"));
    
        return $this->sendResponse();
    }
    
    public function unapply(Request $request, $profileId, $companyId, $id)
    {
        $company = Company::find($companyId);
        if (!$company) {
            return $this->sendError("Company not found..");
        }
        
        $job = $company->jobs()->where('id', $id)->first();
        
        if (!$job) {
            return $this->sendError("Job not found.");
        }
        $profileId = $request->user()->profile->id;
        
        $this->model = $job->unapply($profileId);
        
        return $this->sendResponse();
    }
    
    public function applications(Request $request, $profileId, $companyId, $id)
    {
        $userId = $request->user()->id;
        $user = \App\Profile\User::find($userId);
        $isPartOfCompany = $user->isPartOfCompany($companyId);
    
        if(!$isPartOfCompany){
            $this->sendError("This company does not belong to user.");
        }
        
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
        $userId = $request->user()->id;
        $user = \App\Profile\User::find($userId);
        $isPartOfCompany = $user->isPartOfCompany($companyId);
    
        if(!$isPartOfCompany){
            $this->sendError("This company does not belong to user.");
        }
    
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
}