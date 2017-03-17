<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Profile\Experience;
use App\Scopes\SendsJsonResponse;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    use SendsJsonResponse;

    private $fields = ['company','designation','description','location',
        'start_date','end_date','current_company','profile_id'];

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
        $experiences = $request->all();
        if(empty($experiences)){
          return;
            //throw new \Exception("Received empty experiences.");
        }
        
        $fields = array_only($experiences,$this->fields);
        $this->model = $request->user()->profile->experience()->create($fields);
        
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
        $input = $request->only($this->fields);
        $input = array_filter($input);
        if(isset($input['start_date'])){
            $input['start_date'] = date('Y-m-d',strtotime($input['start_date']));
        }
        if(isset($input['end_date'])){
            $input['end_date'] = date('Y-m-d',strtotime($input['end_date']));
        }
        $experience = Experience::where('profile_id',$profileId)->where('id',$id)->update($input);
        \Log::info($experience);
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
        $this->model = $request->user()->profile->experience()->where('id',$id)->delete();
        \Log::info($this->model);
        return $this->sendResponse();
    }
}
