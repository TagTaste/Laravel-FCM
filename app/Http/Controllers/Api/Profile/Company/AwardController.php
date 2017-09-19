<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Company;
use App\CompanyUser;
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
        $data = $request->except(['_method','_token','company_id']);
        $data['company_id'] = $companyId;
        $this->model = Award::create($data);
        $this->model->company()->sync($companyId);
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
        $data = $request->except(['_method','_token','company_id']);
        $data['company_id'] = $companyId;

        if(isset($inputs['date'])){
            $inputs['date'] = "01-".$inputs['date'];
            $inputs['date'] = date('Y-m-d',strtotime($inputs['date']));
        }

        $this->model = Award::where('id',$id)->update($inputs);

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

        $this->model = Award::where('id',$id)->delete();

        return $this->sendResponse();
    }
}
