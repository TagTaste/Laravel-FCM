<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Address;

class AddressController extends Controller
{
    //
    public function index($profileId)
    {
    	return \App\Profile::where('id',1)->get();
    	$this->model = Address::where('profile_id',$profileId)->get();
    	return $this->sendResponse();
    }

    public function store(Request $request)
    {
    	$inputs = $request->except(['_method','_token']);
        $inputs['profile_id'] = $request->user()->profile->id;
        $checkExisting = Address::where('label',$inputs['label'])->first();
        if(!is_null($checkExisting) || !empty($checkExisting))
        {
        	return $this->sendError("Same label already exists");
        }
        $this->model = Address::create($inputs);
        return $this->sendResponse();
    }

    public function destroy(Request $request, $profileId, $id)
    {
    	$this->model = Address::where('id',$id)->where('profile_id',$profileId)->delete();
    	return $this->sendResponse();
    }

    public function update(Request $request, $profileId, $id)
	{
        $input = $request->except(['_method','_token']);
        \Log::info($input);
        $checkExisting = Address::where('label',$input['label'])->first();
    	if(!is_null($checkExisting) || !empty($checkExisting))
    	{
    		return $this->sendError("Same label already exists");
    	}
        $this->model = Address::where('id',$id)->where("profile_id",$request->user()->profile->id)->update($input);
        return $this->sendResponse();
	}
}
