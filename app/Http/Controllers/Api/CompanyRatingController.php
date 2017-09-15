<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\CompanyRating;
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

        $this->model->where('company_id', $companyId)
            ->where('profile_id', $inputs['profile_id'])->delete();

        $this->model = $this->model->create($inputs);
        return $this->sendResponse();
    }

    public function getRating(Request $request, $companyId)
    {
        $company = Company::where("id",$companyId)->exists();

        if (!$company) {
            return $this->sendError("Company doesn't exist.");
        }
        $this->model = [];
        $companyRating = CompanyRating::where("company_id",$companyId)->where('profile_id','!=',$request->user()->profile->id)->get();
        $this->model['company_review'] = $companyRating->toArray();
        $this->model['my_review'] = CompanyRating::where("company_id",$companyId)->where('profile_id',$request->user()->profile->id)->first();
        return $this->sendResponse();

    }



}
