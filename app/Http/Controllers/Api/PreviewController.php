<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class PreviewController extends Controller
{
    private function getModel(&$modelName, &$id)
    {
        $class = "\\App\\" . ucwords($modelName);
        return $class::find($id);
    }
    
    public function show(Request $request,$modelName,$modelId)
    {
        $sharedModel = $this->getModel($modelName, $modelId);
        
        if (!$sharedModel) {
            return $this->sendError("Nothing found for given Id.");
        }
        
        $this->model = $sharedModel->getPreviewContent();
        
        return $this->sendResponse();
        
    }
}
