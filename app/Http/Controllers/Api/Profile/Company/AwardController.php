<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Company;
use App\Http\Controllers\Api\Controller;
use App\Company\Award;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    private $fields = ['name','description','date'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $profileId, $companyId)
    {
        $this->model = Award::forCompany($companyId)->paginate(10);
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
        $company = Company::where('id',$companyId)->where('user_id',$userId)->first();

        if(!$company){
            throw new \Exception("User does not belong to this company.");
        }

        $this->model = $company->awards()->create($request->only($this->fields));
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
        $this->model = Award::forCompany($companyId)->where('id',$id)->first();

        if(!$this->model){
            throw new \Exception("Award not found.");
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
        $company = Company::where('id',$companyId)->where('user_id',$userId)->first();

        if(!$company){
            throw new \Exception("User does not belong to this company.");
        }

        $this->model = Award::where('id',$id)->forCompany($companyId)->whereHas('company.user',function($query) use ($userId){
            $query->where('id',$userId);
        })->update($request->only($this->fields));

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
        $company = Company::where('id',$companyId)->where('user_id',$userId)->first();

        if(!$company){
            throw new \Exception("User does not belong to this company.");
        }

        $this->model = Award::where('id',$id)->forCompany($companyId)->whereHas('company.user',function($query) use ($userId){
            $query->where('id',$userId);
        })->delete();

        return $this->sendResponse();
    }
}
