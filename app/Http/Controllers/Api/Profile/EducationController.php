<?php
namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Api\Controller;
use App\Education;
use App\Http\Requests;
use \Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\Request;

class EducationController extends Controller {

    use SendsJsonResponse;

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($profileId)
	{
        $this->model = Education::where('profile_id',$profileId)->get();
        return $this->sendResponse();
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
        $inputs = $request->except(['_method','_token']);
        $inputs['profile_id'] = $request->user()->profile->id;
        $this->model = Education::create($inputs);
        $this->model->addUserEducation();
        return $this->sendResponse();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($profileId,$id)
	{
        $this->model = Education::where('profile_id',$profileId)->where('id',$id)->first();
        if(!$this->model){
            throw new \Exception("Education not found.");
        }
        return $this->sendResponse();
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $profileId, $id)
	{
        $input = $request->except(['_method','_token']);
        $education = Education::where('id',$id)->where("profile_id",$profileId)->first();
        $education->detachUserEducation();
        $this->model = $education->update($input);
        $education->updateUserEducation();
        return $this->sendResponse();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $profileId, $id)
	{
		$education = Education::where('id',$id)->where("profile_id",$profileId)->first();
        $education->detachUserEducation();
        $this->model = Education::where('profile_id',$request->user()->profile->id)->where('id',$id)->delete();
        return $this->sendResponse();
	}

}
