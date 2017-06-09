<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class ShareLikeController extends Controller
{
    private $models = [
    	'photo_share' => \App\PhotoShareLike::class,
    	'collaborate_share'  => \App\CollaborateShareLike::class,
    	'recipe_share' => \App\RecipeShareLike::class,
    	// 'job_share' => \App\JobShareLike::class,
    	'shoutout_share' => \App\ShoutoutShareLike::class,
    ];

    public function store(request $request,$model,$model_id)
    {
    	$profileId = $request->user()->profile->id;

    	$class = $this->models[$model];
    	$model = $model.'_id';

    	$exist = $class::where('profile_id',$profileId)->where($model,$model_id)->first();
    	if($exist != null)
    	{
    		$exist = $class::where('profile_id',$profileId)->where($model,$model_id)->delete();
    		return response()->json(0);
    	}

    	$instance = new $class;
    	$instance->profile_id = $profileId;
    	
    	$instance->$model = $model_id;
    	$instance->save();

    	return response()->json(1);
    }

    public function index($model,$model_id)
    {
    	$class = $this->models[$model];
    	$model = $model.'_id';

    	$profileId = $class::where($model,$model_id)->select('profile_id')->get();
    	$profile = \App\Profile::whereIn('id',$profileId)->get();
    	return response()->json($profile);
    }
}
