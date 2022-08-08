<?php 

namespace App\Http\Controllers\Api;

use App\Company;
use App\Deeplink;
use App\Jobs\SuperAdminMail;
use App\User;
use App\V2\CompanyUser;
use App\V2\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $this->model['seoTags'] = $company->getSeoTags();
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

    public function updateDetails(Request $request, $id)
    {
        $data = $request->only(["verified"]);

        foreach ($data as $key => $value) {
            if (is_null($value)) {
                unset($data[$key]);
            } else {
                $data[$key] = (int)$value;
            }
        }
        
        if (is_null($data) || empty($data)) {
            return $this->sendError("Please provide valid params such as 'verified'.");
        } else {
            $this->model = \App\Company::where('id',$id)->first();
            if ($this->model) {
                $this->model->update($data);
                $this->model->addToCache();
                $this->model->addToCacheV2();
                $this->model->addToGraph();
                return $this->sendResponse();
            } else {
                return $this->sendError("Invalid company id.");
            }
        }
    }
    
    function update_ownership(Request $request, $id){
        $company = Company::where('id',$id)->whereNull('deleted_at')->first();
        if(empty($company)){
            return $this->sendNewError("This company doesn't exist.");
        }
        
        if($company->user_id != $request->user()->id){
            return $this->sendNewError('You are not allowed to change the ownership of this company.');
        }

        //new superadmin profile id
        $profile_id = $request->profile_id;
        $profile = Profile::where('id',$profile_id)->whereNull('deleted_at')->first();
        $new_super_admin = User::where('id',$profile->user_id)->first();
        $is_company_admin = CompanyUser::where('company_id',$id)->where('profile_id',$profile_id)->first();

        if(empty($is_company_admin)){
            return $this->sendNewError('Requested user is not a company admin. He needs to be an admin first.');
        }
        $data = ['user_id'=>$profile->user_id, 'updated_at'=>Carbon::now()];
        if($company->update($data)){
            $this->model = $company;
            $this->model->addToCache();
            $this->model->addToCacheV2();
            $this->model->addToGraph();    
            
            $image = json_decode($profile->image_meta)->original_photo ?? '';
            $old_super_admin_data = ['name'=>$request->user()->name, 'email'=>$request->user()->email, 'new_super_admin'=>$new_super_admin->name, 'image'=>$image,'company_name'=>$company->name,'new_super_admin_id'=>$profile->id];
            
            $image = json_decode($company->logo_meta)->original_photo ?? '';
            $new_super_admin_data = ['name'=>$new_super_admin->name, 'email'=>$new_super_admin->email,'old_super_admin'=>$request->user()->name,'company_name'=>$company->name,'image'=>$image,'old_super_admin_id'=>$request->user()->profile->id,'company_id'=>$company->id];

            $mail_job = (new SuperAdminMail($old_super_admin_data, $new_super_admin_data));
            dispatch($mail_job);

            return $this->sendNewResponse(true);
        }else{
            return $this->sendNewError('Something went wrong. Please try again.');        }
    }
}
