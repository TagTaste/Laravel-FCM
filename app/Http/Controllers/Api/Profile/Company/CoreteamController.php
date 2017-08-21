<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Http\Controllers\Api\Controller;
use App\Company\Coreteam;
use Illuminate\Http\Request;

class CoreteamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $profileId, $companyId)
    {
        $this->model = Coreteam::where('company_id',$companyId)->orderBy('weight','ASC')->paginate(10);
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

        if(!$request->hasFile('image') && empty($request->input('image)'))){
            return $this->sendError("Photo missing.");
        }

        $imageName = str_random(32) . ".jpg";
        $path = Coreteam::getCoreteamImagePath($profileId, $companyId);
        $response = $request->file('image')->storeAs($path,$imageName);
        if(!$response)
        {
            throw new \Exception("Could not save resume " . $imageName . " at " . $path);
        }
        $data['image'] = $imageName;
        $this->model = $company->coreteams()->create($data);
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
        $this->model = Coreteam::where('company_id',$companyId)->where('id',$id)->first();
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
        $data = $request->except(['_method','_token','company_id']);

        if($request->hasFile('image')){
            $imageName = str_random(32) . ".jpg";
            $path = Coreteam::getCoreteamImagePath($profileId, $companyId);
            $response = $request->file('image')->storeAs($path,$imageName);
            if(!$response)
            {
                throw new \Exception("Could not save resume " . $imageName . " at " . $path);
            }
            $data['image'] = $imageName;
        }
        $this->model = $company->coreteams()->where('id',$id)->update($data);


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

        $this->model = $company->coreteams()->where('id',$id)->delete();

        return $this->sendResponse();
    }

    public function ordering(Request $request, $profileId, $companyId)
    {
        $userId = $request->user()->id;
        $company = \App\Company::where('id',$companyId)->where('user_id',$userId)->first();

        if(!$company){
            throw new \Exception("User does not belong to this company.");
        }
        $orders =$request->input("order");
        if(count($orders)>0){
            foreach ($orders as $order){
                $this->model = Coreteam::where('id',$order['id'])->update(['weight'=>$order['weight']]);
            }
        }
        return $this->sendResponse();
    }
}
