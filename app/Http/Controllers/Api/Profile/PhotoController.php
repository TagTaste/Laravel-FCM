<?php

namespace App\Http\Controllers\Api\Profile;

use App\Events\DeleteFeedable;
use App\Events\NewFeedable;
use App\Events\UpdateFeedable;
use App\Http\Controllers\Api\Controller;
use App\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($profileId)
    {
        $this->model = Photo::forProfile($profileId)->paginate(10);
        return $this->sendResponse();
    }
    
    private function saveFileToData($key,$path,&$request,&$data)
    {
        if($request->hasFile($key)){
            $data[$key] = $this->saveFile($path,$request,$key);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $data = $request->except(['_method','_token','profile_id']);
        if(!isset($data['privacy_id'])){
            $data['privacy_id'] = 1;
        }
        $path = Photo::getProfileImagePath($profileId);
        $this->saveFileToData("file",$path,$request,$data);
        
        $this->model = $request->user()->profile->photos()->create($data);
        event(new NewFeedable($this->model));
        return $this->sendResponse();
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
    public function show(Request $request,$profileId,$id)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $this->model = Photo::where('id',$id)->forProfile($profileId)->with(['comments' => function($query){
            $query->orderBy('created_at','desc');
            }])
        ->with(['like'=>function($query) use ($loggedInProfileId){
                $query->where('profile_id',$loggedInProfileId);
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
    public function update(Request $request, $profileId,$id)
    {
        $data = $request->except(['_method','_token','profile_id']);
        if(!isset($data['privacy_id'])){
            $data['privacy_id'] = 1;
        }
        $path = Photo::getProfileImagePath($profileId);
        $this->saveFileToData("file",$path,$request,$data);
        
        $this->model = $request->user()->profile->photos()->where('id',$id)->update($data);
        event(new UpdateFeedable($this->model));
    
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
        $this->model =  $request->user()->profile->photos()->where('id',$id)->first();
        event(new DeleteFeedable($this->model));
        $this->model = $this->model->delete();
        return $this->sendResponse();
    }

    public function image($profileId, $id)
    {
        $photo = \App\Photo::select('file')->find($id);
        return response()->file(Photo::getProfileImagePath($profileId, $photo->file));
    }
}
