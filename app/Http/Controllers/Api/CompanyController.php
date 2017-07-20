<?php namespace App\Http\Controllers\Api;

use App\Company;
use App\CompanyRating;
use Illuminate\Http\Request;

class CompanyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $this->model = Company::with('status','type')->orderBy('id', 'desc')->paginate(10);

		return $this->sendResponse();
	}
 

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request,$id)
    {
        $company = Company::where('id',$id)->with('status','type')->first();
        $this->model = $company->toArray();
        $this->model['userRating'] = CompanyRating::where('company_id',$id)->where('profile_id',$request->user()->profile->id)->first();
        if(!$this->model){
            return $this->sendError("Company not found.");
        }
        
        return $this->sendResponse();
    }

    public function filters()
    {
        $filters = [];

        $filters['location'] = \App\Filter\Company::select('registered_address')
            ->groupBy('registered_address')->where('registered_address','!=','null')->get();
        $filters['types'] = \App\Company\Type::select('id','name')->groupBy('id')->get();
        $filters['status'] = \App\Company\Status::select('id','name')->groupBy('id')->get();
        $filters['employee_count'] = \App\Filter\Company::select('employee_count')
            ->groupBy('employee_count')->where('employee_count','!=','null')->get();

        $this->model = $filters;
        return $this->sendResponse();    }
}
