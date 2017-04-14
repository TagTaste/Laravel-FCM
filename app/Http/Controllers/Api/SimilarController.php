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
            throw new \Exception("Relationship not defined.");
        }
        
        if(!method_exists($model,'similar')){
            throw new \Exception("Similars not defined.");
        }
    
        $this->model = $model->similar();
        return $this->sendResponse();
    }
}
