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
        $this->model = Company::with('status','type');

        $filters = $request->input('filters');
        if (!empty($filters['city'])) {
            $this->model=$this->model->whereIn('city',$filters['city']);
        }
        if (!empty($filters['type'])) {
            $this->model=$this->model->whereIn('type',$filters['type']);
        }
        if (!empty($filters['status'])) {
            $this->model=$this->model->whereIn('status_id',$filters['status']);
        }
        
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $this->model=$this->model->orderBy('id', 'desc')->skip($skip)->take($take)->get();
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
        $filters['location'] = \App\Filter\Company::select('city as value')
            ->groupBy('city')->where('city','!=','null')->get();
        $filters['types'] = \App\Company\Type::select('id as key','name as value')->get();
        $filters['status'] = \App\Company\Status::select('id as key','name as value')->get();

        $this->model = $filters;
        return $this->sendResponse();
    }

}
