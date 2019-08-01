<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Api\Controller;
use App\Profile\Experience;
use \Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    use SendsJsonResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($profileId)
    {
        $this->model = Experience::where('profile_id',$profileId)->get();
        return $this->sendResponse();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->except(['_method','_token']);
        $inputs['profile_id'] = $request->user()->profile->id;
        $this->model = Experience::create($inputs);
        $this->model->addUserExperience();
        
        return $this->sendResponse();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request ,$profileId, $id)
    {
        $this->model = Experience::where('profile_id',$profileId)->where('id',$id)->first();

        if(!$this->model){
            throw new \Exception("Experience not found.");
        }

        return $this->sendResponse();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $profileId
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $profileId, $id)
    {
        $input = $request->except(['_method','_token']);
        $profileId = $request->user()->profile->id;
        $experiance = Experience::where('id',$id)->where("profile_id",$profileId)->first();
        $experiance->detachUserExperience();
        $this->model = $experiance->update($input);
        $experiance->updateUserExperience();
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$profileId,$id)
    {
        $experiance = Experience::where('id',$id)->where("profile_id",$profileId)->first();
        $experiance->detachUserExperience();
        $this->model = Experience::where('id',$id)->where('profile_id',$profileId)->delete();
        return $this->sendResponse();
    }
}
