<?php

namespace App\Http\Controllers\Api;

use App\Collaborate;
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
            'deeplink' => Deeplink::getShortLink($modelName, $modelId),
            'modelID' => $modelId,
            'model' => ucwords($modelName),
            'isShared' => false,
            'shareTypeID' => 0,

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

    public function seeall(Request $request, $modelName)
    {
        if ($modelName != 'collaborate') {
            return $this->sendError("Model not found.");
        }

        $collaborations = Collaborate::whereNull('deleted_at')->orderBy("created_at","desc");
        //paginate
        $page = $request->input('page');
        list($skip,$take) = \App\Strategies\Paginator::paginate($page);

        $this->model = [];
        $this->model["count"] = $collaborations->count();
        $this->model["data"] = $collaborations->skip($skip)->take($take)->get();

        return $this->sendResponse();
    }

}

