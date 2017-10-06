<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class HandleController extends Controller
{
	public function getId(Request $request,$handle)
	{
	    $profile = \DB::table("profiles")->select("id")->where('handle','like',$handle)->first();
     
		if($profile !== null){
		    $this->model = ['model'=>'profile','id'=>$profile->id];
			return $this->sendResponse();
		}

        $company = \DB::table('companies')->select('id')->where('handle',$handle)->first();

    	if($company !== null)
    	{
    		$this->model = ['model'=>'company','id'=>$company->id];
    		return $this->sendResponse();
    	}
    	
        return $this->sendError("$handle Handle not found.");
	}
}