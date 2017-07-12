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
    
    
    public function rating(Request $request, $companyId)
    {
        $company = Company::find($companyId);
        
        if (!$company) {
            return $this->sendError("Company doesn't exist.");
        }
        
        $inputs = $request->all();
        
        $inputs['company_id'] = $companyId;
        $inputs['profile_id'] = $request->user()->profile->id;
        
        $userRating = $this->model->where('company_id', $companyId)
            ->where('profile_id', $inputs['profile_id'])->first();
        
        if($userRating){
            $userRating->rating = $inputs['rating'];
            $userRating->save();
            $this->model = $userRating;
            return $this->sendResponse();
        }
        
        $this->model = $this->model->create($inputs);
        return $this->sendResponse();
    }



}