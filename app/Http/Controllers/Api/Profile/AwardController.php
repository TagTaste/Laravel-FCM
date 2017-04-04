<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Profile\Award;
use App\Http\Api\SendsJsonResponse;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    use SendsJsonResponse;
    private $fields = ['name','description','date'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $profileId = null)
    {
        $this->model = $request->user()->profile->awards;
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
        $this->model = $request->user()->profile->awards()->create($request->only($this->fields));
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $profileId,$id)
    {
        $this->model = Award::ForProfile($profileId)->where('id',$id)->first();
        if(!$this->model){
            throw new \Exception("Award not found.");
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
    public function update(Request $request, $profileId,$id)
    {
        $this->model = $request->user()->profile->awards
            ->where('id',$id)->first();
        if($this->model){
            $this->model->update(array_filter($request->only($this->fields)));
        }
        
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
        $this->model = $request->user()->profile->awards
            ->where('id',$id)->first();
        if($this->model){
            $this->model = $this->model->delete();
        return    $this->sendResponse();
        }
        
    }
}
