<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Events\DeleteFeedable;
use App\Events\NewFeedable;
use App\Events\UpdateFeedable;
use App\Http\Controllers\Api\Controller;
use App\Photo;
use App\Album;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($profileId,$companyId)
    {
        $this->model = Photo::forCompany($companyId)->paginate(10);
        return $this->sendResponse();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $profileId, $companyId)
    {
        $company = $request->user()->companies()->find($companyId);
        
        if(!$company){
            throw new \Exception( "This company does not belong to the user.");
        }
    
        $data = $request->except(['_method','_token','company_id']);
        
        if(!$request->hasFile('file') && empty($request->input('file)'))){
            throw new \Exception('Empty file sent.');
        }
        
        $imageName = str_random(32) . ".jpg";
        $request->file('file')->storeAs(Photo::getCompanyImagePath($profileId, $companyId), $imageName);
        $data['file'] = $imageName;
       
        if(!isset($data['privacy_id'])){
            $data['privacy_id'] = 1;
        }
        
        $this->model = $company->photos()->create($data);
        event(new NewFeedable($this->model));
        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($profileId,$companyId,$id)
    {
        $this->model = Photo::where('id',$id)->forCompany($companyId)->with(['comments' => function($query){
            $query->orderBy('created_at','desc');
            }])->first();

        if(!$this->model){
            throw new \Exception("Company does not have the photo.");
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
    public function update(Request $request, $profileId,$companyId,$id)
    {
        $company = $request->user()->companies()->find($companyId);
    
        if(!$company){
           throw new \Exception("This company does not belong to the user.");
        }
        
        $data = $request->except(['_method','_token','company_id']);
    
        if($request->hasFile('file')) {
            $imageName = str_random(32) . ".jpg";
            $request->file('file')->storeAs(Photo::getCompanyImagePath($profileId, $companyId), $imageName);
            $data['file'] = $imageName;
        }
        if(!isset($data['privacy_id'])){
            $data['privacy_id'] = 1;
        }
        $this->model = $company->photos()->where('id',$id)->update($data);
        event(new UpdateFeedable($this->model));
    
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
        $company = $request->user()->companies()->find($companyId);
    
        if(!$company){
            throw new \Exception("This company does not belong to the user.");
        }

        $this->model = $company->photos()->where('id',$id)->first();
        event(new DeleteFeedable($this->model));
        
        $this->model = $this->model->delete();
        return $this->sendResponse();
    }

    public function image($profileId, $companyId, $id)
    {
        $photo = \App\Photo::select('file')->find($id);
        return response()->file(Photo::getCompanyImagePath($profileId, $companyId, $photo->file));
    }
}
