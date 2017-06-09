<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Shareable\Sharelikable;

class ShareLikeController extends Controller
{

    public function store(request $request,$model,$modelId)
    {
    	$profileId = $request->user()->profile->id;

    	$modelName = ucfirst($model);
        $class = \App::make('App\Shareable\Sharelikable\\'.$model);
    	$model = $model.'_share_id';

    	$exist = $class::where('profile_id',$profileId)->where($model,$modelId)->first();
    	if($exist != null)
    	{
    		$this->model = $class::where('profile_id',$profileId)->where($model,$modelId)->delete();
    		return $this->sendResponse();
    	}

    	$this->model = new $class;
    	$this->model->profile_id = $profileId;
    	
    	$this->model->$model = $modelId;
    	$this->model->save();

    	return $this->sendResponse();
    }

    public function index($model,$modelId)
    {
        $modelName = ucfirst($model);
    	$class = \App::make('App\Shareable\Sharelikable\\'.$model);

    	$model = $model.'_share_id';

    	$profileId = $class::where($model,$modelId)->select('profile_id')->get();
    	$profile = \App\Profile::whereIn('id',$profileId)->get();
    	return $this->sendResponse($profile);
    }
}
