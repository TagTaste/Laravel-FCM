<?php namespace App\Http\Controllers\Api\Profile;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Professional;
use \Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\Request;

class ProfessionalController extends Controller {

    use SendsJsonResponse;
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($profileId)
	{
		$this->model = Professional::where('profile_id',$profileId)->orderBy('id', 'desc')->paginate(10);
		return $this->sendResponse();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $profileId)
	{
        $input = array_filter($request->only('favourite_moments','famous_recipes','cuisine','designation'));
	    $this->model = $request->user()->profile->professional()->create($input);
		return $this->sendResponse();
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($profileId, $id)
	{
		$this->model = Professional::forProfile($profileId)->where('id',$id)->first();

		if(!$this->model){
		    throw new \Exception("Professional Information not found for the given profile.");
        }

        return $this->sendResponse();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
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
	    $input = $request->only(['favourite_moments','famous_recipes','cuisine','designation']);
		$this->model = $request->user()->profile->professional()->where('id',$id)
            ->update($input);
        return $this->sendResponse();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request,$profileId, $id)
	{
        $this->model = $request->user()->profile->professional()->where('id',$id)->delete();
        return $this->sendResponse();
	}

}
