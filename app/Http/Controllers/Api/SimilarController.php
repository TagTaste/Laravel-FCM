<?php

namespace App\Http\Controllers\Api;

use App\Strategies\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SimilarController extends Controller
{
    private $relationships = [
        'profile' => \App\Similar\Profile::class,           //user_id
        'company' => \App\Similar\Company::class,           //user_id
        'tagboard' => \App\Similar\Ideabook::class,         //user_id
        'photo' => \App\Similar\Photo::class,               //          profile_id
        'recipe' => \App\Similar\Recipe::class,             //          profile_id
        'job' => \App\Similar\Job::class,                   //          profile_id  company_id
        'collaborate' => \App\Similar\Collaborate::class,   //          profile_id  company_id
        'product' => \App\Similar\Product::class,           //                      company_id


    ];
    
    public function similar(Request $request, $relationship, $relationshipId)
    {
        $model = $this->getModel($relationship);
        
        if(!$model){
            throw new \Exception("Relationship not defined.");
        }
        $model = $model->find($relationshipId);
        
        if(!$model){
            throw new ModelNotFoundException("Could not find " . $relationship . " with id " . $relationshipId);
        }
        
        if(!method_exists($model,'similar')){
            throw new \Exception("Similars not defined.");
        }
        
        //paginate
        $page = $request->input('page');
        list($skip,$take) = Paginator::paginate($page);
        
        $similarModels = $model->similar($skip,$take);
        
        $this->model = [];
        $loggedInProfileId = $request->user()->profile->id;
        
        
        if(in_array($relationship,['job','photo','']))
        $userIds = $similarModels->keyBy('user_id')->pluck('user_id');
        $profiles =  \App\Recipe\Profile::whereIn('user_id',$userIds)->get();
        
        //this should not be the case, hence throwing exception.
        if($profiles === false){
            throw new \Exception("Could not get profiles.");
        }
        
        $profiles = $profiles->keyBy('user_id');
        //get meta
        foreach($similarModels as $similar){
            $temp = $similar->toArray();
            $temp['profile'] = $profiles->get($similar->user_id);
            $temp['meta'] = $similar->getMetaFor($loggedInProfileId);
            $this->model[$relationship][] = $temp;
        }
        return $this->sendResponse();
    }
    
    private function getModel($model)
    {
        return isset($this->relationships[$model]) ? new $this->relationships[$model] : false;
    }
}
