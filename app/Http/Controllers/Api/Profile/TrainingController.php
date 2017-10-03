<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Api\Controller;
use App\Profile\Training;
use \Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    use SendsJsonResponse;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($profileId)
    {
        $this->model = Training::where('profile_id',$profileId)->get();
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
        $input = $request->except(['_method','_token']);
        $input['profile_id'] =$request->user()->profile->id;
        $this->model = Training::create($input);
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profileId, $id)
    {
        $this->model = Training::where('profile_id',$profileId)->where('id',$id)->first();

        if(!$this->model){
            return $this->sendError("Training not found.");
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $profileId, $id)
    {
        $input = $request->except(['_method','_token']);
        $this->model = Training::where('id',$id)->where('profile_id',$request->user()->profile->id)->update($input);
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $profileId, $id)
    {
        $this->model = Training::where('id',$id)->where('profile_id',$request->user()->profile->id)->delete();
        return $this->sendResponse();
    }
}
