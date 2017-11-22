<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeopleLike extends Model
{
    public function peopleLike($modelId, $modelName, $loggedInProfileId, $page = 1 , $length = 3)
    {
        $key = "meta:{$modelName}:likes:$modelId";
        $profileIds = \Redis::SMEMBERS($key);

        $count = count($profileIds);
        $data = [];

        $profileIds = array_slice($profileIds ,($page - 1)*20 ,$length );

        foreach ($profileIds as $key => $value)
        {
            $profileIds[$key] = "profile:small:".$value ;
        }

        if(count($profileIds)> 0)
        {
            $data = \Redis::mget($profileIds);

        }
        foreach($data as &$profile){
            if(is_null($profile)){
                continue;
            }
            $profile = json_decode($profile);
            $profile->isFollowing = \Redis::sIsMember("followers:profile:".$profile->id,$loggedInProfileId) === 1;
            $profile->self = false;
        }
        return $length == 3 ? $data : ['count'=>$count ,'data'=>$data];
    }

}
