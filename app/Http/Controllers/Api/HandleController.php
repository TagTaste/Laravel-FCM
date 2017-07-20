<?php

namespace App\Http\Controllers\Api;

use App\Profile;
use App\User;
use App\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class HandleController extends Controller
{
	public function show(Request $request,$handle)
	{   
		$profile = \App\Profile\User::whereHas("profile",function($query) use ($handle){
            $query->where('handle',$handle);
            })->first();

		if($profile !== null){
			$this->model = $profile;
			return $this->sendResponse();
		}

        $company = Company::where('handle',$handle)->first();

    	if($company !== null)
    	{
    		$this->model = ['company'=>$company]; 	 
    		return $this->sendResponse();
    	}
        return $this->sendError("Handle not found.");
	}
}