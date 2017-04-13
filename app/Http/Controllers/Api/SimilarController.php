<?php

namespace App\Http\Controllers\Api;

use App\Similar\Photo;
use App\Similar\Profile;
use Illuminate\Http\Request;

class SimilarController extends Controller
{
    private $relationships = [
        'profile' => Profile::class,
        'photo' => Photo::class
    ];
    
    private function getModel($model)
    {
        return isset($this->relationships[$model]) ? new $this->relationships[$model] : false;
    }
    
    public function similar(Request $request, $relationship, $relationshipId)
    {
        $model = $this->getModel($relationship);
        
        if(!$model){
            $this->errors[] = "Relationship not defined.";
            
            return $this->sendResponse();
        }
        
        if(!method_exists($model,'similar')){
            $this->errors[] = "Similars not defined.";
            return $this->sendResponse();
        }
    
        $this->model = $model->similar();
        return $this->sendResponse();
    }
}
