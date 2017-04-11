<?php

namespace App\Http\Controllers\Api;

use App\Ideabook;
use App\Photo;
use App\Profile;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * map relationship to their class
     *
     * @var array
     */
    private $relationshipModels = [
        'profiles' => '\App\Ideabook\Profile',
        'photos'=> '\App\Ideabook\Photo',
        'products' => '\App\Ideabook\Profile',
        'recipes' => '\App\Ideabook\Recipe'
    ];
    
    /**
     * Tag a $relationship
     *
     * @param Request $request
     * @param $tagboardId integer
     * @param $relationship string
     * @param $relationshipId integer
     * @return \Illuminate\Http\JsonResponse
     */
    public function tag(Request $request, $tagboardId, $relationship, $relationshipId)
    {
        $tagboard = $this->getTagboard($request,$tagboardId);
    
        if(!$tagboard){
            $this->errors[] = ["Tagboard doesn't exist or the user doesn't belong to the tagboard."];
            return $this->sendResponse();
        }
        
        //check if relationship has been defined in Tagboard
        if(!method_exists($tagboard,$relationship)){
            $this->errors[] = [$relationship . " isn't taggable."];
            return $this->sendResponse();
        }
        
        $model = $this->getRelationshipModel($relationship);
        
        //check if model exists
        
        $exists = $model::find($relationshipId);
        
        if($exists === null){
            $this->errors[] = ["Model doesn't exist."];
            return $this->sendResponse();
        }
        
        $alreadyTagged = $tagboard->where('id',$tagboardId)->alreadyTagged($relationship,$relationshipId)->first();
        $note = $request->input('note');
        
        $response = $alreadyTagged === null ? $tagboard->tag($relationship,$relationshipId,$note) : $tagboard->untag($relationship,$relationshipId);
        
        $this->model['tagged'] = $response === 1 ? false : true;
        
        return $this->sendResponse();
        
    }
    
    public function updateNote(Request $request, $tagboardId, $relationship, $relationshipId)
    {
        $tagboard = $this->getTagboard($request,$tagboardId);
    
        if(!$tagboard){
            $this->errors[] = ["Tagboard doesn't exist or the user doesn't belong to the tagboard."];
            return $this->sendResponse();
        }
    
        //check if relationship has been defined in Tagboard
        if(!method_exists($tagboard,$relationship)){
            $this->errors[] = [$relationship . " isn't taggable."];
            return $this->sendResponse();
        }
    
        $model = $this->getRelationshipModel($relationship);
    
        //check if model exists
    
        $exists = $model::find($relationshipId);
    
        if($exists === null){
            $this->errors[] = ["Model doesn't exist."];
            return $this->sendResponse();
        }
        $note = $request->input('note');
        $this->model = $tagboard->updateNote($relationship,$relationshipId,$note);
        return $this->sendResponse();
    }
    
    /**
     * Get the model class for the relationship
     *
     * @param $relationshipName string
     * @return mixed
     */
    private function getRelationshipModel($relationshipName)
    {
        if(!array_key_exists($relationshipName,$this->relationshipModels)){
            throw new \Exception("relationshipModel for '$relationshipName' not defined in TagController.");
        }
        return $this->relationshipModels[$relationshipName];
    }
    
    
    /**
     * Get the logged in user's tagboard with $tagboardId
     *
     * @param $request
     * @param $tagboardId integer
     * @return mixed
     */
    private function getTagboard(&$request, $tagboardId){
        return $request->user()->ideabooks()->find($tagboardId);
    }
    
}
