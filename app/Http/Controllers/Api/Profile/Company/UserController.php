<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Company;
use App\CompanyUser;
use App\Events\Actions\Admin;
use App\Http\Controllers\Api\Controller;
use App\Profile;
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
            return $this->sendError("Company does not exist.");
        }
        if(!$request->has("profile_id"))
        {
            return $this->sendError("User does not exist");
        }
        $profiles = \DB::table('profiles')->where('id',$request->input('profile_id'))->get();

        if(!isset($profiles[0]->user_id))
        {
            return $this->sendError("User does not exist");
        }
        $userId = User::select('id')->where('id',$profiles[0]->user_id)->first();

        if(!$userId){
            return $this->sendError("User does not exist");
        }
        try {
            $this->model = $company->addUser($userId);

        } catch (\Exception $e){
            $this->errors = "Could not add user. " . $e->getMessage();
            $this->model = false;
        }
        $company->user_id = $userId->id;
        event(new Admin($company, $request->user()->profile));

        return $this->sendResponse();
    }
    
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $profileId, $companyId,$userProfileId)
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