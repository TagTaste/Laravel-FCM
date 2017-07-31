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
        $companies = $this->model->orderBy('id', 'desc')->skip($skip)->take($take)->get();
        $companies = $companies->keyBy('id');

        $profileId = $request->user()->profile->id;
        $followers  = \DB::table('subscribers')->where('profile_id',$profileId)->whereIn('company_id',$companies->pluck('id'))->get();
        
        $this->model = [];
        if($followers->count()){
            foreach($followers as $follower){
                $temp = $companies->get($follower->company_id)->toArray();
                $temp['isFollowing'] = true;
                $this->model[] = $temp;
            }
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
        $company = Company::where('id',$id)->with('status','type')->first();
        $profileId = $request->user()->profile->id;
        $this->model = $company->toArray();
        $this->model['userRating'] = CompanyRating::where('company_id',$id)->where('profile_id',$profileId)->first();
        $this->model['isFollowing'] = $company->isFollowing($profileId);
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
