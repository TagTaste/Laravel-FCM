<?php namespace App\Http\Controllers\Api;

use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$companies = Company::with('status','type')->orderBy('id', 'desc')->paginate(10);

        $profileId = $request->user()->profile->id;
        $this->model = [];
        foreach($companies as $company){
            //firing multiple queries for now.
            $temp = $company->toArray();
            $this->model['company_rating']=$company->companyRating($company->id);
            $this->model['your_rating']=$company->yourRating($company->id,$profileId);
            $this->model[] = $temp;
        }
  
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
        $profileId=$request->user()->profile->id;
        $this->model = Company::where('id',$id)->with('status','type')->first();
        $this->model['company_rating']=$this->model->companyRating($id);
        $this->model['your_rating']=$this->model->yourRating($id,$profileId);
        if(!$this->model){
            return $this->sendError("Company not found.");
        }
        
        return $this->sendResponse();
    }
}
