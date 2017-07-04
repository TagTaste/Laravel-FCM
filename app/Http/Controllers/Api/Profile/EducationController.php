<?php
namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Api\Controller;
use App\Education;
use App\Http\Requests;
use \Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\Request;

class EducationController extends Controller {

    use SendsJsonResponse;

    private $fields = ['degree','college','field','grade','percentage','description','start_date','end_date','ongoing'];


    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($profileId)
	{
	    \Log::info('here');
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
        $this->model = $request->user()->profile->education()->create($request->only($this->fields));
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
            throw new \Exception("Book not found.");
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
        $input = $request->only($this->fields);
        $input = array_filter($input);
        if(isset($input['start_date'])){
            $input['start_date'] = date('Y-m-d',strtotime($input['start_date']));
        }
        if(isset($input['end_date'])){
            $input['end_date'] = date('Y-m-d',strtotime($input['end_date']));
        }
        $this->model = $request->user()->profile->education()->
        where('id',$id)->update($input);
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
        $this->model = $request->user()->profile->education()->where('id',$id)->delete();
        return $this->sendResponse();
	}

}
