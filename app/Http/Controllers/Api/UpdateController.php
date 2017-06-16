<?php

namespace App\Http\Controllers\Api;

use App\Update;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateController extends Controller
{
	/**
	 * Variable to model
	 *
	 * @var update
	 */
	protected $model;

	/**
	 * Create instance of controller with Model
	 *
	 * @return void
	 */
	public function __construct(Update $model)
	{
		$this->model = $model;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
	    $profileId = $request->user()->profile->id;
	    
		$this->model = $this->model->where('profile_id',$profileId)->orderBy('created_at','desc')->paginate();

		return $this->sendResponse();
	}

	public function isRead(Request $request, $modelName,$modelId)
    {
        $loggedInProfileId = $request->user()->profile->id;
        $match=['model_name'=>$modelName,'model_id'=>$modelId,'profile_id'=>$loggedInProfileId];
        $this->model = Update::where($match)->update(['is_read'=>1]);

        return $this->sendResponse();
    }
}