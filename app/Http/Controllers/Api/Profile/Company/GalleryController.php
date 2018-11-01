<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\CompanyUser;
use App\Http\Controllers\Api\Controller;
use App\Company\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $profileId, $companyId)
    {
        $this->model = Gallery::where('company_id',$companyId)->orderBy('id','desc')->get();
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
    public function store(Request $request,$profileId, $companyId)
    {
        $data = $request->except(['_method','_token','company_id']);
        $data['company_id'] = $companyId;
        if($request->hasFile('image_meta')) {
            $path = Gallery::getGalleryImagePath($profileId, $companyId);
            $this->saveFileToData("image_meta",$path,$request,$data,"image");
        }
        $this->model = Gallery::create($data);
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$profileId,$companyId,$id)
    {
        $this->model = Gallery::where('company_id',$companyId)->where('id',$id)->first();
        if(!$this->model){
            throw new \Exception("Core team not found.");
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
    public function update(Request $request, $profileId,$companyId,$id)
    {
        $inputs = $request->except(['_method','_token','company_id','profile_id']);
        $inputs['company_id'] = $companyId;
        if ($request->hasFile('image_meta')) {
            $path = Gallery::getGalleryImagePath($profileId, $companyId);
            $this->saveFileToData("image_meta",$path,$request,$inputs,"image");
        }
        $this->model = Gallery::where('id',$id)->update($inputs);


        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $profileId, $companyId, $id)
    {
        $this->model = Gallery::where('id',$id)->delete();

        return $this->sendResponse();
    }

    private function saveFileToData($key,$path,&$request,&$data,$extraKey = null)
    {
        if($request->hasFile($key) && !is_null($extraKey)){

            $response = $this->saveFile($path,$request,$key);
            $data[$key] = json_encode($response,true);
            $data[$extraKey] = $response['original_photo'];
        }
    }

    private function saveFile($path,&$request,$key)
    {
        $imageName = str_random("32") . ".jpg";
        $response['original_photo'] = \Storage::url($request->file($key)->storeAs($path."/original",$imageName,['visibility'=>'public']));
        //create a tiny image
        $path = $path."/tiny/" . str_random(20) . ".jpg";
        $thumbnail = \Image::make($request->file($key))->resize(50, null,function ($constraint) {
            $constraint->aspectRatio();
        })->blur(1)->stream('jpg',70);
        \Storage::disk('s3')->put($path, (string) $thumbnail,['visibility'=>'public']);
        $response['tiny_photo'] = \Storage::url($path);
        $response['meta'] = getimagesize($request->input($key));
        if(!$response){
            throw new \Exception("Could not save image " . $imageName . " at " . $path);
        }
        return $response;
    }
}
