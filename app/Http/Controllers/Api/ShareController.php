<?php

namespace App\Http\Controllers\Api;

use App\Events\NewFeedable;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    private $column = "_id";
    
    private function setColumn(&$modelName)
    {
        $this->column = $modelName . $this->column;
    }
    
    private function getModel(&$modelName, &$id){
        $class = "\\App\\" . ucwords($modelName);
        return $class::find($id);
    }
    
    public function store(Request $request, $modelName, $id)
    {
        $this->setColumn($modelName);
        
        $sharedModel = $this->getModel($modelName,$id);
        
        if(!$sharedModel){
            return $this->sendError("Nothing found for given Id.");
        }
    
        $loggedInProfileId = $request->user()->profile->id;
        
        $sharedModel->additionalPayload = ['sharedBy'=>'profile:small:' . $loggedInProfileId,'shared'=>$modelName . ":" . $id];
        
        $class = "\\App\\Shareable\\" . ucwords($modelName);
        
        $share = new $class();
        $exists = $share->where('profile_id',$loggedInProfileId)
            ->where($this->column,$sharedModel->id)->exists();

        if($exists){
            return $this->sendError("You have already shared this.");
        }
        
        $this->model = $share->create(['profile_id'=>$loggedInProfileId, $this->column =>$sharedModel->id]);
        
        //push to feed
        event(new NewFeedable($sharedModel,$request->user()->profile,$this->model));
        
        return $this->sendResponse();
    }
    
    public function delete(Request $request, $modelName, $id)
    {
        $class = "\\App\\Shareable\\" . ucwords($modelName);
        $this->setColumn($modelName);
        $loggedInId = $request->user()->profile->id;
        $this->model = $class::where($this->column,$id)->where('profile_id',$loggedInId)->first();
        
        if($this->model){
            $this->model = $this->model->delete() ? true : false;
        }
        return $this->sendResponse();
    }

    
    
}
