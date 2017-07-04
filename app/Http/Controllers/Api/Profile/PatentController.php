<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Api\Controller;
use App\Profile\Patent;
use \Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\Request;
class PatentController extends Controller
{
    use SendsJsonResponse;
    
    /**
     * Display a listing of the resource.
     *N
     * @return \Illuminate\Http\Response
     */
    public function index($profileId)
    {
        $this->model = Patent::where('profile_id',$profileId)->get();
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
        $this->model = $request->user()->profile->patents()->create($request->except(['_method','_token']));
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profileId,$id)
    {
        $this->model = Patent::where('profile_id',$profileId)->where('id',$id)->first();
        if(!$this->model){
            throw new \Exception("Patent not found.");
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
        $input = array_filter($input);
        if(isset($input['publish_date'])){
            $input['publish_date'] = date('Y-m-d',strtotime($input['publish_date']));
        }

        $this->model = $request->user()->profile->patents()->
        where('id',$id)->update($input);
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
        $this->model = $request->user()->profile->patents()->where('id',$id)->delete();
        return $this->sendResponse();
    }
}
