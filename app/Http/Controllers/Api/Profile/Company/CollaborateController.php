<?php

namespace App\Http\Controllers\Api\Profile\Company;

use App\Collaborate;
use App\Company;
use App\Events\DeleteFeedable;
use App\Events\NewFeedable;
use App\Http\Controllers\Api\Controller;
use App\Profile;
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
        $collaborations = $this->model->where('company_id',$companyId)->whereNull('deleted_at')
            ->orderBy('created_at','desc');

        $profileId = $request->user()->profile->id;
        $this->model = [];
        $data = [];
        $this->model['count'] = $collaborations->count();
        $collaborations = $collaborations->skip($skip)->take($take)->get();
        foreach($collaborations as $collaboration){
            $data[] = ['collaboration'=>$collaboration,'meta'=>$collaboration->getMetaFor($profileId)];
        }
        $this->model['collaborations'] = $data;
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
        $profile = $request->user()->profile;
        $profileId = $profile->id ;
        $inputs = $request->all();
        $inputs['company_id'] = $companyId;
        $inputs['profile_id'] = $profileId;

        $inputs['state'] = Collaborate::$state[0];

        $inputs['expires_on'] = Carbon::now()->addMonth()->toDateTimeString();
        $fields = $request->has("fields") ? $request->input('fields') : [];

        if(!empty($fields)){
            unset($inputs['fields']);
        }
        $imagesArray = [];
        if ($request->has("images"))
        {
            $images = $request->input('images');
            $i = 1;
            foreach ($images as $image)
            {
                if(is_null($image))
                    continue;
                $imagesArray[]['image'.$i] = $image;
                $i++;
            }
        }
        $inputs['images'] = json_encode($imagesArray,true);
        if($request->hasFile('file1')){
            $relativePath = "images/p/$profileId/company/$companyId/collaborate";
            $name = $request->file('file1')->getClientOriginalName();
            $extension = \File::extension($request->file('file1')->getClientOriginalName());
            $inputs["file1"] = $request->file("file1")->storeAs($relativePath, $name . "." . $extension,['visibility'=>'public']);
        }

        $this->model = $this->model->create($inputs);
//        $categories = $request->input('categories');
//        $this->model->categories()->sync($categories);
//        $this->model->syncFields($fields);
        $company = Company::find($companyId);
        $this->model = $this->model->fresh();

        //push to feed
        event(new NewFeedable($this->model,$company));

        //add to filters
        \App\Filter\Collaborate::addModel($this->model);

        //add subscriber
        event(new \App\Events\Model\Subscriber\Create($this->model,$profile));

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
        $collaboration = $this->model->where('id',$id)->where('company_id',$companyId)->where('state','!=',Collaborate::$state[1])->first();
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
        unset($inputs['profile_id']);
        unset($inputs['expires_on']);


        $collaborate = $this->model->where('company_id',$companyId)->where('id',$id)->first();
        if($collaborate === null){
            return $this->sendError("Collaboration not found.");
        }

        unset($inputs['images']);
        $imagesArray = [];
        if ($request->has("images"))
        {
            $images = $request->input('images');
            $i = 1;
            if(count($images) && is_array($images))
            {
                foreach ($images as $image)
                {
                    if(is_null($image))
                        continue;
                    $imagesArray[]['image'.$i] = $image;
                    $i++;
                }
            }
            $inputs['images'] = json_encode($imagesArray,true);
        }

        if($request->hasFile('file1')){
            $relativePath = "images/p/$profileId/collaborate";
            $name = $request->file('file1')->getClientOriginalName();
            $extension = \File::extension($request->file('file1')->getClientOriginalName());
            $inputs["file1"] = $request->file("file1")->storeAs($relativePath, $name . "." . $extension,['visibility'=>'public']);
        }

        if($collaborate->state == 'Expired')
        {
            $inputs['state'] = Collaborate::$state[0];
            $inputs['deleted_at'] = null;
            $inputs['created_at'] = Carbon::now()->toDateTimeString();
            $inputs['updated_at'] = Carbon::now()->toDateTimeString();
            $inputs['expires_on'] = Carbon::now()->addMonth()->toDateTimeString();
            $this->model = $collaborate->update($inputs);

            $collaborate->addToCache();

            $company = Company::find($companyId);
            $this->model = Collaborate::find($id);

            event(new NewFeedable($this->model, $company));
            \App\Filter\Collaborate::addModel($this->model);

            return $this->sendResponse();
        }

        $this->model = $collaborate->update($inputs);

        \App\Filter\Collaborate::addModel(Collaborate::find($id));

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

        //send notificants to collaboraters for delete collab
        $profileIds = \DB::table("collaborate_applicants")->where("collaborate_id",$id)->get()->pluck('profile_id');
        foreach ($profileIds as $profileId)
        {
            $collaborate->profile_id = $profileId;
            event(new \App\Events\Actions\DeleteModel($collaborate, $request->user()->profile));
        }

        //remove filters
        \App\Filter\Collaborate::removeModel($id);

        $this->model = $collaborate->update(['deleted_at'=>Carbon::now()->toDateTimeString(),'state'=>Collaborate::$state[1]]);
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
            $this->model = $collaborate->approveCompany($company);
            return $this->sendResponse();
        }

        if($request->has('profile_id')){
            $inputProfileId = $request->input('profile_id');
            $profile =  Profile::find($inputProfileId);
            if(!$profile){
                return $this->sendError( "Profile not found.");
            }
            $this->model = $collaborate->approveProfile($profile);
            return $this->sendResponse();
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
            $this->model = $collaborate->rejectCompany($company);
            return $this->sendResponse();
        }

        if($request->has('profile_id')){
            $inputProfileId = $request->input('profile_id');
            $profile =  Profile::find($inputProfileId);
            if(!$profile){
                return $this->sendError( "Profile not found.");
            }
            $this->model = $collaborate->rejectProfile($profile);
            return $this->sendResponse();
        }
    }

    public function expired(Request $request,$profileId, $companyId)
    {
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $collaborations = $this->model->where('company_id',$companyId)->where('state',Collaborate::$state[2])->orderBy('deleted_at','desc');
        $this->model = [];
        $data = [];
        $this->model['count'] = $collaborations->count();
        $collaborations = $collaborations->skip($skip)->take($take)->get();
        $profileId = $request->user()->profile->id;
        foreach($collaborations as $collaboration){
            $data[] = ['collaboration'=>$collaboration,'meta'=>$collaboration->getMetaFor($profileId)];
        }
        $this->model['collaborations'] = $data;
        return $this->sendResponse();

    }

    public function interested(Request $request, $profileId, $companyId)
    {
        $profileId = $request->user()->profile->id;
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $collaborations = $this->model->select('collaborate_id','collaborates.*')
            ->join('collaborate_applicants','collaborate_applicants.collaborate_id','=','collaborates.id')
            ->where("collaborate_applicants.company_id",$companyId)->where("collaborates.state",Collaborate::$state[0]);
        dd($collaborations);
        $this->model = [];
        $data = [];
        $this->model['count'] = $collaborations->count();
        $collaborations = $collaborations->skip($skip)->take($take)->get();
        foreach ($collaborations as $collaboration) {
            $data[] = ['collaboration' => $collaboration, 'meta' => $collaboration->getMetaFor($profileId)];
        }
        $this->model['collaborations'] = $data;
        return $this->sendResponse();

    }
}