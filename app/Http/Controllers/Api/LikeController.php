<?php

namespace App\Http\Controllers\Api;

use App\PeopleLike;
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
    
    public function peopleLiked(Request $request,$modelName,$modelId)
    {
        $loggedInProfileId = $request->user()->profile->id ;
        $page = $request->has('page') ? $request->input('page') : 1;
        $peopleLike = new PeopleLike();
        $this->model = $peopleLike->peopleLike($modelId, $modelName ,$loggedInProfileId, $page,20);
        return $this->sendResponse();
    }
}
