<?php

namespace App\Http\Controllers\Api;

use App\Collaborate;
use App\Deeplink;
use Illuminate\Http\Request;
use App\Traits\GetTags;
use App\Traits\HasPreviewContent;

class PreviewController extends Controller
{
    use GetTags, HasPreviewContent;
    private function getModel(&$modelName, &$id)
    {
        if($modelName == 'product')
            $class = "\\App\\" . "PublicReviewProduct";
        else
            $class = "\\App\\" . ucwords($modelName);
        return $class::find($id);
    }
    
    public function show(Request $request,$modelName,$modelId)
    {
        $model = $this->getModel($modelName, $modelId);
        if (!$model) {
            return $this->sendError("Nothing found for given Id.");
        }
        $modelData = $model;
        $data = $model->getPreviewContent();
        $deepLink = Deeplink::getShortLink($modelName, $modelId);
        $modelData = $model;
        if(isset($modelData->caption)) {
            $modelData->caption = $this->getContent($modelData->caption);
        }
        if(isset($modelData->content)) {
            $modelData->content = $this->getContent($modelData->content);
        }
        $res = [
            'title' => $data['ogTitle'],
            'image' => $data['ogImage'],
            'description' => $data['ogDescription'],
            'type' => 'article',
            'url' => $data['redirectUrl'],
            'site_name' => 'TagTaste',
            'deeplink' => $deepLink,
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

        ];

        $this->model = $res;

        return $this->sendResponse();
    }

}

