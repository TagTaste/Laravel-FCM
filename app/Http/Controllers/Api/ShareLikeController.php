<?php

namespace App\Http\Controllers\Api;

use App\Events\Actions\Like;
use Illuminate\Http\Request;
use App\Shareable\Sharelikable;

class ShareLikeController extends Controller
{
    
    public function store(request $request,$model,$modelId)
    {
        $models = [
            'photo' => \App\Photo::class,
            'tagboard' => \App\Ideabook::class,
            'collaborate'=> \App\Collaborate::class,
            'recipe' => \App\Recipe::class,
            'shoutout' =>\App\Shoutout::class
        ];
    
        if((!array_key_exists($model,$models))){
            return $this->sendError("Could not find model with provided id");
        }
        
    	$profileId = $request->user()->profile->id;

        $modelName = ucfirst($model);

        $modelClass = \App::make('App\Shareable\\'.$modelName);
        $exist = $modelClass::where('id',$modelId)->exists();

        if(!$exist){
            return $this->sendError("Could not find id with provided model");
        }

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
    	event(new Like($model,$request->user()->profile));
        return $this->sendResponse();
    }

    public function index($model,$modelId)
    {
        $models = [
            'photo' => \App\Photo::class,
            'tagboard' => \App\Ideabook::class,
            'collaborate'=> \App\Collaborate::class,
            'recipe' => \App\Recipe::class,
            'shoutout' =>\App\Shoutout::class
        ];
    
        if((!array_key_exists($model,$models))){
            return $this->sendError("Could not find model with provided id");
        }
        
        $modelName = ucfirst($model);
    	$class = \App::make('App\Shareable\Sharelikable\\'.$modelName);

    	$columnName = $model.'_share_id';

    	$profileId = $class::where($columnName,$modelId)->select('profile_id')->get();
    	$this->model = \App\Profile::whereIn('id',$profileId)->get();
    	return $this->sendResponse();
    }
}
