<?php

namespace App\Http\Controllers\Api\Profile\Album;

use App\Http\Controllers\Api\Controller;
use App\Photo;
use \Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PhotoController extends Controller
{
    use SendsJsonResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($profileId,$albumId)
    {
        $this->model = Photo::where('album_id',$albumId)->paginate(10);
        return $this->sendResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $profileId, $albumId)
    {
        $album = $request->user()->profile->albums()->where('id',$albumId)->first();

        if (!$album) {
            throw new \Exception("Album not found.");
        }
        $data = $request->only(['caption','file']);
        $path = Photo::getProfileImagePath($profileId, $albumId);
        $this->saveFileToData("file",$path,$request,$data);
        
        $this->model = $album->photos()->create($data);
        return $this->sendResponse();
    }
    
    private function saveFileToData($key,$path,&$request,&$data)
    {
        if($request->hasFile($key)){
            $data[$key] = $this->saveFile($path,$request,$key);
        }
    }
    
    private function saveFile($path,&$request,$key)
    {
        $imageName = str_random("32") . ".jpg";
        $response = $request->file($key)->storeAs($path,$imageName);
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        return $imageName;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profileId,$albumId,$id)
    {
        $this->model = Photo::with('album')->where('id',$id)->where('album_id',$albumId)
            ->whereHas('album.profile',function($query) use ($profileId) {
            $query->where("profile_id",$profileId);
        })->with(['comments' => function($query){
            $query->orderBy('created_at','desc');
            }])->first();

        if(!$this->model){
            throw new \Exception("Profile does not have the photo.");
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
    public function update(Request $request, $profileId,$albumId,$id)
    {
        $album = $request->user()->profile->albums()->where('id',$albumId)->first();

        if (!$album) {
            throw new \Exception("Album not found.");
        }
        $data = $request->only(['caption','file']);
        $path = Photo::getProfileImagePath($profileId, $albumId);
        $this->saveFileToData("file",$path,$request,$data);
        
        $this->model = $album->photos()->where('id',$id)->update($data);
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $profileId, $albumId, $id)
    {
        $album = $request->user()->profile->albums()->where('id',$albumId)->first();

        if (!$album) {
            throw new \Exception("Album not found.");
        }

        $this->model = $album->photos()->where('id',$id)->delete();
        return $this->sendResponse();
    }

    public function image($profileId, $albumId, $id)
    {
        $photo = \App\Photo::select('file')->find($id);
        return response()->file(Photo::getProfileImagePath($profileId, $albumId, $photo->file));
    }
}
