<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Profile\ShippingAddress as Address;

class ShippingAddressController extends Controller
{
    //
    public function index($profileId)
    {
    	$this->model = Address::where('profile_id',$profileId)->get();
    	return $this->sendResponse();
    }

    public function store(Request $request)
    {
    	$inputs = $request->except(['_method','_token']);
        $inputs['profile_id'] = $request->user()->profile->id;
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
        $address = Address::where('id',$id)->exists();
        if(!$address)
        {
            return $this->sendError("Id doesnt exist");
        }
        $checkExisting = Address::where('id',$id)->where('profile_id',$request->user()->profile->id)->exists();
        if(!$checkExisting)
        {
            return $this->sendError("This address doesnt bleong to this user");
        }
        $this->model = Address::where('id',$id)->where("profile_id",$request->user()->profile->id)->update($input);
        return $this->sendResponse();
	}
}
