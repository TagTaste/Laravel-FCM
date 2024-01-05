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
        
        if(!$similarModels){
            return $this->sendError("No similar models.");
        }
        
        $this->model = [];
        $loggedInProfileId = $request->user()->profile->id;
        
        $profiles = false;
        $companies = false;
        $ownerColumn = null;
        if(in_array($relationship,['profile','company','tagboard'])){
            //using user_id
            $userIds = $similarModels->keyBy('user_id')->pluck('user_id');
            $profiles =  \App\Recipe\Profile::whereIn('user_id',$userIds)->get();
            $profiles = $profiles->keyBy('user_id');
            $ownerColumn = 'user_id';
            
        } elseif(in_array($relationship,['photo','recipe'])){
            //using profile_id
            $profileIds = $similarModels->keyBy('profile_id')->pluck('profile_id');
            $profiles = \App\Recipe\Profile::whereIn('id',$profileIds)->get();
            $profiles = $profiles->keyBy('id');
            $ownerColumn = 'profile_id';
    
        } elseif(in_array($relationship,['job','collaborate'])){
            //using profile_id
            $profileIds = $similarModels->keyBy('profile_id')->pluck('profile_id')->toArray();
            $profileIds = array_filter($profileIds);
            $profiles = \App\Recipe\Profile::whereIn('id',$profileIds)->get();
            $profiles = $profiles->keyBy('id');
            $ownerColumn = 'profile_id';
            
            //using company_id as well
            $companyIds = $similarModels->keyBy('company_id')->pluck('company_id')->toArray();
            $companyIds = array_filter($companyIds);
            $companies = \App\Company::whereIn('id',$companyIds)->get();
            $companies = $companies->keyBy('id');
        }  elseif($relationship === 'product'){
            //using company_id
            $companyIds = $similarModels->keyBy('company_id')->pluck('company_id')->toArray();
            $companyIds = array_filter($companyIds);
            $companies = \App\Company::whereIn('id',$companyIds)->get();
            $companies = $companies->keyBy('id');
        }
        
        //get meta
        foreach($similarModels as $similar){
            $temp = $similar->toArray();

            if (isset($temp['videos_meta']) && !is_array($temp['videos_meta'])) {
                $temp['videos_meta'] = json_decode($temp['videos_meta'], true);
            }
            
            if($profiles){
                $temp['profile'] = $profiles->get($similar->$ownerColumn);
            }
            
            if($companies){
                $temp['company'] = $companies->get($similar->company_id);
            }
            
            if($relationship !== 'product' && $relationship !=='profile' && $relationship !== 'company'){
                $temp['meta'] = $similar->getMetaFor($loggedInProfileId);
            }
            
            $this->model[$relationship][] = $temp;
        }
        return $this->sendResponse();
    }
    
    private function getModel($model)
    {
        return isset($this->relationships[$model]) ? new $this->relationships[$model] : false;
    }
}
