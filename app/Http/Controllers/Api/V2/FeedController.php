<?php

namespace App\Http\Controllers\Api\V2;

use App\Channel\Payload;
use App\Strategies\Paginator;
use App\SuggestionEngine;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Redis;

class FeedController extends Controller
{
    protected $model = [];
    //things that is displayed on my (private) feed, and not on network or public
    public function feed(Request $request)
    {
        $page = $request->input('page');
        if($page > 20)
        {
            $this->errors[] = 'No more feed';
            return $this->sendResponse();
        }
        list($skip,$take) = Paginator::paginate($page);
        
        $profileId = $request->user()->profile->id;
        $payloads = Payload::join('subscribers','subscribers.channel_name','=','channel_payloads.channel_name')
            ->where('subscribers.profile_id',$profileId)
            //Query Builder's where clause doesn't work here for some reason.
            //Don't remove this where query.
            //Ofcourse, unless you know what you are doing.
//            ->whereRaw(\DB::raw('channel_payloads.created_at >= subscribers.created_at'))
            ->orderBy('channel_payloads.created_at','desc')
            ->skip($skip)
            ->take($take)
            ->get();
        if($payloads->count() === 0){
            $this->errors[] = 'No more feed';
            return $this->sendResponse();
        }
        $this->getMeta($payloads,$profileId);
        return $this->sendResponse();
    }
    
    //things that is displayed on my public feed
    public function public(Request $request, $profileId)
    {
        $page = $request->input('page',1);
        $take = 20;
        $skip = $page > 1 ? ($page - 1) * $take : 0;
        
        $payloads = Payload::where('channel_name','public.' . $profileId)
            ->orderBy('created_at','desc')
            ->skip($skip)
            ->take($take)
            ->get();
        $profileId = $request->user()->profile->id;
        $this->getMeta($payloads,$profileId);
    
        return $this->sendResponse();
    }
    
    //things that are posted by my network
    public function network(Request $request)
    {
        $page = $request->input('page',1);
        $take = 20;
        $skip = $page > 1 ? ($page - 1) * $take : 0;
        $profileId = $request->user()->profile->id;
        $payloads = Payload::join('subscribers','subscribers.channel_name','=','channel_payloads.channel_name')
            ->where('subscribers.profile_id',$profileId)
            //not my things, but what others have posted.
            ->where('subscribers.channel_name','not like','feed.' . $profileId)
            ->where('subscribers.channel_name','not like','public.' . $profileId)
            ->where('subscribers.channel_name','not like','network.' . $profileId)
    
            //Query Builder's where clause doesn't work here for some reason.
            //Don't remove this where query.
            //Ofcourse, unless you know what you are doing.
            ->whereRaw(\DB::raw('channel_payloads.created_at >= subscribers.created_at'))
            ->skip($skip)
            ->take($take)
            ->get();
        
        $this->getMeta($payloads,$profileId);
    
        return $this->sendResponse();
    }
    
    private function getMeta(&$payloads, &$profileId)
    {
        $indexTypeV2 = array("shared", "company", "sharedBy", "shoutout", "profile", "collaborate");
        $indexTypeV1 = array("photo", "polling");

        $index = 0;
        foreach ($payloads as $payload) {
            $type = null;
            $data = [];
            $index++;
            $cached = json_decode($payload->payload, true);
            foreach($cached as $name => $key){
                $cachedData = null;
                if (in_array($name, $indexTypeV2)) {
                    $key = $key.":V2";
                    $cachedData = Redis::connection('V2')->get($key);
                } else {
                    $cachedData = Redis::get($key);
                }
                if (!$cachedData) {
                    \Log::warning("could not get from $key");
                }
                $data[$name] = json_decode($cachedData,true);
            }


            if($payload->model !== null){
                $model = $payload->model;
                $type = $this->getType($payload->model);
                $model = $model::find($payload->model_id);
                if ($model !== null && method_exists($model, 'getMetaForV2')) {
                    $data['meta'] = $model->getMetaForV2($profileId);
                }
            }
            $data['type'] = $type;
            $this->model[] = $data;
        }
    }
    
    private function getType($modelName)
    {
        $exploded = explode('\\',$modelName);
        return strtolower(end($exploded));
    }
    //things that is displayed on company's public feed
    public function company(Request $request, $companyId)
    {

        $page = $request->input('page',1);
        $take = 20;
        $skip = $page > 1 ? ($page - 1) * $take : 0;

        $payloads = Payload::where('channel_name','company.public.' . $companyId)
            ->orderBy('created_at','desc')
            ->skip($skip)
            ->take($take)
            ->get();
        $profileId=$request->user()->profile->id;
        $this->getMeta($payloads,$profileId);

        return $this->sendResponse();
    }
  
}
