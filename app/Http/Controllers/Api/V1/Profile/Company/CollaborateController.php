<?php

namespace App\Http\Controllers\Api\V1\Profile\Company;

use App\Collaborate;
use App\Company;
use App\CompanyUser;
use App\Events\DeleteFeedable;
use App\Events\NewFeedable;
use App\Events\UploadQuestionEvent;
use App\Http\Controllers\Api\Controller;
use App\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

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
        $loggedInProfileId = $request->user()->profile->id;
        $checkAdmin = CompanyUser::where('company_id',$companyId)->where('profile_id',$loggedInProfileId)->exists();
        if(!$checkAdmin){
            return $this->sendError("Invalid Admin.");
        }


        $profile = $request->user()->profile;
        $profileId = $profile->id ;
        $inputs = $request->all();
        $inputs['state'] = 1;
        if(isset($inputs['collaborate_type']) && $inputs['collaborate_type'] == 'product-review')
        {
            $checkCompanyPremium = Company::where('id',$companyId)->where('is_premium',1)->exists();
            if(!$checkCompanyPremium)
            {
                return $this->sendError("This company do not have premium account");
            }
            $inputs['step'] = 1;
            $inputs['state'] = 4;
        }
        $inputs['company_id'] = $companyId;
        $inputs['profile_id'] = $profileId;
        $inputs['expires_on'] = isset($inputs['expires_on']) && !is_null($inputs['expires_on'])
                    ? $inputs['expires_on'] : Carbon::now()->addMonth()->toDateTimeString();
        $fields = $request->has("fields") ? $request->input('fields') : [];

        if(!empty($fields)){
            unset($inputs['fields']);
        }
        //save images
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

        if($request->has('allergens_id'))
        {
            $allergensIds = $request->input('allergens_id');
            $allergens = [];
            foreach ($allergensIds as $allergensId)
            {
                $allergens[] = ['collaborate_id'=>$this->model->id,'allergens_id'=>$allergensId];
            }
            if(count($allergens))
            {
                Collaborate\Allergens::where('collaborate_id',$this->model->id)->delete();
                $this->model->collaborate_allergens()->insert($allergens);
            }
        }
        $this->model = $this->model->fresh();

        if($this->model->collaborate_type != 'product-review')
        {
            //push to feed
            event(new NewFeedable($this->model,$company));

            //add to filters
            \App\Filter\Collaborate::addModel($this->model);

            //add subscriber
            event(new \App\Events\Model\Subscriber\Create($this->model,$profile));
        }

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
        $loggedInProfileId = $request->user()->profile->id;

        $checkAdmin = CompanyUser::where('company_id',$companyId)->where('profile_id',$loggedInProfileId)->exists();
        if(!$checkAdmin){
            return $this->sendError("Invalid Admin.");
        }
        $inputs = $request->all();
        unset($inputs['profile_id']);
        unset($inputs['state']);
        unset($inputs['step']);
        $collaborate = $this->model->where('company_id',$companyId)->where('id',$id)->first();
        if($collaborate === null){
            return $this->sendError("Collaboration not found.");
        }
        if(isset($inputs['expires_on']) && !is_null($inputs['expires_on']))
        {
            $inputs['expires_on'] = Carbon::now()->addMonth($inputs['expires_on'])->toDateTimeString() ;
        }
        else
        {
            unset($inputs['expires_on']);
        }

        if($collaborate->collaborate_type == 'collaborate')
            unset($inputs['expires_on']);

        unset($inputs['images']);
        $imagesArray = [];
        if ($request->has("images"))
        {
            $images = $request->input('images');
            $i = 1;
            if(count($images) > 0)
            {
                foreach ($images as $image)
                {
                    if(is_null($image))
                        continue;
                    $imagesArray[]['image'.$i] = $image;
                    $i++;
                }
                $inputs['images'] = json_encode($imagesArray,true);
            }
            else
                $inputs[$images] = null;
        }

        if($request->hasFile('file1')){
            $relativePath = "images/p/$profileId/collaborate";
            $name = $request->file('file1')->getClientOriginalName();
            $extension = \File::extension($request->file('file1')->getClientOriginalName());
            $inputs["file1"] = $request->file("file1")->storeAs($relativePath, $name . "." . $extension,['visibility'=>'public']);
        }

        if($request->has('allergens_id'))
        {
            $allergensIds = $request->input('allergens_id');
            $allergens = [];
            foreach ($allergensIds as $allergensId)
            {
                $allergens[] = ['collaborate_id'=>$collaborate->id,'allergens_id'=>$allergensId];
            }
            if(count($allergens))
            {
                Collaborate\Allergens::where('collaborate_id',$collaborate->id)->delete();
                $collaborate->collaborate_allergens()->insert($allergens);
            }
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
        $inputs['privacy_id'] = 1;
        $this->model = $collaborate->update($inputs);
        $this->model = Collaborate::find($id);
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

    public function scopeOfReview(Request $request, $profileId, $companyId, $id)
    {
        $collaborateId = $id;
        $inputs = $request->only(['methodology_id','age_group','is_taster_residence',
            'gender_ratio','no_of_expert','no_of_veterans','is_product_endorsement','step','state','taster_instruction']);
        $this->checkInputForScopeReview($inputs);
        if(!isset($inputs['is_product_endorsement']) || is_null($inputs['is_product_endorsement']))
            $inputs['is_product_endorsement'] = 0;
        $loggedInProfileId = $request->user()->profile->id;

        $checkAdmin = CompanyUser::where('company_id',$companyId)->where('profile_id',$loggedInProfileId)->exists();
        if(!$checkAdmin){
            return $this->sendError("Invalid Admin.");
        }

        $collaborate = $this->model->where('company_id',$companyId)->where('id',$id)->first();
        if($collaborate === null){
            return $this->sendError("Collaboration not found.");
        }

        if($inputs['no_of_veterans'] > 0 || $inputs['no_of_expert'] > 0)
        {
            $inputs['is_taster_residence'] = 1;
        }
        if(!$this->checkJson($inputs['age_group']) || !$this->checkJson($inputs['gender_ratio']))
        {
            $this->model = false;
            return $this->sendError("json is not valid.");
        }

        $inputs['is_taster_residence'] = is_null($inputs['is_taster_residence']) ? 0 : $inputs['is_taster_residence'];

        if(isset($inputs['step']))
        {
            $inputs['state'] = Collaborate::$state[0];
        }
        else
        {
            $inputs['state'] = Collaborate::$state[0];
        }

        if($request->has('city'))
        {
            $addresses = $request->input('city');
            Collaborate\Addresses::where('collaborate_id',$id)->delete();
            $cities = [];
            foreach ($addresses as $address)
            {
                $cities[] = ['collaborate_id'=>$collaborateId,'city_id'=>$address['id'],'no_of_taster'=>$address['no_of_taster']];
            }
            if(count($cities))
                $collaborate->addresses()->insert($cities);
        }

        if($request->has('occupation_id'))
        {
            $jobIds = $request->input('occupation_id');
            $jobs = [];
            foreach ($jobIds as $jobId)
            {
                $jobs[] = ['collaborate_id'=>$collaborateId,'occupation_id'=>$jobId];
            }
            if(count($jobs))
            {
                Collaborate\Occupation::where('collaborate_id',$id)->delete();
                $collaborate->collaborate_occupations()->insert($jobs);

            }
        }


        if($request->has('specialization_id'))
        {
            $specializationIds = $request->input('specialization_id');
            $specializations = [];
            foreach ($specializationIds as $specializationId)
            {
                $specializations[] = ['collaborate_id'=>$collaborateId,'specialization_id'=>$specializationId];
            }
            if(count($specializations))
            {
                Collaborate\Specialization::where('collaborate_id',$id)->delete();
                $collaborate->collaborate_specializations()->insert($specializations);

            }
        }
        $inputs['privacy_id'] = 1;
        if($request->has('batches'))
        {
            if($collaborate->state == 'Active')
            {
                return $this->sendError("You can not update your products.");
            }
        }
        if($collaborate->state != 'Active')
        {
            $now = Carbon::now()->toDateTimeString();
            $inputs['created_at'] = $now;
            $inputs['updated_at'] = $now;
        }
        $this->model = $collaborate->update($inputs);
        if($request->has('batches'))
        {
            $batches = $request->input('batches');
            $batchList = [];
            $now = Carbon::now()->toDateTimeString();
            foreach ($batches as $batch)
            {
                $batchList[] = ['name'=>$batch['name'],'color_id'=>$batch['color_id'],'notes'=>isset($batch['notes']) ? $batch['notes'] : null,
                    'instruction'=>isset($batch['instruction']) ? $batch['instruction'] : null, 'collaborate_id'=>$collaborateId,
                    'created_at'=>$now,'updated_at'=>$now];
            }
            if(count($batchList) > 0 && count($batchList) < $collaborate->no_of_batches)
            {
                Collaborate\Batches::insert($batchList);
                $batches = Collaborate\Batches::where('collaborate_id',$collaborateId)->get();
                foreach ($batches as $batch)
                {
                    $batch->addToCache();
                }
            }
        }
        $this->model = Collaborate::where('id',$id)->first();
        if(isset($inputs['step']) && !is_null($inputs['step']))
        {
            if($inputs['step'] == 3 && $collaborate->state == 'Active')
            {
                $this->model->addToCache();
                $company = Company::find($companyId);
                if(!isset($this->model->payload_id))
                    event(new NewFeedable($this->model, $company));
                \App\Filter\Collaborate::addModel($this->model);

            }
            return $this->sendResponse();

        }
        return $this->sendResponse();
    }

    public function checkJson($json)
    {
        if(!is_null($json))
        {
            $result = json_decode($json);

            if (json_last_error() === JSON_ERROR_NONE) {
                return true;
            }

// OR this is equivalent

            if (json_last_error() === 0) {
                return true;
            }
            return false;
        }
        return true;

    }

    public function uploadQuestion(Request $request, $profileId, $companyId, $id)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $checkAdmin = CompanyUser::where('company_id', $companyId)->where('profile_id', $loggedInProfileId)->exists();
        if (!$checkAdmin) {
            return $this->sendError("Invalid Admin.");
        }


        $collaborate = $this->model->where('company_id',$companyId)->where('id',$id)->first();
        if($collaborate === null){
            return $this->sendError("Collaboration not found.");
        }

        if(isset($collaborate->global_question_id) && !is_null($collaborate->global_question_id))
        {
            $this->model = false;
            return $this->sendError("You can not update your question");
        }

        if($collaborate->state == 'Save')
        {
            $globalQuestionId = $request->input('global_question_id');
            $checkQuestionexist = \DB::table('global_questions')->where('id',$globalQuestionId)->exists();
            if(!$checkQuestionexist)
            {
                $this->model = false;
                return $this->sendError("Global question id is not exists.");
            }
            //check again when going live
            event(new UploadQuestionEvent($collaborate->id,$globalQuestionId));
            $collaborate->update(['step'=>2,'global_question_id'=>$globalQuestionId]);
            $collaborate = Collaborate::where('company_id',$companyId)->where('id',$id)->first();
            $this->model = $collaborate;
            return $this->sendResponse();
        }
        $this->model = $collaborate;
        return $this->sendResponse();
    }

    public function checkInputForScopeReview(&$inputs)
    {
        $gender = ['Male','Female','Others'];
        $age = ['< 18','18 - 35','35 - 55','55 - 70','> 70'];
        if(isset($inputs['age_group']))
        {
            $inputs['age_group'] = json_decode($inputs['age_group'],true);
            $ageGroups = $inputs['age_group'];
            if(count($ageGroups))
            {
                $ageInputs = [];
                foreach ($ageGroups as $key=>$ageGroup)
                {
                    $key = htmlspecialchars_decode($key);
                    $ageGroup = htmlspecialchars_decode($ageGroup);
                    if(!in_array($key,$age))
                    {
                        unset($ageGroups[$key]);
                    }
                    $ageInputs[] = [$key=>$ageGroup];
                }
                $inputs['age_group'] = json_encode($ageInputs);
            }
        }
        if(isset($inputs['gender_ratio']))
        {
            $inputs['gender_ratio'] = json_decode($inputs['gender_ratio'],true);
            $genderTypes = $inputs['gender_ratio'];
            if(count($genderTypes))
            {
                $gendeInput = [];
                foreach ($genderTypes as $key=>$genderType)
                {
                    $key = htmlspecialchars_decode($key);
                    $genderType = htmlspecialchars_decode($genderType);
                    if(!in_array($key,$gender))
                    {
                        unset($genderTypes[$key]);
                    }
                    $gendeInput[] = [$key=>$genderType];
                }
                $inputs['gender_ratio'] = json_encode($gendeInput);
            }
        }
    }
}