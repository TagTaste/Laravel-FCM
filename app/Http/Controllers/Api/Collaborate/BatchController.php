<?php namespace App\Http\Controllers\Api\Collaborate;

use App\Collaborate;
use App\Recipe\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;

class BatchController extends Controller
{

    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Collaborate\Batches $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($collaborateId)
    {
        $this->model = $this->model->where('collaborate_id',$collaborateId)->get();

        return $this->sendResponse();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, $collaborateId)
    {
        $inputs = $request->except(['_method','_token']);
        $this->model = $this->model->create($inputs);

        return $this->sendResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($collaborateId,$id)
    {
        $profileIds = \DB::table('collaborate_batches_assign')->where('batch_id',$id)->get()->pluck('profile_id');
        $profiles = Profile::whereIn('id',$profileIds)->get();

        $profiles = $profiles->toArray();
        foreach ($profiles as &$profile)
        {
            $review = \DB::table('collaborate_tasting_user_review')->where('batch_id',$id)
                ->where('profile_id',$profile['id'])->orderBy('id','desc')->first();
            $profile['current_status'] = isset($review->current_status) ? $review->current_status : 0;
        }
        $this->model = $profiles;
        return $this->sendResponse();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param Request $request
     * @return Response
     */
    public function update(Request $request,$collaborateId, $id)
    {
        $inputs = $request->except(['_method','_token']);
        $batches = $this->model->where('id',$id)->where('collaborate_id',$collaborateId)->first();

        if(!$batches)
        {
            return $this->sendError("No batch available");
        }

        $this->model = $batches->update($inputs);
        return $this->sendResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $collaborateId, $id)
    {
        $batches = $this->model->where('id',$id)->where('collaborate_id',$collaborateId)->first();
        $this->model = $batches->delete();
        return $this->sendResponse();
    }

    public function assignBatch(Request $request, $id)
    {
        $collaborate = Collaborate::where('id',$id)->where('state','!=',Collaborate::$state[1])->first();

        if ($collaborate === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }
        $profileId = $request->user()->profile->id;

        if(isset($collaborate->company_id)&& (!is_null($collaborate->company_id)))
        {
            $checkUser = CompanyUser::where('company_id',$collaborate->company_id)->where('profile_id',$profileId)->exists();
            if(!$checkUser){
                return $this->sendError("Invalid Collaboration Project.");
            }
        }
        else if($collaborate->profile_id != $profileId){
            return $this->sendError("Invalid Collaboration Project.");
        }
        $applierProfileIds = $request->input('profile_id');
        $batchIds = $request->input('batch_id');
        $inputs = [];
        foreach ($batchIds as $batchId)
        {
            foreach ($applierProfileIds as $applierProfileId)
            {
                $inputs[] = ['profile_id' => $applierProfileId,'batch_id'=>$batchId];
            }
        }
        $this->model = \DB::table('collaborate_batches_assign')->insert($inputs);

        return $this->sendResponse();
    }

    public function removeFromBatch(Request $request, $collaborateId, $batchId)
    {
        $profileIds = $request->input('profile_id');

        $this->model = \DB::table('collaborate_batches_assign')->where('batch_id',$batchId)->whereIn('profile_id',$profileIds)->delete();

        return $this->sendResponse();

    }

}
