<?php namespace App\Http\Controllers\Api\Profile\Company;

use App\Company\Portfolio;
use App\Http\Controllers\Controller;
use \Tagtaste\Api\SendsJsonResponse;
use Illuminate\Http\Request;

class PortfolioController extends Controller {

    use SendsJsonResponse;
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request, $profileId, $companyId)
	{
		$this->model = Portfolio::where('company_id',$companyId)->orderBy('id', 'desc')->paginate(10);
        return $this->sendResponse();
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
        $input = $request->only(['worked_for','description']);
        $input['company_id'] = $companyId;
		$this->model = Portfolio::create($input);
        return $this->sendResponse();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $profileId, $companyId,$id)
	{
		$this->model = Portfolio::where('company_id',$companyId)->where('id',$id)->first();

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
        $userId = $request->user()->id;
        $inputs = $request->only(['worked_for','description']);
        
        $this->model = Portfolio::whereHas('company.user',function($query) use ($userId){
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
        
        $this->model = Portfolio::whereHas('company.user',function($query) use ($userId){
            $query->where('id',$userId);
        })->where('id',$id)->where('company_id',$companyId)->delete();
        
        return $this->sendResponse();
	}

}
