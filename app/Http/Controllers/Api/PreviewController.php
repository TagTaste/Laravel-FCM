<?php

namespace App\Http\Controllers\Api;

use App\Deeplink;
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

        $data = $sharedModel->getPreviewContent();

        $res = [
            'title' => $data['ogTitle'],
            'image' => $data['ogImage'],
            'description' => $data['ogDescription'],
            'type' => 'article',
            'url' => $data['redirectUrl'],
            'site_name' => 'TagTaste',
            'deeplink' => Deeplink::getShortLink($modelName, $modelId),
            'modelID' => $modelId,
            'model' => ucwords($modelName),

        ];

        $this->model = $res;
        
        return $this->sendResponse();
        
    }

}
