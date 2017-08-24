<?php

namespace App\Http\Controllers\Api\Profile\Company;

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
        $userId = $request->user()->id;
        $company = \App\Company::where('id',$companyId)->where('user_id',$userId)->first();

        if(!$company){
            throw new \Exception("User does not belong to this company.");
        }
        $data = $request->except(['_method','_token','company_id']);
        $data['company_id'] = $companyId;
        if($request->hasFile('image')) {
            $imageName = str_random(32) . ".jpg";
            $path = Gallery::getGalleryImagePath($profileId, $companyId);
            $data['image'] = $request->file("image")->storeAs($path, $imageName, ['visibility' => 'public']);
        }
        \Log::info($data);
        $this->model = $company->gallery()->create($data);
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
        $userId = $request->user()->id;
        $company = \App\Company::where('id',$companyId)->where('user_id',$userId)->first();

        if(!$company){
            throw new \Exception("User does not belong to this company.");
        }
        $inputs = $request->except(['_method','_token','company_id','profile_id']);
        $inputs['company_id'] = $companyId;
        if ($request->hasFile('image')) {
            $imageName = str_random(32) . ".jpg";
            $path = Gallery::getAlbumImagePath($profileId, $companyId);
            $inputs['image'] = $request->file('image')->storeAs($path, $imageName,['visibility'=>'public']);;
        }
        $this->model = $company->gallery()->where('id',$id)->update($inputs);


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
        $userId = $request->user()->id;
        $company = \App\Company::where('id',$companyId)->where('user_id',$userId)->first();

        if(!$company){
            throw new \Exception("User does not belong to this company.");
        }

        $this->model = $company->gallery()->where('id',$id)->delete();

        return $this->sendResponse();
    }
}
