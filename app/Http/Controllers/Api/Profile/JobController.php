<?php

namespace App\Http\Controllers\Api\Profile;

use App\Profile;
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
    public function index(Request $request, $profileId)
    {
        $this->model = [];
        $this->model['jobs'] = Job::where('profile_id', $profileId)
            ->with(['applications' => function ($query) use ($profileId) {
                $query->where('applications.profile_id', $profileId);
            }])
            ->paginate();
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
        
        $inputs = $request->except(['_method','_token']);
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
        $this->model = $this->model->where('profile_id',$profileId)->where('id',$id)->first();
        
        if (!$this->model) {
            throw new \Exception("No job found with the given Id.");
        }
        
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
        $profile = $request->user()->profile;
        
        $this->model = $profile->jobs()->where('id',$id)->update($request->except(['_token','_method']));
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
        $profile = $request->user()->profile;
        $this->model = $profile->jobs()->where('id',$id)->delete();
        
        return $this->sendResponse();
        
    }
    
    public function apply(Request $request, $profileId, $id)
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
        
        $this->model = $job->apply($applierProfileId);
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
        
        return $this->sendResponse();
    }
    
    public function applications(Request $request, $profileId, $id)
    {
        $job = $request->user()->profile->jobs()->where('id',$id)->first();
        
        if(!$job){
            throw new \Exception("Job not found.");
        }
    
        $this->model = ['applications' => $job->applications()->paginate()];
        $this->model['count'] = $job->applications()->count();

        return $this->sendResponse();
    }
    
    public function shortlist($profileId, $id, $shortlistedProfileId)
    {
        throw new \Exception("Not implemented yet.");
    }
}