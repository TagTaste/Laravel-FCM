<?php 

namespace App\Http\Controllers\Api;

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
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        
        if(empty($filters)){
            $totalCount = $this->model->count();
            //paginate
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
        
        $companiesIds = \App\Filter\Company::getModelIds($filters,$skip,$take);
        if(!is_array($companiesIds))
        {
            $companiesIds = $companiesIds->toArray();
        }
        $profileId = $request->user()->profile->id;
        $companies = $this->model->whereIn('id',$companiesIds)->orderBy('id', 'desc')->skip($skip)->take($take)->get();
        $this->model = [];

        foreach($companies as &$company){
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
        if(!$company){
            return $this->sendError("Company not found.");
        }
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
        $this->model = \App\Filter::getFilters("company");
        return $this->sendResponse();
    }

    public function getUserWithoutAdmin(Request $request ,$id)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $query = $request->input('term');

        $profileIds = \DB::table('company_users')->where('company_id',$id)->get()->pluck('profile_id')->toArray();

        $this->model = \App\Recipe\Profile::select('profiles.*')->join('users','profiles.user_id','=','users.id')
            ->where('profiles.id','!=',$loggedInProfileId)->whereNotIn('profiles.id',$profileIds)->where('users.name','like',"%$query%")->take(15)->get();
        return $this->sendResponse();
    }

}
