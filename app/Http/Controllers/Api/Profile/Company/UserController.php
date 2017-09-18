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
        $company = Company::where('id', $companyId)->first();
        
        if (!$company) {
            throw new \Exception("Company does not belongs this user.");
        }

        $userId = User::select('id')->where('email',$request->input("email"))->first();
        if(!$userId){
            throw new \Exception("User is not available.");
        }

        try {
            $this->model = $company->addUser($userId);
        } catch (\Exception $e){
            $this->errors = "Could not add user. " . $e->getMessage();
            $this->model = false;
        }
        
        return $this->sendResponse();
    }
    
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $profileId, $companyId, $userProfileId)
	{
        $company = Company::where('id', $companyId)->first();
        
        try {
            $userId = \App\Recipe\Profile::select("user_id")->where('id',$userProfileId)->first();
            $this->model = $company->removeUser($userId->user_id);
        } catch(\Exception $e){
            $this->errors = "Could not delete user. " . $e->getMessage();
            $this->model = false;
        }
        
		return $this->sendResponse();
	}

}