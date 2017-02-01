<?php

namespace App\Http\Controllers\Api\Profile;

use App\Album;
use App\Http\Api\Response;
use App\Http\Controllers\Controller;
use App\Scope\SendsJsonResponse;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    use SendsJsonResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($profileId)
    {
        $this->model = Album::where('profile_id',$profileId)->get();
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
        $this->model = $request->user()->profile->albums()->create($request->only(['name','description']));
        $response = new Response($album);
        return $response->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profileId,$albumId)
    {
        $this->model = Album::with('photos')->where('profile_id',$profileId)->where('id',$albumId)->first();

        if(!$this->model){
            throw new \Exception("Album not found.");
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
        $this->model = $request->user()->profile->albums()
            ->where('id',$request->input('id'))->update($request->only('name','description'));
        $response = new Response($album);
        return $response->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$profileId,$id)
    {
        $this->model = $request->user()->profile->albums()->where('id',$id)->delete();
        $response = new Response($album);
        return $response->json();
    }
}
