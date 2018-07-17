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

}
