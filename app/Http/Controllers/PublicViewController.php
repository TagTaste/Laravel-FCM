<?php

namespace App\Http\Controllers;

use App\Deeplink;
use App\PublicView\Collaborate;
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

        // Added to retrieve profile details from handle
        if($modelName === 'profile' && starts_with($id, '@')) {
            $model = $class::where('handle', substr($id,1))->first();
            $id = isset($model->id) ? $model->id : null;
        }
        else if($modelName === 'collaborate')
        {
            $model = $class::where('id',$id)->where('state',$class::$state[0])->whereNull('deleted_at')->first();
        }
        else {
            $model = $class::find($id);
        }

        if(!$model){
            return response()->json(['data' => null, 'model' => null, 'errors' => ["Could not find model."]]);
        }

        if(isset($model->content['text'])) {
            $model->content = $this->getContentForHTML($model->content);
        }
        if(isset($model->caption) && isset($model->caption['text'])) {
            $model->caption = $this->getContentForHTML($model->caption);
        }
        $meta = $model->getMetaForPublic();
        $this->model = [$modelName=>$model,'meta'=>$meta];
        $socialPreview = $model->getPreviewContent();
        $socialPreview['ogUrl'] = Deeplink::getActualUrl($modelName, $id);
        $this->model['social'] = [];
        $this->model['social']['deeplink'] = Deeplink::getShortLink($modelName, $id);
        $this->model['social']['deeplink_text'] = Deeplink::getDeepLinkText($modelName,$model);
        $this->model['social']['metaTags'] = $this->getSocialPreview($socialPreview,$modelName, $this->model['social']['deeplink'],$this->model['social']['deeplink_text']);
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

        if (!$model) {
            return response()->json(['data' => null, 'model' => null, 'errors' => ["Nothing found for given Id."]]);
        }

        if (!$sharedModel) {
            return response()->json(['data' => null, 'model' => null, 'errors' => ["Nothing found for given shared model."]]);
        }

        if(isset($model->content['text'])) {
            $model->content = $this->getContentForHTML($model->content);
        }

        if(isset($model->caption) && isset($model->caption['text'])) {
            $model->caption = $this->getContentForHTML($model->caption);
        }

        $this->model['shared'] = $sharedModel;
        $this->model['sharedBy'] = json_decode(\Redis::get('profile:small:' . $sharedModel->profile_id));
        $this->model['type'] = $modelName;
        $this->model[$modelName] = (array) $model;
        $this->model['meta']= $sharedModel->getMetaForPublic();
        $socialPreview = $model->getPreviewContent();
        $socialPreview['ogUrl'] = Deeplink::getActualUrl($modelName, $id, true, $sharedId);
        $this->model['social'] = [];
        $this->model['social']['deeplink'] = Deeplink::getShortLink($modelName, $id, true, $sharedId);
        $this->model['social']['deeplink_text'] = Deeplink::getDeepLinkText($modelName,$model);
        $this->model['social']['metaTags'] = $this->getSocialPreview($socialPreview,$modelName, $this->model['social']['deeplink'],$this->model['social']['deeplink_text']);
        return response()->json(['data' => $this->model]);
    }

    private function getSocialPreview($preview,$modelName,$deepLink,$deepLinkText) : array
    {
        $res = [];
        $res[] = ['property'=> 'og:title', 'content' => $preview['ogTitle']];
        $res[] = ['property'=> 'og:image', 'content' => $preview['ogImage']];
        $res[] = ['property'=> 'og:description', 'content' => $preview['ogDescription']];
        $res[] = ['property'=> 'og:url', 'content' => $preview['ogUrl']];
        $res[] = ['property'=> 'og:type', 'content' => 'article'];
        $res[] = ['property'=> 'og:modelId', 'content' => $preview['modelId']];
        $res[] = ['property'=> 'og:modelName', 'content' => $modelName];
        $res[] = ['property'=> 'og:deepLinkUrl', 'content' => $deepLink];
        $res[] = ['property'=> 'og:deepLinkText','content'=>$deepLinkText];
//        $res['og:title'] = $preview['ogTitle'];
//        $res['og:image'] = $preview['ogImage'];
//        $res['og:description'] = $preview['ogDescription'];
//        $res['og:url'] = $preview['ogUrl'];
//        $res['og:type'] = 'article';

        return $res;

    }

    public function similarModelView(Request $request, $modelName , $id)
    {
        if($modelName == 'jobs') $modelName = 'job';

        $class = "\\App\\PublicView\\" . ucwords($modelName);

        $this->model = $class::whereNotIn('id',[$id])->where('state',1)->whereNull('deleted_at')->skip(0)->take(10)->get();

        if(!$this->model){
            return response()->json(['data' => null, 'model' => null, 'errors' => ["Could not find model."]]);
        }
        return response()->json(['data'=>$this->model]);
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

        return response()->json(['data'=>$this->model]);
    }

    /**
     * Display a listing of the resource foodshot.
     *
     * @return \Illuminate\Http\Response
     */
    public function foodShot(Request $request, $modelId)
    {
        $class = "\\App\\PublicView\\" . ucwords("product");
        $product = $class::where('id',$modelId)->first();
        if ($product == null) {
            return $this->sendError("Product is not available");
        }

        //paginate
        $page = $request->input('page') ? intval($request->input('page')) : 1;
        $page = $page == 0 ? 1 : $page;
        $take = 20;
        $skip = ($page - 1) * $take;
        $sortBy = $request->has('sort_by') ? $request->input('sort_by') : 'DESC';
        $sortBy = $sortBy == 'DESC' ? 'DESC' : 'ASC';
        $header = \App\PublicReviewProduct\ReviewHeader::where('global_question_id',$product->global_question_id)->where('header_selection_type',2)->first();

        $food_shots = \App\PublicReviewProduct\Review::where('product_id',$modelId)->where('header_id',$header->id)
            ->where('select_type',5)
            ->orderBy('updated_at',$sortBy)
            ->get();
        
        $final_data = [];

        if (count($food_shots)) {
            $food_shots = $food_shots->toArray();
            foreach ($food_shots as $key => $food_shot) {
                if (!is_null($food_shot['meta'])) {
                    $final_data[] = $food_shot;
                }
            }
        }
        $this->model = array_splice($final_data, $skip, $take);
        return response()->json(['data'=>$this->model]);
    }

}
