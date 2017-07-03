<?php

namespace App\Http\Controllers\Api;

use App\CompanyRating;
use App\Company;
use Illuminate\Http\Request;

class CompanyRatingController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var company_rating
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(CompanyRating $model)
	{
		$this->model = $model;
	}


	public function rating(Request $request,$companyId)
	{
        $inputs = $request->all();
        $inputs['company_id']=$companyId;
        $inputs['profile_id']=$request->user()->profile->id;
        $company = Company::find($inputs['company_id']);
        if(!$company){
            throw new \Exception("Company doesn't exist.");
        }
        if($company->rating->profile_id){
            $this->model=$this->model->where('company_id',$companyId)
                ->where('profile_id',$inputs['profile_id'])->update($inputs);
            return $this->sendResponse();
        }

        $this->model = $this->model->create($inputs);
        return $this->sendResponse();
	}



}