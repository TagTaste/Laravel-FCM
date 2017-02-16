<?php

namespace App\Http\Controllers\Api\Profile\Album;

use App\Http\Api\Response;
use App\Http\Controllers\Controller;
use App\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($profileId,$albumId)
    {
        $this->model = Photo::where('profile_id',$profileId)->where('album_id',$albumId)->paginate(10);
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

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profileId,$albumId,$id)
    {
        $photo = Photo::with('album')->where('id',$id)->where('album_id',$albumId)
            ->whereHas('album.profile',function($query) use ($profileId) {
            $query->where("profile_id",$profileId);
        })->first();

        if(!$photo){
            throw new \Exception("Profile does not have the photo.");
        }

        $response = new Response($photo);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function apiImage($id)
    {
        $file = \App\Photo::find($id);
        return response()->file(storage_path("app/" . $file->file));
    }
}
