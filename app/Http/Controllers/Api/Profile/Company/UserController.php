<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Company;
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
	    //check if the user belongs to the requested company.
        $loggedInUserId = $request->user()->id;
        $company = Company::where('id', $companyId)->where('user_id', $loggedInUserId)->first();
        
        if (!$company) {
            throw new \Exception("Company does not belongs this user.");
        }
        
		$this->model = $company->getUsers();
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
        $loggedInUserId = $request->user()->id;
        $company = Company::where('id', $companyId)->where('user_id', $loggedInUserId)->first();
        
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
        $loggedInUserId = $request->user()->id;
        $company = Company::where('id', $companyId)->where('user_id', $loggedInUserId)->first();
        
        if (!$company) {
            throw new \Exception("Company does not belongs this user.");
        }
        
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