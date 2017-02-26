<?php namespace App\Http\Controllers\Api\Profile\Company;

use App\Http\Controllers\Api\Controller;

use App\Company\Address;
use Illuminate\Http\Request;

class AddressController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request, $profileId, $companyId)
	{
		$company = $request->user()->companies()->where('id',$companyId)->first();

		if(!$company){
		    throw new \Exception("No company with id " . $companyId . ".");
        }

        $this->model = $company->addresses()->paginate(10);
		return $this->sendResponse();
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//return view('company_locations.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $profileId, $companyId)
	{
        $company = $request->user()->companies()->where('id',$companyId)->first();

        if(!$company){
            throw new \Exception("No company with id " . $companyId . ".");
        }

        $this->model = $company->addresses()->create(['address','country','phone']);
        return $this->sendResponse();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $profileId, $companyId, $id)
	{
        $company = $request->user()->companies()->where('id',$companyId)->first();

        if(!$company){
            throw new \Exception("No company with id " . $companyId . ".");
        }
		$this->model = $company->addresses;
        return $this->sendResponse();

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($profileId, $companyId,$id)
	{
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $profileId, $companyId, $id)
	{
        $company = $request->user()->companies()->where('id',$companyId)->first();

        if(!$company){
            throw new \Exception("No company with id " . $companyId . ".");
        }

        $this->model = $company->addresses()->where('id',$id)
            ->update($request->only(['address','country','phone']));
        return $this->sendResponse();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $profileId, $companyId,$id)
	{
        $company = $request->user()->companies()->where('id',$companyId)->first();

        if(!$company){
            throw new \Exception("No company with id " . $companyId . ".");
        }
        $this->model = $company->addresses()->where('id',$id)->delete();
        return $this->sendResponse();
	}

}
