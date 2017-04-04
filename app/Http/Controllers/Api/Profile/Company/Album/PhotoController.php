<?php

namespace App\Http\Controllers\Api\Profile\Company\Album;

use App\Http\Api\Response;
use App\Http\Controllers\Controller;
use App\Photo;
use App\Album;
use App\Http\Api\SendsJsonResponse;
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
    public function index($profileId,$companyId, $albumId)
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
    public function store(Request $request, $profileId, $companyId, $albumId)
    {
        $album = Album::where('id',$albumId)->first();

        if (!$album) {
            throw new \Exception("Album not found.");
        }
        
        $data = $request->only(['caption','file']);
        if(!$request->hasFile('file') && empty($request->input('file)'))){
            $this->errors = ['Empty file sent.'];
            return $this->sendResponse();
        }
        
        $imageName = str_random(32) . ".jpg";
        $request->file('file')->storeAs(Photo::getCompanyImagePath($profileId, $companyId, $albumId), $imageName);
        $data['file'] = $imageName;
       

        $this->model = $album->photos()->create($data);
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profileId,$companyId,$albumId,$id)
    {
        $photo = Photo::with('album')->where('id',$id)->where('album_id',$albumId)
            ->whereHas('album.company',function($query) use ($companyId) {
            $query->where("company_id",$companyId);
        })->with(['comments' => function($query){
            $query->orderBy('created_at','desc');
            }])->first();

        if(!$photo){
            throw new \Exception("Profile does not have the photo.");
        }

        $response = new Response($photo);
        return $response->json();



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $profileId,$companyId,$albumId,$id)
    {
        $album = Album::where('id',$albumId)->first();

        if (!$album) {
            throw new \Exception("Album not found.");
        }
    
        if($request->hasFile('file')) {
            $imageName = str_random(32) . ".jpg";
            $request->file('file')->storeAs(Photo::getCompanyImagePath($profileId, $companyId, $albumId), $imageName);
            $data['file'] = $imageName;
        }

        $this->model = $album->photos()->where('id',$id)->update($request->only(['caption','file']));
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $profileId, $companyId, $albumId, $id)
    {
        $album = Album::where('id',$albumId)->first();

        if (!$album) {
            throw new \Exception("Album not found.");
        }

        $this->model = $album->photos()->where('id',$id)->delete();
        return $this->sendResponse();
    }

    public function image($profileId, $companyId, $albumId, $id)
    {
        $photo = \App\Photo::select('file')->find($id);
        return response()->file(Photo::getCompanyImagePath($profileId, $companyId, $albumId, $photo->file));
    }
}
