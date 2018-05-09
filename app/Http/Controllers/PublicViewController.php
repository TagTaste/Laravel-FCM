<?php

namespace App\Http\Controllers;

use App\Deeplink;
use App\Traits\GetTags;
use App\Traits\HasPreviewContent;
use Illuminate\Http\Request;

class PublicViewController extends Controller
{
    use GetTags, HasPreviewContent;
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function modelView(Request $request, $modelName , $id)
    {
        if($modelName == 'jobs') $modelName = 'job';

        $class = "\\App\\PublicView\\" . ucwords($modelName);

        $model = $class::find($id);

        if(isset($model->content['text'])) {
            $model->content = $this->getContentForHTML($model->content);
        }
        if(isset($model->caption) && isset($model->caption['text'])) {
            $model->caption = $this->getContentForHTML($model->caption);
        }

        if(!$model){
            return response()->json(['data' => null, 'model' => null, 'errors' => ["Could not find model."]]);
        }
        $meta = $model->getMetaForPublic();
        $this->model = [$modelName=>$model,'meta'=>$meta];
        $socialPreview = $model->getPreviewContent();
        $socialPreview['ogUrl'] = Deeplink::getActualUrl($modelName, $id);
        $this->model['social'] = [];
        $this->model['social']['metaTags'] = $this->getSocialPreview($socialPreview);
        $this->model['social']['deeplink'] = Deeplink::getShortLink($modelName, $id);
        return response()->json(['data'=>$this->model]);
    }

    public function modelSharedView(Request $request, $modelName, $id, $sharedId)
    {
        $this->model = [];
        $modelName = strtolower($modelName);

        $shareClass = "\\App\\Shareable\\" . ucwords($modelName);
        $modelClass = "\\App\\PublicView\\" . ucwords($modelName);

        $share = new $shareClass();
        $sharedModel = $share->where('id', $sharedId)->whereNull('deleted_at')->first();

        $model = $modelClass::find($id);

        if(isset($model->content['text'])) {
            $model->content = $this->getContentForHTML($model->content);
        }

        if(isset($model->caption) && isset($model->caption['text'])) {
            $model->caption = $this->getContentForHTML($model->caption);
        }

        if (!$model) {
            return response()->json(['data' => null, 'model' => null, 'errors' => ["Nothing found for given Id."]]);
        }

        if (!$sharedModel) {
            return response()->json(['data' => null, 'model' => null, 'errors' => ["Nothing found for given shared model."]]);
        }

        $this->model['shared'] = $sharedModel;
        $this->model['sharedBy'] = json_decode(\Redis::get('profile:small:' . $sharedModel->profile_id));
        $this->model['type'] = $modelName;
        $this->model[$modelName] = $model;
        $this->model['meta']= $sharedModel->getMetaForPublic();
        $socialPreview = $model->getPreviewContent();
        $socialPreview['ogUrl'] = Deeplink::getActualUrl($modelName, $id, true, $sharedId);
        $this->model['social'] = [];
        $this->model['social']['metaTags'] = $this->getSocialPreview($socialPreview);
        $this->model['social']['deeplink'] = Deeplink::getShortLink($modelName, $id, true, $sharedId);

        return response()->json(['data'=>$this->model]);

    }

    private function getSocialPreview($preview) : array
    {
        $res = [];
        $res[] = ['property'=> 'og:title', 'content' => $preview['ogTitle']];
        $res[] = ['property'=> 'og:image', 'content' => $preview['ogImage']];
        $res[] = ['property'=> 'og:description', 'content' => $preview['ogDescription']];
        $res[] = ['property'=> 'og:url', 'content' => $preview['ogUrl']];
        $res[] = ['property'=> 'og:type', 'content' => 'article'];
//        $res['og:title'] = $preview['ogTitle'];
//        $res['og:image'] = $preview['ogImage'];
//        $res['og:description'] = $preview['ogDescription'];
//        $res['og:url'] = $preview['ogUrl'];
//        $res['og:type'] = 'article';

        return $res;

    }

}
