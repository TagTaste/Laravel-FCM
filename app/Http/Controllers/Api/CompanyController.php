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
        $this->model = Company::with('status','type');
        
        $filters = $request->input('filters');
        if(empty($filters)){
            $totalCount = $this->model->count();
            //paginate
            $page = $request->input('page');
            list($skip,$take) = \App\Strategies\Paginator::paginate($page);
            $companies = $this->model->orderBy('id', 'desc')->skip($skip)->take($take)->get();
    
            $profileId = $request->user()->profile->id;
            $this->model = [];
            $this->model['data'] = [];
            foreach($companies as $company){
                $temp = $company->toArray();
                $temp['isFollowing'] = Company::checkFollowing($profileId,$company['id']);
        
                $this->model['data'][] = $temp;
            }
            $this->model['count'] = $totalCount;
            return $this->sendResponse();
        }
        
        $companyIds = \App\Cached\Filter\Company::getModelIds($filters);
        foreach($companyIds as &$id){
            $id = "company:small:" . $id;
        }
        $companies = \Redis::mget($companyIds);
        $profileId = $request->user()->profile->id;
        $this->model = [];
        foreach($companies as $company){
            $company = json_decode($company,true);
            $company['isFollowing'] = Company::checkFollowing($profileId,$company['id']);
        }
        $this->model['data'] = $companies;
        $this->model['count'] = count($companies);

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
        $this->model['isFollowing'] = $company->isFollowing($profileId);
        if(!$this->model){
            return $this->sendError("Company not found.");
        }
        
        return $this->sendResponse();
    }

    public function filters()
    {
        $this->model = \App\Cached\Filter\Company::getFilters();
    
        foreach($this->model as &$filter){
            foreach($filter as &$value){
                $value = ['value'=>$value];
            }
        }
//        $filters = [];
//        $filters['location'] = \App\Filter\Company::select('city as value')
//            ->groupBy('city')->where('city','!=','null')->get();
//        $filters['types'] = \App\Company\Type::select('id as key','name as value')->get();
//        $filters['status'] = \App\Company\Status::select('id as key','name as value')->get();
////        $keywords = \App\Filter\Company::select('speciality')->whereNotNull('speciality')->take(10)->get();
////        $filters['speciality'] = [];
////        foreach($keywords as $keyword){
////            $filters['speciality'] = array_merge($filters['speciality'],explode(",",$keyword->speciality));
////        }
////        \Log::info($filters['speciality']);
//        $this->model = $filters;
        return $this->sendResponse();
    }

}
