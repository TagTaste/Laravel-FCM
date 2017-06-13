<?php

namespace App\Http\Controllers\Api;

use App\Strategies\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class SimilarController extends Controller
{
    private $relationships = [
        'job' => \App\Similar\Job::class,
        'profile' => \App\Similar\Profile::class,
        'photo' => \App\Similar\Photo::class,
        'product' => \App\Similar\Product::class,
        'recipe' => \App\Similar\Recipe::class,
        'collaborate' => \App\Similar\Collaborate::class
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
        
        $this->model = $model->similar($skip,$take);
        return $this->sendResponse();
    }
    
    private function getModel($model)
    {
        return isset($this->relationships[$model]) ? new $this->relationships[$model] : false;
    }
}
