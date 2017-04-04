<?php namespace App\Http\Controllers\Api\Profile\Company;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Company\Website;
use App\Http\Api\SendsJsonResponse;
use Illuminate\Http\Request;

class WebsiteController extends Controller {

    use SendsJsonResponse;
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($profileId, $companyId)
	{
		$this->model = Website::where('company_id',$companyId)->paginate(10);
        return $this->sendResponse();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($companyId)
	{
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request,$profileId,$companyId)
	{
        $company = $request->user()->companies()->where('id',$companyId)->first();
        if(!$company){
            throw new \Exception("You don't have the rights to add websites to this company.");
        }

        $this->model = $company->websites()->create(['name'=>$request->input('name'),'url'=>$request->input('url')]);
        return $this->sendResponse();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($profileId,$companyId, $id)
	{
		$this->model = Website::where('company_id',$companyId)->where('id',$id)->first();

		return $this->sendResponse();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $profileId, $companyId,$id)
	{
        $company = $request->user()->companies()->where('companies.id','=',$companyId)->first();
        if(!$company){
            throw new \Exception("You don't have the rights to edit websites of this company.");
        }
        $this->model = $company->websites()->where('id',$id)->first();
        return $this->sendResponse();
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
            throw new \Exception("You don't have the rights to update websites of this company.");
        }
        $this->model = $company->websites()->where('id',$id)->update(['name'=>$request->input('name'),'url'=>$request->input('url')]);
        return $this->sendResponse();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $profileId,$companyId, $id)
	{
        $company = $request->user()->companies()->where('id',$companyId)->first();
        if(!$company){
            throw new \Exception("You don't have the rights to delete websites of this company.");
        }
        $this->model = $company->websites()->where("id",$id)->delete();
        return $this->sendResponse();
	}

}
