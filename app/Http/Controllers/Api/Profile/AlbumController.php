<?php

namespace App\Http\Controllers\Api\Profile;

use App\Album;
use App\Http\Api\Response;
use App\Http\Controllers\Controller;
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
        if(!$profileId){
            throw new \Exception("Missing Profile Id");
        }
        $albums = Album::where('profile_id',$profileId)->get();
        $response = new Response($albums);
        return $response->json();
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
        $album = $request->user()->profile->albums()->create($request->only(['name','description']));
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
        $album = Album::with('photos')->where('profile_id',$profileId)->where('id',$albumId)->first();

        if(!$album){
            throw new \Exception("Album not found.");
        }

        $response = new Response($album);
        return $response->json();
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
        $album = $request->user()->profile->albums()
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
        $album = $request->user()->profile->albums()->where('id',$id)->delete();
        $response = new Response($album);
        return $response->json();
    }
}
