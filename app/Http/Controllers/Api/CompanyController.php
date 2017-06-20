<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Api\Controller;

use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->model = Company::with('status','types')->orderBy('id', 'desc')->paginate(10);
  
		return $this->sendResponse();
	}
 

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
    {
        $this->model = Company::where('id',$id)->with('status','types')->first();
        
        if(!$this->model){
            return $this->sendError("Company not found.");
        }
        
        return $this->sendResponse();
    }
}
