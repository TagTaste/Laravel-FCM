<?php

namespace App\Http\Controllers\Api;

use App\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $loggedInProfileId = $request->user()->profile->id;
        if(!$loggedInProfileId){
            return $this->sendError("Invalid Profile.");
        }
        $photo = Photo::where('id',$id)->first();
    
        if(!$photo){
            return $this->sendError("Could not find photo.");
        }
        $meta = $photo->getMetaFor($loggedInProfileId);
        $this->model = ['photo'=>$photo,'meta'=>$meta];
        return $this->sendResponse();
    }
}
