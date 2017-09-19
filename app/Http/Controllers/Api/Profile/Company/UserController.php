<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Company;
use App\CompanyUser;
use App\Http\Controllers\Api\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request,$profileId,$companyId)
	{
		$this->model = CompanyUser::where("company_id",$companyId)->get();
		return $this->sendResponse();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
    public function store(Request $request, $profileId, $companyId)
    {
        $data = $request->except(['_method','_token','company_id']);
        \Log::info($data);
        $profileId = \App\Recipe\Profile::where('id',$data['profile_id'])->exists();
        if(!$profileId)
        {
            return $this->sendError("User not found.");
        }
        $data['company_id'] = $companyId;
        $this->model = CompanyUser::create($data);
        return $this->sendResponse();
    }
    
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $profileId, $companyId)
	{
        $this->model = CompanyUser::where('profile_id',$request->input('profile_id'))->where('company_id',$companyId)->delete();

        return $this->sendResponse();
	}

}