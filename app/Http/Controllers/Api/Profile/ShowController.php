<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Api\Controller;
use App\Profile\Show;
use \Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    use SendsJsonResponse;

    private $fields = ['title','description','channel','date','url','appeared_as'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($profileId)
    {
        $this->model = Show::where('profile_id',$profileId)->get();
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
        $this->model = $request->user()->profile->tvshows()->create($request->only($this->fields));
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
        $this->model =  Show::where('profile_id',$profileId)->where('id',$id)->first();

        if(!$this->model){
          return;
            //throw new \Exception("TV Show not found.");
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
        if(isset($input['start_date'])){
            $input['start_date'] = empty($input['start_date']) ? null : date("Y-m-d",strtotime(trim($input['start_date'])));
        }
        if(isset($input['end_date'])){
            $input['end_date'] = empty($input['end_date']) ? null : date("Y-m-d",strtotime(trim($input['end_date'])));
        }

        $this->model = $request->user()->profile->tvshows()
            ->where('id',$id)->update($input);
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
        $this->model = $request->user()->profile->tvshows()->where('id',$id)->delete();
        return $this->sendResponse();
    }
}
