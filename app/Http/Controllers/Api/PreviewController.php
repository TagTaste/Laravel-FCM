<?php

namespace App\Http\Controllers\Api;

use App\Collaborate;
use App\Deeplink;
use Illuminate\Http\Request;

class PreviewController extends Controller
{
    private function getModel(&$modelName, &$id)
    {
        if($modelName == 'product')
            $class = "\\App\\" . "PublicReviewProduct";
        else
            $class = "\\App\\" . ucwords($modelName);
        if($modelName === 'collaborate')
        {
            $model = $class::where('id',$id)->where('state',$class::$state[0])->whereNull('deleted_at')->first();
        }
        else {
            $model = $class::find($id);
        }
        return $model;
    }
    
    public function show(Request $request,$modelName,$modelId)
    {
        $model = $this->getModel($modelName, $modelId);

        if (!$model) {
            return $this->sendError("Nothing found for given Id.");
        }
        $modelData = $model;
        $data = $model->getPreviewContent();

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
            'isShared' => false,
            'shareTypeID' => 0,
            'deepLinkText' => Deeplink::getDeepLinkText($modelName, $modelData)
        ];

        $this->model = $res;
        
        return $this->sendResponse();
        
    }

    public function showShared(Request $request, $modelName,$modelId, $shareId)
    {
        $model = $this->getModel($modelName, $modelId);

        if (!$model) {
            return $this->sendError("Nothing found for given Id.");
        }
        $data = $model->getPreviewContent();

        $res = [
            'title' => $data['ogTitle'],
            'image' => $data['ogImage'],
            'description' => $data['ogDescription'],
            'type' => 'article',
            'url' => $data['redirectUrl'],
            'site_name' => 'TagTaste',
            'deeplink' => Deeplink::getShortLink($modelName, $modelId, true, $shareId),
            'modelID' => $modelId,
            'model' => ucwords($modelName),
            'isShared' => true,
            'shareTypeID' => $shareId,
            'deepLinkText' => Deeplink::getDeepLinkText($modelName, $model)

        ];

        $this->model = $res;

        return $this->sendResponse();
    }

}

