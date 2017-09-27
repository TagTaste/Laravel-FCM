<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Collaborate;
use App\Company;
use App\Events\DeleteFeedable;
use App\Events\NewFeedable;
use App\Http\Controllers\Api\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CollaborateController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var collaborate
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(Collaborate $model)
	{
		$this->model = $model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request, $profileId,$companyId)
	{
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $collaborations = $this->model->where('company_id',$companyId)->whereNull('deleted_at')->orderBy('created_at','desc')->skip($skip)->take($take)->get();

        $profileId = $request->user()->profile->id;
        $this->model = [];
        foreach($collaborations as $collaboration){
            $this->model[] = ['collaboration'=>$collaboration,'meta'=>$collaboration->getMetaFor($profileId)];
        }
//        if($request->has('categories')){
//            $categories = $request->input('categories');
//            $this->model = $this->model->whereHas('categories',function($query) use ($categories){
//                $query->whereIn('category_id',$categories);
//            });
//        }
        return $this->sendResponse();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request, $profileId, $companyId)
	{
        $profileId=$request->user()->profile->id;
		$inputs = $request->all();
		$inputs['company_id'] = $companyId;
        $inputs['profile_id'] = $profileId;

        $inputs['expires_on'] = Carbon::now()->addMonth()->toDateTimeString();

        $fields = $request->has("fields") ? $request->input('fields') : [];

        if(!empty($fields)){
            unset($inputs['fields']);
        }
        //save images
        for ($i = 1; $i <= 5; $i++) {
            if (!$request->hasFile("image$i")) {
                break;
            }
            $imageName = str_random("32") . ".jpg";
            $relativePath = "images/p/$profileId/c/$companyId/collaborate";
            $inputs["image$i"] = $request->file("image$i")->storeAs($relativePath, $imageName,['visibility'=>'public']);
        }
        
        if($request->hasFile('file1')){
            $relativePath = "images/p/$profileId/c/$companyId/collaborate";
            $name = \Input::file('file1')->getClientOriginalName();
            $extension = \Input::file('file1')->getClientOriginalExtension();
            $inputs["file1"] = $request->file("file1")->storeAs($relativePath, $name . "." . $extension,['visibility'=>'public']);
        }
        
        $this->model = $this->model->create($inputs);
//        $categories = $request->input('categories');
//        $this->model->categories()->sync($categories);
//        $this->model->syncFields($fields);
        $company = Company::find($companyId);
        $this->model = $this->model->fresh();
        event(new NewFeedable($this->model,$company));
        return $this->sendResponse();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request,$profileId, $companyId, $id)
	{
        $collaboration = $this->model->where('company_id',$companyId)->whereNull('deleted_at')->find($id);
        if ($collaboration === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;
        $meta = $collaboration->getMetaFor($profileId);
        $this->model = ['collaboration'=>$collaboration,'meta'=>$meta];
		return $this->sendResponse();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $profileId, $companyId, $id)
	{
		$inputs = $request->all();
		$collaborate = $this->model->where('company_id',$companyId)->where('id',$id)->first();
        unset($inputs['expires_on']);
		if($collaborate === null){
		    return $this->sendError("Collaboration not found.");
        }
        
//        if(!empty($fields)){
//            unset($inputs['fields']);
//
//            $this->model->syncFields($fields);
//        }
//        $categories = $request->input('categories');
//        $this->model->categories()->sync($categories);

        for ($i = 1; $i <= 5; $i++) {
            if ($request->hasFile("image$i")) {
                $imageName = str_random("32") . ".jpg";
                $relativePath = "images/p/$profileId/collaborate";
                $inputs["image$i"] = $request->file("image$i")->storeAs($relativePath, $imageName,['visibility'=>'public']);
            }
        }
        
        if($request->hasFile('file1')){
            $relativePath = "images/p/$profileId/c/$companyId/collaborate";
            $name = \Input::file('file1')->getClientOriginalName();
            $extension = \Input::file('file1')->getClientOriginalExtension();
            $inputs["file1"] = $request->file("file1")->storeAs($relativePath, $name . "." . $extension,['visibility'=>'public']);
        }
        $this->model = $collaborate->update($inputs);
        return $this->sendResponse();
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $profileId, $companyId, $id)
	{
        $collaborate = $this->model->where('company_id',$companyId)->where('id',$id)->first();
        
        if($collaborate === null){
            return $this->sendError( "Collaboration not found.");
        }
        event(new DeleteFeedable($collaborate));

        $this->model = $collaborate->delete();
        return $this->sendResponse();
	}
    
    public function approve(Request $request, $profileId, $companyId, $id)
    {
        $collaborate = $this->model->where('company_id',$companyId)->where('id',$id)->first();
        
        if($collaborate === null){
            return $this->sendError( "Collaboration not found.");
        }
        
        if($request->has('company_id')){
            $companyId = $request->input('company_id');
            $company =  Company::find($companyId);
            if(!$company){
                return $this->sendError( "Company not found.");
            }
            
            return $collaborate->approveCompany($company);
        }
        
        if($request->has('profile_id')){
            $inputProfileId = $request->input('profile_id');
            $profile =  Profile::find($inputProfileId);
            if(!$profile){
                return $this->sendError( "Profile not found.");
            }
            
            return $collaborate->approveProfile($profile);
        }
    }
    
    public function reject(Request $request, $profileId, $companyId, $id)
    {
        $collaborate = $this->model->where('company_id',$companyId)->where('id',$id)->first();
    
        if($collaborate === null){
            return $this->sendError( "Collaboration not found.");
        }
    
        if($request->has('company_id')){
            $companyId = $request->input('company_id');
            $company =  Company::find($companyId);
            if(!$company){
                return $this->sendError( "Company not found.");
            }
        
            return $collaborate->rejectCompany($company);
        }
    
        if($request->has('profile_id')){
            $inputProfileId = $request->input('profile_id');
            $profile =  Profile::find($inputProfileId);
            if(!$profile){
                return $this->sendError( "Profile not found.");
            }
        
            return $collaborate->rejectProfile($profile);
        }
    }
}