<?php namespace App\Http\Controllers\Api\Profile\Company;

use App\Http\Controllers\Api\Controller;

use App\Company\Patent;
use Illuminate\Http\Request;

class PatentController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request, $profileId, $companyId)
	{
		$this->model = Patent::where('company_id',$companyId)->paginate(10);
        return $this->sendResponse();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
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
            throw new \Exception("This user does not own this company.");
        }
        $inputs = $request->only(['title','description','number','issued_by','awarded_on']);
        $inputs['company_id'] = $company->id;

        $this->model = Patent::create(array_filter($inputs));
        return $this->sendResponse();
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($profileId, $companyId, $id)
	{
		$this->model = Patent::where('id',$id)->where('company_id',$companyId)->first();
		return $this->sendResponse();
    }

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($profileId, $companyId, $id)
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
        $userId = $request->user()->id;
        $inputs = $request->only(['title','description','number','issued_by','awarded_on']);
        $inputs = array_filter($inputs);

        $this->model = Patent::whereHas('company.user',function($query) use ($userId){
            $query->where('id',$userId);
        })->where('id',$id)->where('company_id',$companyId)->update($inputs);

        return $this->sendResponse();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $profileId, $companyId, $id)
	{
        $userId = $request->user()->id;

        $this->model = Patent::whereHas('company.user',function($query) use ($userId){
            $query->where('id',$userId);
        })->where('id',$id)->where('company_id',$companyId)->delete();

        return $this->sendResponse();
	}

}
