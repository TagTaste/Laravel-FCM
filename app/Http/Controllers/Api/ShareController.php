<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function share(Request $request, $modelName, $id)
    {
        $class = "\\App\\" . $modelName;
        $model = $class::find($id);
        
        if(!$model){
            return $this->sendError("Nothing found for given Id.");
        }
    
        $loggedInProfileId = $request->user()->profile->id;
    
        try {
            $this->model = (new \App\Share())->setTable($modelName . "_shares")->insert(['profile_id'=>$loggedInProfileId, $modelName . '_id'=>$model->id]);
        } catch (\Exception $e){
            \Log::info($e->getMessage());
            return $this->sendError($modelName . " cannot be shared.");
        }
        
        return $this->sendResponse();

    }
}
