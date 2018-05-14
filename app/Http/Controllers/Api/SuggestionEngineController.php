<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

class SuggestionEngineController extends Controller
{
    public function suggestion(Request $request,$modelName)
    {
        $key = 'suggested:'.$modelName.':'.$request->user()->profile->id;

        $modelIds = \Redis::sMembers($key);
        if($modelName == 'profile')
        {
            $profileIds = [];
            foreach ($modelIds as $key=>$modelId)
            {
                if($modelId == '')
                {
                    unset($modelIds[$key]);
                    continue;
                }
                $profileIds[$key] = "profile:small:".$modelId ;
            }

            if(count($profileIds)> 0)
            {
                $suggestedProfiles = \Redis::mget($profileIds);
            }
            foreach($suggestedProfiles as &$profile){
                if(is_null($profile)){
                    continue;
                }
                $profile = json_decode($profile);
                $key = "following:profile:".$request->user()->profile->id;
                $profile->isFollowing =  \Redis::sIsMember($key,$profile->id) === 1;
            }
            $this->model = $suggestedProfiles;
            return $this->sendResponse();
        }
    }
}