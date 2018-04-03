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
            return $this->sendError("Could not find model.");
        }
        $meta = $model->getMetaFor();
        $this->model = [$modelName=>$model,'meta'=>$meta];
        return response()->json(['data'=>$this->model]);
    }
}
