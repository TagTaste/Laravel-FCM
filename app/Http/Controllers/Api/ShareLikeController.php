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
        $class = \App::make('App\Shareable\Sharelikable\\'.$modelName);
    	$columnName = $model.'_share_id';

    	$exist = $class::where('profile_id',$profileId)->where($columnName,$modelId)->first();
    	if($exist != null)
    	{
    		$class::where('profile_id',$profileId)->where($columnName,$modelId)->delete();
            $this->model['likeCount'] = \Redis::hIncrBy("shareLike" . strtolower($modelName) . ":" . $modelId . ":meta","like",-1);
    		return $this->sendResponse();
    	}

    	$model = new $class;
    	$model->profile_id = $profileId;
    	
    	$model->$columnName = $modelId;
    	$model->save();
        $this->model['likeCount'] = \Redis::hIncrBy("shareLike" . strtolower($modelName) . ":" . $modelId . ":meta","like",1);
    	return $this->sendResponse();
    }

    public function index($model,$modelId)
    {
        $modelName = ucfirst($model);
    	$class = \App::make('App\Shareable\Sharelikable\\'.$modelName);

    	$columnName = $model.'_share_id';

    	$profileId = $class::where($columnName,$modelId)->select('profile_id')->get();
    	$this->model = \App\Profile::whereIn('id',$profileId)->get();
    	return $this->sendResponse();
    }
}
