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
        $profileId = $request->user()->profile->id;
        $inputs = $request->all();
        $inputs['profile_id'] = $profileId;
        $inputs['state'] = Collaborate::$state[0];
        $inputs['expires_on'] = $request->expires_on;
        $fields = $request->has("fields") ? $request->input('fields') : [];

        $imagesArray = [];
        if ($request->has("images"))
        {
            $images = $request->input('images');
            $imageMeta = [];
            $i = 1;
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
        }
        $inputs['images'] = json_encode($imagesArray,true);
        if($request->hasFile('file1')){
            $relativePath = "images/p/$profileId/collaborate";
            $name = $request->file('file1')->getClientOriginalName();
            $extension = \File::extension($request->file('file1')->getClientOriginalName());
            $inputs["file1"] = $request->file("file1")->storeAs($relativePath, $name . "." . $extension,['visibility'=>'public']);
        }

        if (!empty($fields)) {
            unset($inputs['fields']);
        }
        unset($inputs['images']);
        $this->model = $this->model->create($inputs);

//        $categories = $request->input('categories');
//        $this->model->categories()->sync($categories);
//		$this->model->syncFields($fields);

        $profile = \App\Recipe\Profile::find($profileId);
        $this->model = $this->model->fresh();
        //push to feed
        event(new NewFeedable($this->model, $profile));

        //add subscriber
        event(new \App\Events\Model\Subscriber\Create($this->model,$profile));

        \App\Filter\Collaborate::addModel($this->model);

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
        $inputs = $request->all();
        $profileId = $request->user()->profile->id;

        $collaborate = $this->model->where('profile_id', $profileId)->where('id', $id)->whereNull('company_id')->first();


        if ($collaborate === null) {
            return $this->sendError( "Collaboration not found.");
        }
        
        // image computation
        $imagesArray = [];
        if ($request->has("images")) {
            $images = $request->input('images');
            $imageMeta = [];
            $i = 1;
            if (count($images) && is_array($images)) {
                foreach ($images as $image) {
                    if (is_null($image))
                        continue;
                    $imagesArray[]['image'.$i] = $image['original_photo'];
                    $imageMeta[] = $image;
                    $i++;
                }
                $inputs['images_meta'] = json_encode($imageMeta,true);
            } else {
                $inputs['images_meta'] = null;
                $inputs['images'] = null;
            }

        }
        unset($inputs['images']);


        if ($request->hasFile('file1')) {
            $relativePath = "images/p/$profileId/collaborate";
            $name = $request->file('file1')->getClientOriginalName();
            $extension = \File::extension($request->file('file1')->getClientOriginalName());
            $inputs["file1"] = $request->file("file1")
                ->storeAs($relativePath, $name . "." . $extension,['visibility'=>'public']);
        } else {
            if (isset($inputs['file1']) && ($inputs['file1'] == $collaborate->file1))
                unset($inputs['file1']);
            else
                $inputs['file1'] = null;
        }
//        $categories = $request->input('categories');
//        $this->model->categories()->sync($categories);
        // if ($collaborate->state == 'Expired'||$collaborate->state == 'Close') {
        //     $inputs['state'] = Collaborate::$state[0];
        //     $inputs['deleted_at'] = null;
        //     $inputs['created_at'] = Carbon::now()->toDateTimeString();
        //     $inputs['updated_at'] = Carbon::now()->toDateTimeString();
        //     $inputs['expires_on'] = Carbon::now()->addMonth()->toDateTimeString();

        //     $this->model = $collaborate->update($inputs);
        //     $collaborate->addToCache();

        //     $profile = Profile::find($profileId);
        //     $this->model = Collaborate::find($id);

        //     event(new NewFeedable($this->model, $profile));
        //     \App\Filter\Collaborate::addModel($this->model);
        //     return $this->sendResponse();
        // }
        if($request->expires_on != null) {
            $inputs['expires_on'] = $request->expires_on;
            if($collaborate->state == 'Expired' || $collaborate->state == 'Close' ) {
                $inputs['state'] = Collaborate::$state[0];
                $inputs['deleted_at'] = null;
                $collaborate->addToCache();
                $profile = Profile::find($profileId);
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

    public function allSubmissions(Request $request, $profileId, $collaborateId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $checkAdmin = $this->model->where('id',$collaborateId)->where('profile_id',$profileId)->whereNull('company_id')->where('state','!=',Collaborate::$state[1])->where('is_contest',1)->exists();
        if(!$checkAdmin){
            return $this->sendError("Invalid Admin.");
        }

        $profiles = \DB::table("collaborate_applicants")->where("collaborate_id",$collaborateId)->whereNull('rejected_at')->get();
        foreach($profiles as &$profile) {
            $submissions = \App\Collaborate\Applicant::getSubmissions($profile->profile_id, $collaborateId);
            $profile->submissions = $submissions;
        }
        $this->model = $profiles;
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
        }
        return $this->sendResponse();
    }
}