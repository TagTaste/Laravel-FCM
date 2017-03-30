<?php

namespace App\Http\Controllers\Api\Profile;

use App\Album;
use App\Http\Controllers\Controller;
use App\Scopes\SendsJsonResponse;
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
        $this->model = $request->user()->profile->albums()->create($request->intersect(['name','description']));
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
        $this->model = $request->user()->profile->albums()
            ->where('id',$id)->update($request->intersect('name','description'));
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
        $this->model = $request->user()->profile->albums()->where('id',$id)->delete();
        return $this->sendResponse();
    }
}
