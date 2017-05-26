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
		$profile = User::whereHas("profile",function($query) use ($handle){
            $query->where('handle',$handle);
            })->first();
            
        if($profile === null)
        {
            $company = Company::has('user.profile')->where('handle',$handle)->first();

        	if($company === null)
        	{
            throw new \Exception("Invalid Handle.");
        	}
        	return response()->json($company);
        }

        return response()->json($profile);

	}
}