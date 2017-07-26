<?php

namespace App\Http\Controllers\Api\Profile;

use App\Album;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($profileId)
    {
        $this->model = Album::forProfile($profileId)->paginate(10);
        return $this->sendResponse();
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $inputs['profile_id'] =  $request->user()->profile->id;
        $this->model = \App\Album::create($inputs);
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profileId,$albumId)
    {
        $this->model = Album::forProfile($profileId)->with('photos')->where('id',$albumId)->first();

        if(!$this->model){
            throw new \Exception("Album not found.");
        }

        return $this->sendResponse();
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
        $inputs = $request->all();
        $this->model = \App\Album::where('id',$id)->where('profile_id',$request->user()->profile->id)->update($inputs);
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response`
     */
    public function destroy(Request $request,$profileId,$id)
    {
        $this->model = \App\Album::where('id',$id)->where('profile_id',$request->user()->profile->id)->delete();
        return $this->sendResponse();
    }
}
