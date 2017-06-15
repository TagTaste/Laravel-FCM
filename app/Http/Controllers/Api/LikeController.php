<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(Request $request)
    {
        $model = $request->input('model');
        
        //hard coded, our bad
        if ($model == 'collaborate') {
            $model = 'collaboration';
        }
        
        $table = $model . "_likes";
        $model .= "_id";
        
        $id = $request->input('model_id');
        
        $this->model = \App\Profile::join($table, $table . ".profile_id", '=', 'profiles.id')
            ->where($model, $id)->get();
        
        return $this->sendResponse();
        
    }
}
