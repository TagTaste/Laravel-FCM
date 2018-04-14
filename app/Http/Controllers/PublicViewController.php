<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicViewController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function modelView(Request $request, $modelName , $id)
    {
        $class = "\\App\\PublicView\\" . ucwords($modelName);

        $model = $class::find($id);

        if(!$model){
            return response()->json(['data' => null, 'model' => null, 'errors' => ["Could not find model."]]);
        }
        $meta = $model->getMetaFor();
        $this->model = [$modelName=>$model,'meta'=>$meta];
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

        $this->model['shared'] = $sharedModel;
        $this->model['sharedBy'] = json_decode(\Redis::get('profile:small:' . $sharedModel->profile_id));
        $this->model['type'] = $modelName;
        $this->model[$modelName] = $model;
        $this->model['meta']= $sharedModel->getMetaForPublic();

        return response()->json(['data'=>$this->model]);

    }

}
