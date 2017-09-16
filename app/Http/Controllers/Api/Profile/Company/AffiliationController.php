<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Http\Controllers\Api\Controller;
use App\Company\Affiliation;
use Illuminate\Http\Request;

class AffiliationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $profileId, $companyId)
    {
        $this->model = Affiliation::where('company_id',$companyId)->get();
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
        $this->model = Affiliation::create($data);
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
        $this->model = Affiliation::where('company_id',$companyId)->where('id',$id)->first();
        if(!$this->model){
            return $this->sendError("Affiliation not found.");
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
        $data = $request->except(['_method','_token','company_id']);
        $this->model = Affiliation::where('id',$id)->where('company_id',$companyId)->update($data);


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

        $this->model = Affiliation::where('id',$id)->where('company_id',$companyId)->delete();

        return $this->sendResponse();
    }
}
