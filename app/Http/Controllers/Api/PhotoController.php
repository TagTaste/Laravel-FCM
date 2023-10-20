<?php

namespace App\Http\Controllers\Api;

use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

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
        try{
            $path = $path."/tiny/$randnum" . str_random(20) . ".jpg";
            $thumbnail = \Image::make($request->file('image'))->resize(50, null,function ($constraint) {
                $constraint->aspectRatio();
            })->blur(1)->stream('jpg',70);
            \Storage::disk('s3')->put($path, (string) $thumbnail,['visibility'=>'public']);
            $response['tiny_photo'] = \Storage::url($path);
        } catch (\Exception $e){
            $response['tiny_photo'] = $response['original_photo'];
        }
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

    public function globalFileUpload(Request $request)
    {
        $data=$request->all();
        $rules=['document'=>'mimes:pdf,doc,docx,txt,xls,xlsx|max:10240|required'];
        $validator = Validator($data,$rules);
        if ($validator->fails()){
            return $this->sendError("This file type not allowed or max limit(10 MB)reached.");
        }

        if(!$request->hasFile('document')){
            return $this->sendError("Could not find document to upload.");
        }
        $file = $request->file('document');
        $profileId = $request->user()->profile->id;
        $extension = $file->getClientOriginalExtension();

        $modelName = strtolower($request->input('model_name'));
        if(is_null($modelName) || $modelName == ''){
            return $this->sendError("Model name missing.");
        }
        $fileNameRandom = str_random("32") . ".$extension";
        $originalName = $file->getClientOriginalName();
        
        $path = "document/$modelName/$profileId";
        $fileSize = $request->file('document')->getSize(); //return in Bytes divide by 1024 for KB
 
        $response['document_url'] = \Storage::url($request->file('document')->storeAs($path,$fileNameRandom,['visibility'=>'public']));
        $response['orignal_name'] = $originalName;
        $response['meta']['profile_id'] = $profileId;
        $response['meta']['name'] = $request->user()->name;
        
        if(!$response){
            throw new \Exception("Could not save image " . $originalName . " at " . $path);
        }
        $this->model = $response;
        return $this->sendResponse();
    }

    public function globalVideoUpload($modelName, Request $request){
        
        if(!in_array($modelName, ['polling','surveys','quiz','collaborate'])){
            return $this->sendNewError("This model is not supported.");
        }

        $profileId = $request->user()->profile->id;
        $path = "global/video/$modelName/$profileId";
        $status = Storage::makeDirectory($path,0644,true);
        
        // $path = Shoutout::getProfileMediaPath($profile->id);
        $filename = $request->file('media_file')->getClientOriginalName();
        $filename = str_random(15).".".\File::extension($filename);
        $inputs['media_url'] = $request->file("media_file")->storeAs($path, $filename,['visibility'=>'public']);
        $mediaJson =  $this->videoTranscodingNew($inputs['media_url']);
        $mediaJson = json_decode($mediaJson,true);
        $inputs['cloudfront_media_url'] = $mediaJson['cloudfront_media_url'];
        $inputs = $mediaJson;

        $this->model = $inputs;
        return $this->sendNewResponse();
    }

    private function videoTranscodingNew($url)
    {
        $profileId = request()->user()->profile->id;
        $curl = curl_init();
        $data = [
            'profile_id' => $profileId,
            'file_path' => $url
        ];
        curl_setopt_array($curl, array(
            CURLOPT_URL => env('TRANSCODING_APIGATEWAY_URL'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                "accept: */*",
                "accept-language: en-US,en;q=0.8",
                "content-type: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response);
        $body = $response->body;
        return json_encode($body,true);
    }
}
