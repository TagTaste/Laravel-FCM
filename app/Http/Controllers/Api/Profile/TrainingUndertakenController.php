<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Api\Controller;
use App\Profile\TrainingUndertaken;
use \Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\Request;

class TrainingUndertakenController extends Controller
{
    use SendsJsonResponse;

    private $fields = ['title','traind_from','completed_on'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($profileId)
    {
        $this->model = TrainingUndertaken::where('profile_id',$profileId)->get();
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
        $input = $request->only($this->fields);
        $input['profile_id'] =$request->user()->profile->id;
        $this->model = $request->user()->profile->trainingUndertaken()->create($input);
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
        $this->model = TrainingUndertaken::where('profile_id',$profileId)->where('id',$id)->first();

        if(!$this->model){
            throw new \Exception("Project not found.");
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
        $input = $request->only($this->fields);
        $input = array_filter($input);
        if(isset($input['completed_on'])){
            $input['completed_on'] = "01-".$input['completed_on'];
            $input['completed_on'] = empty($input['completed_on']) ? null : date("Y-m-d",strtotime(trim($input['completed_on'])));
        }
        $this->model = $request->user()->profile->trainingUndertaken()
            ->where('id',$id)->where('profile_id',$request->user()->profile->id)->update($input);
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
        $this->model = $request->user()->profile->trainingUndertaken()->where('id',$id)->where('profile_id',$request->user()->profile->id)->delete();
        return $this->sendResponse();
    }
}
