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

        if(empty($inputs['review'])){
            unset($inputs['review']);
        }
        
        $this->model->where('company_id', $companyId)
            ->where('profile_id', $inputs['profile_id'])->delete();

        $companyRating = $this->model->create($inputs);
        $this->model = [];
        $this->model['avg_rating'] = $companyRating->where('company_id',$companyId)->avg('rating');
        $this->model['review_count'] = $companyRating->where('company_id',$companyId)->whereNotNull('review')->count();
        $this->model['rating_count'] = $companyRating->where('company_id',$companyId)->count();
        $this->model['my_review'] = $companyRating;
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
