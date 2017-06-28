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
        $this->model = Company::where('id',$id)->with('status','type')->first();
        if(!$this->model){
            return $this->sendError("Company not found.");
        }
        
        return $this->sendResponse();
    }
}
