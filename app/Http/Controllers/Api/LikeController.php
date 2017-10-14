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

    public function peopleLiked(Request $request,$modelName,$modelId)
    {
        $key = "meta:{$modelName}:likes:$modelId";
        $profileIds = \Redis::SMEMBERS($key);

        $loginProfileId = $request->user()->profile->id;

        foreach ($profileIds as &$profileId)
        {
            $profileId = "profile:small:".$profileId;
        }
        $data = [];
        if(count($profileIds)) {
            $data = \Redis::mget($profileIds);
        }

        foreach ($data as &$datum)
        {
            $datum = json_decode($datum,true);
            $datum['isFollowing'] = \Redis::sIsMember("following:profile:" . $loginProfileId,$datum['id']) == 1;
            $datum['self'] = $loginProfileId === $datum['id'];
        }

        $this->model = $data;

        return $this->sendResponse();

    }
}
