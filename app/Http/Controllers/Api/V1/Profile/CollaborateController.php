<?php

namespace App\Http\Controllers\Api\V1\Profile;

use App\Collaborate;
use App\Company;
use App\Events\DeleteFeedable;
use App\Events\NewFeedable;
use App\Http\Controllers\Api\Controller;
use App\Listeners\Subscriber\Create;
use App\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Events\UploadQuestionEvent;

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
    public function index(Request $request, $profileId)
    {
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $collaborations = $this->model->orderBy('state', 'asc')->orderBy('created_at','desc');
        $profileId = $request->user()->profile->id;
        $this->model = [];
        $data = [];
        $type = isset($request->type)?$request->type:null;
        $state = isset($request->state)?$request->state:null;
        
        //Get compnaies of the logged in user.
        $companyIds = \DB::table('company_users')->where('profile_id',$profileId)->pluck('company_id');
        if($state == 6) {
            $interestedInCollaboration =  \App\Collaborate\Applicant::where('profile_id',$profileId)->pluck('collaborate_id');
            $collaborations = $collaborations->where('state','!=',2)->whereIn('id',$interestedInCollaboration);
        } else if($state == 4){
            $collaborations = $collaborations->where('state','!=',2)->where('step',1)->where(function($q) use ($profileId,$companyIds) {
                $q->where('profile_id', $profileId)
                  ->orWhereIn('company_id', $companyIds);
            });
            
        } else {
            $roleCollaborates = \DB::table('collaborate_user_roles')->where('profile_id',$profileId)->pluck('collaborate_id');
            $collaborations = $collaborations->where('state','!=',2)->where('step',3)->where(function($q) use ($profileId,$companyIds,$roleCollaborates) {
                $q->where('profile_id', $profileId)
                  ->orWhereIn('company_id', $companyIds)
                  ->orWhereIn('id',$roleCollaborates);
            });
        }
        if($type == 'collaborate') {
            $collaborations = $collaborations->where('collaborate_type','collaborate');
        
        } else if ($type == 'product-review') {
            $collaborations = $collaborations->where('collaborate_type','product-review');
        }
        $this->model['count'] = $collaborations->count();
        $collaborations = $collaborations->skip($skip)->take($take)
        ->get();
        foreach ($collaborations as $collaboration) {
            $data[] = [
                'collaboration' => $collaboration,
                'meta' => $collaboration->getMetaFor($profileId)
            ];
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
    public function store(Request $request, $profileId)
    {
        $loggedInProfileId = $request->user()->profile->id;


        $profile = $request->user()->profile;
        
        if(!$profile->is_premium){
            return $this->sendError("User profile ain't premium");
        }

        $profileId = $profile->id ;
        $inputs = $request->all();
        $inputs['state'] = 1;
        if(isset($inputs['collaborate_type']) && $inputs['collaborate_type'] == 'product-review')
        {
            $inputs['step'] = 1;
            $inputs['state'] = 4;
        }
        $inputs['profile_id'] = $profileId;
        $inputs['expires_on'] = isset($inputs['expires_on']) && !is_null($inputs['expires_on'])
                    ? $inputs['expires_on'] : Carbon::now()->addMonth()->toDateTimeString();
        $fields = $request->has("fields") ? $request->input('fields') : [];

        if(!empty($fields)){
            unset($inputs['fields']);
        }
        //save images
        $imagesArray = [];
        if ($request->has("images"))
        {
            $images = $request->input('images');
            $i = 1;
            $imageMeta = [];
            if(count($images) && is_array($images))
            {
                foreach ($images as $image)
                {
                    if(is_null($image))
                        continue;
                    $imagesArray[]['image'.$i] = $image['original_photo'];
                    $imageMeta[] = $image;
                    $i++;
                }
                $inputs['images_meta'] = json_encode($imageMeta,true);
            }
            $inputs['images'] = json_encode($imagesArray,true);
        }
        if($request->hasFile('file1')){
            $relativePath = "images/p/$profileId/collaborate";
            $name = $request->file('file1')->getClientOriginalName();
            $extension = \File::extension($request->file('file1')->getClientOriginalName());
            $inputs["file1"] = $request->file("file1")->storeAs($relativePath, $name ,['visibility'=>'public']);
        }
        unset($inputs['images']);
        if($request->has('mandatory_field_ids')) {
            $mandatory_field_ids = $request->mandatory_field_ids;
            unset($inputs['mandatory_field_ids']);
        }
        $this->model = $this->model->create($inputs);
//        $categories = $request->input('categories');
//        $this->model->categories()->sync($categories);
//        $this->model->syncFields($fields);
        //$company = Company::find($companyId);
        $this->model = $this->model->fresh();

        if($request->has('allergens_id'))
        {
            $allergensIds = $request->input('allergens_id');
            $allergens = [];
            if(count($allergensIds) > 0 && !empty($allergensIds) && is_array($allergensIds))
            {
                foreach ($allergensIds as $allergensId)
                {
                    $allergens[] = ['collaborate_id'=>$this->model->id,'allergens_id'=>$allergensId];
                }
                Collaborate\Allergens::where('collaborate_id',$this->model->id)->delete();
                $this->model->collaborate_allergens()->insert($allergens);
            }
            else
            {
                Collaborate\Allergens::where('collaborate_id',$this->model->id)->delete();
            }
        }
        $this->model = $this->model->fresh();
        
        //storing mandatory fields
        if(isset($mandatory_field_ids) && $mandatory_field_ids != null && count($mandatory_field_ids)>0)
            $this->storeMandatoryFields($mandatory_field_ids,$this->model->id);

        if($this->model->collaborate_type != 'product-review')
        {
            //push to feed
            event(new NewFeedable($this->model,$profile));

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
     * @param  int $id
     * @return Response
     */
    public function show(Request $request, $profileId, $id)
    {
        $collaboration = $this->model->where('id',$id)->where('profile_id', $profileId)->whereNull('company_id')->where('state','!=',Collaborate::$state[1])->first();
        if ($collaboration === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;
        $meta = $collaboration->getMetaFor($profileId);
        $this->model = ['collaboration' => $collaboration, 'meta' => $meta];

        return $this->sendResponse();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request, $profileId, $id)
    {
        $loggedInProfileId = $request->user()->profile->id;

        $inputs = $request->all();
        unset($inputs['profile_id']);
        unset($inputs['state']);
        unset($inputs['step']);
        $collaborate = $this->model->where('profile_id',$loggedInProfileId)->where('id',$id)->first();
        if($collaborate === null){
            return $this->sendError("Collaboration not found.");
        }

        if($collaborate->collaborate_type == 'collaborate')
            unset($inputs['expires_on']);

        $imagesArray = [];
        if ($request->has("images"))
        {
            $images = $request->input('images');
            $i = 1;
            $imageMeta = [];
            if(count($images) > 0 && !empty($images) && is_array($images))
            {
                foreach ($images as $image)
                {
                    if(is_null($image))
                        continue;
                    $imagesArray[]['image'.$i] = $image['original_photo'];
                    $imageMeta[] = $image;
                    $i++;
                }
                $inputs['images_meta'] = json_encode($imageMeta,true);
                $inputs['images'] = json_encode($imagesArray,true);
            }
            else
            {
                $inputs['images_meta'] = null;
                $inputs['images'] = null;
            }
        }
        unset($inputs['images']);
        if($request->hasFile('file1')){
            $relativePath = "images/p/$profileId/collaborate";
            $name = $request->file('file1')->getClientOriginalName();
            $extension = \File::extension($request->file('file1')->getClientOriginalName());
            $inputs["file1"] = $request->file("file1")->storeAs($relativePath, $name . "." . $extension,['visibility'=>'public']);
        }
        else
        {
            if (isset($inputs['file1']) && ($inputs['file1'] == $collaborate->file1))
                unset($inputs['file1']);
            else
                $inputs['file1'] = null;
        }
        if($request->has('allergens_id'))
        {
            $allergensIds = $request->input('allergens_id');
            $allergens = [];
            if(count($allergensIds) > 0 && !empty($allergensIds) && is_array($allergensIds))
            {
                foreach ($allergensIds as $allergensId)
                {
                    $allergens[] = ['collaborate_id'=>$collaborate->id,'allergens_id'=>$allergensId];
                }
                Collaborate\Allergens::where('collaborate_id',$collaborate->id)->delete();
                $collaborate->collaborate_allergens()->insert($allergens);
            }
            else
            {
                Collaborate\Allergens::where('collaborate_id',$collaborate->id)->delete();
            }
        }

        // if($collaborate->state == 'Expired'||$collaborate->state == 'Close')
        // {
        //     $inputs['state'] = Collaborate::$state[0];
        //     $inputs['deleted_at'] = null;
        //     $inputs['created_at'] = Carbon::now()->toDateTimeString();
        //     $inputs['updated_at'] = Carbon::now()->toDateTimeString();
        //     $inputs['expires_on'] = Carbon::now()->addMonth()->toDateTimeString();
        //     $this->model = $collaborate->update($inputs);

        //     $collaborate->addToCache();

        //     $company = Company::find($companyId);
        //     $this->model = Collaborate::find($id);

        //     event(new NewFeedable($this->model, $company));
        //     \App\Filter\Collaborate::addModel($this->model);

        //     return $this->sendResponse();
        // }
        $inputs['privacy_id'] = 1;
        if($request->expires_on != null) {
            $inputs['expires_on'] = $request->expires_on;
            if($collaborate->state == 'Expired' || $collaborate->state == 'Close' ) {
                $inputs['state'] = Collaborate::$state[0];
                $inputs['deleted_at'] = null;
                $collaborate->addToCache();
                $profile = \App\Profile::find($profileId);
                $this->model = Collaborate::find($id);

                event(new NewFeedable($this->model, $profile));
            }
        }
        $inputs['updated_at'] = Carbon::now()->toDateTimeString();
        $this->model = $collaborate->update($inputs);
        $this->model = Collaborate::find($id);
        \App\Filter\Collaborate::addModel(Collaborate::find($id));

        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Request $request, $profileId, $id)
    {
        $profileId = $request->user()->profile->id;

        $collaborate = $this->model->where('profile_id', $profileId)->where('id', $id)->whereNull('company_id')->first();

        if ($collaborate === null) {
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

        $this->model = $collaborate->update(['deleted_at'=>Carbon::now()->toDateTimeString(),'state'=>Collaborate::$state[1]]);;
        return $this->sendResponse();
    }

    public function approve(Request $request, $profileId, $id)
    {
        $collaborate = $this->model->where('profile_id', $profileId)->where('id', $id)->whereNull('company_id')->first();

        if ($collaborate === null) {
            return $this->sendError( "Collaboration not found.");
        }

        if ($request->has('company_id')) {
            $companyId = $request->input('company_id');
            $company = Company::find($companyId);
            if (!$company) {
                return $this->sendError( "Company not found.");
            }

            $this->model = $collaborate->approveCompany($company);
        } elseif ($request->has('profile_id')) {
            $inputProfileId = $request->input('profile_id');
            $profile = Profile::find($inputProfileId);
            if (!$profile) {
                return $this->sendError( "Profile not found.");
            }
            $this->model = $collaborate->approveProfile($profile);
        }
        return $this->sendResponse();

    }

    public function reject(Request $request, $profileId, $id)
    {
        $collaborate = $this->model->where('profile_id', $profileId)->where('id', $id)->whereNull('company_id')->first();

        if ($collaborate === null) {
            return $this->sendError( "Collaboration not found.");
        }

        if ($request->has('company_id')) {
            $companyId = $request->input('company_id');
            $company = Company::find($companyId);
            if (!$company) {
                return $this->sendError( "Company not found.");
            }

            $this->model = $collaborate->rejectCompany($company);
        } elseif ($request->has('profile_id')) {
            $inputProfileId = $request->input('profile_id');
            $profile = Profile::find($inputProfileId);
            if (!$profile) {
                return $this->sendError( "Profile not found.");
            }

            $this->model = $collaborate->rejectProfile($profile);
        }
        return $this->sendResponse();

    }

    public function interested(Request $request, $profileId)
    {
        $profileId = $request->user()->profile->id;
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $collaborations = $this->model->select('collaborate_id','collaborates.*')
            ->join('collaborate_applicants','collaborate_applicants.collaborate_id','=','collaborates.id')
            ->where("collaborate_applicants.profile_id",$profileId)->where("collaborates.state","!=",Collaborate::$state[1])
            ->whereNull('collaborate_applicants.company_id')->orderBy('collaborate_applicants.created_at', 'desc');;

        $data = [];
        $this->model = [];
        $this->model['count'] = $collaborations->count();
        $collaborations = $collaborations->skip($skip)->take($take)->get();
        foreach ($collaborations as $collaboration) {
            $data[] = ['collaboration' => $collaboration, 'meta' => $collaboration->getMetaFor($profileId)];
        }
        $this->model['collaborations'] = $data;
        return $this->sendResponse();

    }

    public function expired(Request $request, $profileId)
    {
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $profileId = $request->user()->profile->id;
        $collaborations = $this->model->where('profile_id', $profileId)->whereIn('state',[3,5])->whereNull('company_id')->orderBy('deleted_at', 'desc');
        $this->model = [];
        $data = [];
        $this->model['count'] = $collaborations->count();
        $collaborations = $collaborations->skip($skip)->take($take)->get();
        $profileId = $request->user()->profile->id;
        foreach ($collaborations as $collaboration) {
            $data[] = ['collaboration' => $collaboration, 'meta' => $collaboration->getMetaFor($profileId)];
        }
        $this->model['collaborations'] = $data;
        return $this->sendResponse();

    }

    public function draft(Request $request)
    {
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);
        $profileId = $request->user()->profile->id;
        $collaborations = $this->model->where('profile_id', $profileId)->where('state',Collaborate::$state[3])->whereNull('company_id')->orderBy('deleted_at', 'desc');
        $this->model = [];
        $data = [];
        $this->model['count'] = $collaborations->count();
        $collaborations = $collaborations->skip($skip)->take($take)->get();
        $profileId = $request->user()->profile->id;
        foreach ($collaborations as $collaboration) {
            $data[] = ['collaboration' => $collaboration, 'meta' => $collaboration->getMetaFor($profileId)];
        }
        $this->model['collaborations'] = $data;
        return $this->sendResponse();
    }

    public function collaborateClose(Request $request, $profileId, $id)
    {
        $data = [];
        $reasonId = $request->input('reason_id');
        if ($reasonId == 1 || $reasonId == 2 || $reasonId == 3 ) {
            $description = null;
            if ($reasonId == 1) {
                $reason = 'Completed';
            } else if ($reasonId == 2) {
                $reason = 'Did not find enough responses for this collaboration';
            } else {
                $reason = 'Other';
                $description = $request->input('description');
            }
            $data = ['collaborate_id'=>$id,'reason'=>$reason,'other_reason'=>$description];
        } else {
            return $this->sendError("Please select valid reason");
        }
        $loggedInProfileId = $request->user()->profile->id;

        $collaboration = $this->model->where('id',$id)
            ->where('profile_id', $profileId)
            ->whereNull('company_id')
            ->whereIn('state',[Collaborate::$state[0], Collaborate::$state[4]])
            ->first();
        if (is_null($collaboration)) {
            return $this->sendError("Collaboration not found.");
        }

        event(new \App\Events\DeleteFilters(class_basename($collaboration), $collaboration->id));
        $collaboration->update(['deleted_at' => Carbon::now()->toDateTimeString(), 'state' => Collaborate::$state[4]]);
        event(new DeleteFeedable($collaboration));

        $this->model = \DB::table('collaborate_close_reason')->insert($data);

        return $this->sendResponse();
    }

    public function allSubmissions(Request $request, $profileId, $collaborateId,$userId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $checkAdmin = $this->model->where('id',$collaborateId)->where('profile_id',$profileId)->whereNull('company_id')->where('state','!=',Collaborate::$state[1])->where('is_contest',1)->exists();
        if(!$checkAdmin){
            return $this->sendError("Invalid Admin.");
        }
        $applicant = \DB::table('collaborate_applicants')->where('collaborate_id',$collaborateId)->where('profile_id',$userId)->first()->id;
        $submissions = \App\Collaborate\Applicant::getSubmissions($applicant, $collaborateId);
        $this->model = $submissions;
        return $this->sendResponse();
    }
    public function updateSubmissionStatus(Request $request, $profileId, $collaborateId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $checkAdmin = $this->model->where('id',$collaborateId)->where('profile_id',$profileId)->whereNull('company_id')->where('state','!=',Collaborate::$state[1])->where('is_contest',1)->exists();
        if(!$checkAdmin){
            return $this->sendError("Invalid Admin.");
        }
        $submissions = $request->submissions;
        foreach($submissions as $submission) {
            $this->model = \DB::table('submissions')->where('id',$submission['id'])
                    ->update(['status'=>$submission['status']]);
            if($submission['status'] == 2 && $this->model) {
                $this->sendRejectNotification($submission['id'],$collaborateId);
            }
        }
        return $this->sendResponse();
    }

    protected function sendRejectNotification($submissionId,$collaborateId)
    {
        $profileId = \DB::table('contest_submissions')
                        ->join('collaborate_applicants','collaborate_applicants.id','=','contest_submissions.applicant_id')
                        ->where('contest_submissions.submission_id',$submissionId)
                        ->pluck('collaborate_applicants.profile_id');
        $collaborate = \App\Collaborate::where('id',$collaborateId)->first();
        event(new \App\Events\DocumentRejectEvent($profileId,null,null,$collaborate));
    }

    public function storeMandatoryFields($fieldIds, $collaborateId)
    {
        \DB::table('collaborate_mandatory_mapping')->where('collaborate_id',$collaborateId)->delete();
        $insertData = [];
        foreach ($fieldIds as $fieldId) {
            $insertData[] = ['mandatory_field_id'=>$fieldId,'collaborate_id'=>$collaborateId];
        }
        \DB::table('collaborate_mandatory_mapping')->insert($insertData);
    }

    public function uploadQuestion(Request $request, $profileId, $id)
    {
        $loggedInProfileId = $request->user()->profile->id;

        $collaborate = $this->model->where('company_id',$companyId)->where('id',$id)->first();
        if($collaborate === null){
            return $this->sendError("Collaboration not found.");
        }

        if(isset($collaborate->global_question_id) && !is_null($collaborate->global_question_id))
        {
            $this->model = false;
            return $this->sendError("You can not update your question");
        }
        // if($collaborate->state == 'Save')
        // {
            $globalQuestionId = $request->input('global_question_id');
            if (!is_null($globalQuestionId)) {
                $checkQuestionexist = \DB::table('global_questions')->where('id',$globalQuestionId)->where('track_consistency',$collaborate->track_consistency)->exists();
                if(!$checkQuestionexist)
                {
                    $this->model = false;
                    return $this->sendError("Global question id is not exists.");
                }
                //check again when going live
                event(new UploadQuestionEvent($collaborate->id,$globalQuestionId));
            }

            $collaborate->update(['step'=>2,'global_question_id'=>$globalQuestionId]);
            $collaborate = Collaborate::where('company_id',$companyId)->where('id',$id)->first();
            $this->model = $collaborate;
            return $this->sendResponse();
        // }
        $this->model = $collaborate;
        return $this->sendResponse();
    }

    public function scopeOfReview(Request $request, $profileId, $id)
    {
        $collaborateId = $id;
        $inputs = $request->only(['methodology_id','age_group','is_taster_residence',
            'gender_ratio','no_of_expert','no_of_veterans','is_product_endorsement','step','state','taster_instruction']);
        $this->checkInputForScopeReview($inputs);
        if(!isset($inputs['is_product_endorsement']) || is_null($inputs['is_product_endorsement']))
            $inputs['is_product_endorsement'] = 0;
        $profile = $request->user()->profile;
        $loggedInProfileId = $request->user()->profile->id;


        $collaborate = $this->model->where('profile_id',$profileId)->where('id',$id)->first();
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
           $this->storeCity($request->input('city'),$collaborateId,$collaborate);
        }
        if($request->has('mandatory_field_ids')) {
            $this->storeMandatoryFields($request->mandatory_field_ids,$collaborateId);
            $inputs['document_required'] = $request->has('document_required') ? $request->document_required : null;
        }
        if($request->has('occupation_id'))
        {
            $jobIds = $request->input('occupation_id');
            $jobs = [];
            if(count($jobIds) > 0 && !empty($jobIds) && is_array($jobIds))
            {
                foreach ($jobIds as $jobId)
                {
                    $jobs[] = ['collaborate_id'=>$collaborateId,'occupation_id'=>$jobId];
                }
                Collaborate\Occupation::where('collaborate_id',$id)->delete();
                $collaborate->collaborate_occupations()->insert($jobs);
            }
            else
            {
                Collaborate\Occupation::where('collaborate_id',$id)->delete();

            }
        }


        if($request->has('specialization_id'))
        {
            $specializationIds = $request->input('specialization_id');
            $specializations = [];
            if(count($specializationIds) > 0 && !empty($specializationIds) && is_array($specializationIds))
            {
                foreach ($specializationIds as $specializationId)
                {
                    $specializations[] = ['collaborate_id'=>$collaborateId,'specialization_id'=>$specializationId];
                }
                Collaborate\Specialization::where('collaborate_id',$id)->delete();
                $collaborate->collaborate_specializations()->insert($specializations);
            }
            else
            {
                Collaborate\Specialization::where('collaborate_id',$id)->delete();
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
            if(count($batchList) > 0 && count($batchList) <= $collaborate->no_of_batches)
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
                if(!isset($this->model->payload_id))
                    event(new NewFeedable($this->model, $profile));
                \App\Filter\Collaborate::addModel($this->model);
            }
            return $this->sendResponse();

        }
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

    protected function storeCity($addresses, $collaborateId, $collaborate)
    {
        $isReviewed = \DB::table('collaborate_tasting_user_review')
                        ->where('collaborate_id',$collaborateId)
                        ->exists();
        if($isReviewed)
        {
            return ;
        }
        Collaborate\Addresses::where('collaborate_id',$collaborateId)->delete();
        $cities = [];
        if(count($addresses) > 0 && !empty($addresses) && is_array($addresses))
        {
            foreach ($addresses as $address)
            {
                if( isset($address['outlets']) && count($address['outlets'])>0 ) {
                    foreach($address['outlets'] as $outlet) {
                        if(!isset($outlet['id'])) {
                            $outletId = \App\Outlet::create(['name'=>$outlet['name']])->id;
                            $isActive = 1;
                        }else{
                            \App\Outlet::where('id',$outlet['id'])->update(['name'=>$outlet['name']]);
                            $outletId = $outlet['id'];
                            if(isset($outlet['is_active'])) {
                                $isActive = $outlet['is_active'];
                            } else {
                                $isActive = 1;
                            }
                        }
                        $cities[] = [
                                'collaborate_id'=>$collaborateId,
                                'city_id'=>$address['id'],
                                'no_of_taster'=>$address['no_of_taster'], 
                                'outlet_id'=>$outletId,
                                'is_active'=>$isActive
                            ];    
                    }
                } else if (!isset($address['outlets']) && $collaborate->track_consistency) {
                    return $this->sendError('Outlet cannot be null for consistency tracking collaboration');
                } else {
                    $cities[] = ['collaborate_id'=>$collaborateId,'city_id'=>$address['id'],'no_of_taster'=>$address['no_of_taster']];
                }
            }
            Collaborate\Addresses::where('collaborate_id',$collaborateId)->delete();
            Collaborate\Addresses::insert($cities);
        }
        else
        {
            Collaborate\Addresses::where('collaborate_id',$collaborateId)->delete();
        }
    }

    public function getRoles(Request $request,$proifleId,$id)
    {
        $canAction = Collaborate::where('id',$id)->pluck('state')[0] == "Active" ? true : false;
        $roles = \DB::table('collaborate_role')
        ->leftJoin('collaborate_user_roles',function($join) use ($id){
            $join->on('collaborate_role.id','=', 'collaborate_user_roles.role_id')
            ->where('collaborate_user_roles.collaborate_id','=',$id);
        })
        ->leftJoin('profiles','collaborate_user_roles.profile_id','=','profiles.id')
        ->leftJoin('users','profiles.user_id','=','users.id')
        ->select('collaborate_role.role',
                'users.name',
                'profiles.image',
                'collaborate_role.id as role_id',
                'collaborate_role.helper_text',
                'collaborate_role.can_action',
                'profiles.id',
                'profiles.handle',
                'profiles.city',
                'profiles.tagline',
                'profiles.image_meta',
                'profiles.verified',
                'profiles.is_tasting_expert'
            )
        ->orderBy('collaborate_role.id','asc')
        ->get();
        $roles = $roles->groupBy("role");
        $this->model = [];
        foreach($roles as $role => $value) {
            $model = [];
            if($role == 'Panel Partners' && $canAction == false) {
                $model['can_action'] = filter_var('false', FILTER_VALIDATE_BOOLEAN);
            } else {
                $model['can_action'] = filter_var('true', FILTER_VALIDATE_BOOLEAN);
            }
            $model['role'] = $role;
            $model['role_id'] = $value[0]->role_id;
            $model['name'] = $role;
            $model['description'] = $value[0]->helper_text;
            $model['profiles'] = [];
            
            if($value[0]->id != null)
            $model['profiles'] = $value;
            $this->model[] = $model;
        }
        return $this->sendResponse();
    }
    public function assignRole(Request $request,$profileId,$collaborateId)
    {
        $checkIfExists = \DB::table('collaborates')
            // ->whereNull('deleted_at')
            // ->where('state',1)
            ->where('id',$collaborateId)
            ->where('profile_id',$profileId)
            ->count();
       if(!$checkIfExists) {
           return $this->sendError("Invalid Collaboration");
       }
        $loggedInProfileId = $request->user()->profile->id;
       $roleId = $request->role_id;
       if(!isset($roleId) || $roleId == null) {
           $this->sendError("please enter role id");
       }
       if(!is_array($roleId))
           $roleId = [$roleId];

       $data = [];
       $profileId = $request->profile_id;
       foreach ($roleId as $role) {
           $exists = \DB::table('collaborate_role')->where('id',$role)->count();
           if(!$exists) {
               return $this->sendError('Invalid role id');
           }
           $rolesAssigned = \DB::table('collaborate_user_roles')->where('collaborate_id',$collaborateId)->where('profile_id',$profileId)->where('role_id',$roleId)->count();
           if(!$rolesAssigned) {
               $data[] = ['profile_id'=>$profileId,'collaborate_id'=>$collaborateId,'role_id'=>$role];
           }
       }
       $this->model = \DB::table('collaborate_user_roles')->insert($data);
       return $this->sendResponse();

    }
    public function deleteRoles(Request $request,$profileId,$collaborateId)
    {
        $checkIfExists = \DB::table('collaborates')
            // ->whereNull('deleted_at')
            // ->where('state',1)
            ->where('profile_id',$profileId)
            ->where('id',$collaborateId)
            ->count();
        if(!$checkIfExists) {
            return $this->sendError("Invalid Collaboration");
        }
        $loggedInProfileId = $request->user()->profile->id;
        $checkAdmin = CompanyUser::where('company_id', $companyId)->where('profile_id', $loggedInProfileId)->exists();
        if (!$checkAdmin) {
            return $this->sendError("Invalid Admin.");
        }
        $profileId = $request->profile_id;
        $roleId = $request->role_id;
        if(!isset($profileId) || !isset($roleId)) {
            return $this->sendError("Invalid Inputs given");
        }
        $this->model = \DB::table('collaborate_user_roles')
                        ->where('collaborate_id',$collaborateId)
                        ->where('profile_id',$profileId)
                        ->where('role_id',$roleId)
                        ->delete();
        return $this->sendResponse();
    }
    public function getProfileRole(Request $request,$profileId,$collaborateId)
    {
        $checkIfExists = \DB::table('collaborates')
            // ->whereNull('deleted_at')
            // ->where('state',1)
            ->where('profile_id',$profileId)
            ->where('id',$collaborateId)
            ->count();
        if(!$checkIfExists) {
            return $this->sendError("Invalid Collaboration");
        }
        $loggedInProfileId = $request->user()->profile->id;
        $checkAdmin = CompanyUser::where('company_id', $companyId)->where('profile_id', $loggedInProfileId)->exists();
        if (!$checkAdmin) {
            return $this->sendError("Invalid Admin.");
        }
        $profileId = $request->profile_id;
        $this->model = \DB::table('collaborate_user_roles')
            ->join('collaborate_role','collaborate_role.id','=','collaborate_user_roles.role_id')
            ->where('collaborate_user_roles.profile_id',$profileId)->get();
        return $this->sendResponse();
    }
    public function getOutlets(Request $request,$profileId,$collaborateId,$cityId)
    {
        $this->model = \DB::table('collaborate_addresses')->select('collaborate_addresses.address_id','outlets.name','collaborate_addresses.is_active')
                        ->where('collaborate_id',$collaborateId)
                        ->join('outlets','outlets.id','=','collaborate_addresses.outlet_id')
                        ->where('city_id',$cityId)
                        ->get();
        return $this->sendResponse();
    }

    public function outletStatus(Request $request,$profileId,$collaborateId,$cityId,$addressId)
    {   
        $status = $request->status != null ? $request->status : null;
        if($status != null) {
            $this->model = \DB::table('collaborate_addresses')
                            ->where('address_id',$addressId)
                            ->where('collaborate_id',$collaborateId)
                            ->update(['is_active'=>$status]);
            return $this->sendResponse();
        } else {
            return $this->sendError("Invalid status type");
        }
    }
}