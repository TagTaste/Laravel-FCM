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

    public function globalImageUpload(Request $request)
    {
        $companyId = null;
        if($request->has('company_id') && !is_null($request->input('company_id')))
        {
            $companyId = $request->input('company_id');
        }
        $profileId = $request->user()->profile->id;
        if(is_null($companyId))
        {
            $path = \App\V2\Photo::getProfileImagePath($profileId);
        }
        else
        {
            $path = \App\V2\Photo::getCompanyImagePath($profileId,$companyId);
        }
        $imageName = str_random("32") . ".jpg";
        $randnum = rand(10,1000);
        $response['original_photo'] = \Storage::url($request->file('image')->storeAs($path."/original/$randnum",$imageName,['visibility'=>'public']));
        //create a tiny image
        $path = $path."/tiny/$randnum" . str_random(20) . ".jpg";
        $thumbnail = \Image::make($request->file('image'))->resize(50, null,function ($constraint) {
            $constraint->aspectRatio();
        })->blur(1)->stream('jpg',70);
        \Storage::disk('s3')->put($path, (string) $thumbnail,['visibility'=>'public']);
        $response['tiny_photo'] = \Storage::url($path);
        $meta = getimagesize($request->input('image'));
        $response['meta']['width'] = $meta[0];
        $response['meta']['height'] = $meta[1];
        $response['meta']['mime'] = $meta['mime'];
        $response['meta']['size'] = null;
        $response['meta']['tiny_photo'] = $response['tiny_photo'];
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        $this->model = $response;
        return $this->sendResponse();
    }
}
