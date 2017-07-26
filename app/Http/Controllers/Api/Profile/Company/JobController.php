<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Company;
use App\Http\Controllers\Api\Controller;
use App\Job;
use Illuminate\Http\Request;
use App\Events\Update;

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
        $this->model['jobs'] = Job::where('company_id', $companyId)
            ->with(['applications' => function ($query) use ($profileId) {
                $query->where('applications.profile_id', $profileId);
            }])
            ->paginate();
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
            throw new \Exception("This company does not belong to user.");
        }
        
        $inputs = $request->except(['_method', '_token','notify']);
        $inputs['profile_id'] = $request->user()->profile->id;
        $job = $company->jobs()->create($inputs);

        $notifies = $request->input("notify");
        if (count($notifies) > 0) {
            foreach ($notifies as &$notify) {
                $notify = ['job_id' => $job->id, "profile_id" => $notify['profile_id'],"is_notify"=>$notify['is_notify']];
            }
            $job->notifications()->insert($notifies);
        }
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
        $job = $this->model->where('company_id', $companyId)->where('id', $id)->first();
        
        if (!$job) {
            throw new \Exception("No job found with the given Id.");
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
            throw new \Exception("This company does not belong to user.");
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
            throw new \Exception("This company does not belong to user.");
        }
        
        $this->model = $company->jobs()->where('id', $id)->delete();
        
        return $this->sendResponse();
        
    }
    
    public function apply(Request $request, $profileId, $companyId, $id)
    {
        $applierProfileId = $request->user()->profile->id;

        $alreadyApplied=\DB::table('applications')->where('job_id',$id)->Where('profile_id',$applierProfileId)->exists();
        if($alreadyApplied){
            throw new \Exception("You had already applied.");
        }

        $company = Company::find($companyId);
        if (!$company) {
            throw new \Exception("Company not found..");
        }
        
        $job = $company->jobs()->where('id', $id)->first();
        
        if (!$job) {
            throw new \Exception("Job not found.");
        }
        $path = "profile/$profileId/job/$id/resume";
        $status = \Storage::makeDirectory($path, 0644, true);
        if ($request->hasFile('resume')) {
            $resumeName = str_random("32") . ".pdf";
            $response = $request->file("resume")->storeAs($path, $resumeName);
            if (!$response) {
                throw new \Exception("Could not save resume " . $resumeName . " at " . $path);
            }
        } else {
            $resumeName = $request->user()->profile->resume;
        }
        $profileId = $request->user()->profile->id;
        $this->model = $job->apply($profileId, $resumeName);
        
        return $this->sendResponse();
    }
    
    public function unapply(Request $request, $profileId, $companyId, $id)
    {
        $company = Company::find($companyId);
        if (!$company) {
            throw new \Exception("Company not found..");
        }
        
        $job = $company->jobs()->where('id', $id)->first();
        
        if (!$job) {
            throw new \Exception("Job not found.");
        }
        $profileId = $request->user()->profile->id;
        
        $this->model = $job->unapply($profileId);
        
        return $this->sendResponse();
    }
    
    public function applications(Request $request, $profileId, $companyId, $id)
    {
        $company = \App\Company::where('user_id',$request->user()->id)->where('id', $companyId)->first();
        
        if (!$company) {
            throw new \Exception("This company does not belong to user.");
        }
        
        $job = $company->jobs()->where('id', $id)->first();
        
        if (!$job) {
            throw new \Exception("Job not found.");
        }
        
        $this->model = ['applications' => $job->applications()->paginate()];
        $this->model['count'] = $job->applications()->count();
        
        return $this->sendResponse();
    }
    
    public function shortlist($profileId, $companyId, $id, $shortlistedProfileId)
    {
        $this->model = true;
        return $this->sendResponse();
    }
}