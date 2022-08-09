<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Traits\HashtagFactory;
use App\Strategies\Paginator;
use App\Channel\Payload;
use Illuminate\Support\Facades\Redis;

class HashtagController extends Controller
{
    use HashtagFactory;
    public $modelNotIncluded = [];
    public function suggestions(Request $request)
    {
        $key = $request->q;
        if($key == null || !isset($key))    {
            $this->model = $this->trendingHashtags();
            return $this->sendResponse();
        } else {
            $this->model = $this->hashtagSuggestions($key);
            return $this->sendResponse();
        }
    }

    public function trending()
    {
        $this->model = $this->trendingHashtags();
        return $this->sendResponse();
    }

    public function feed(Request $request)
    {
        $key = $request->q;
        if($key == null || !isset($key)) {
            return $this->sendError("select a valid hashtag");
        }
        $models = $this->getModelsForFeed($key);
        $this->model = $this->generateFeed($models, $request);
        return $this->sendResponse();
    }

    protected function generateFeed($models,$request)
    {
        $profileId = $request->user()->profile->id;
        $this->removeReportedPayloads($profileId);
        $page = $request->input('page');
        
        list($skip,$take) = Paginator::paginate($page, 20);
        $payloadIds = $this->getPayloadIds($models,$skip,$take);
        $payloads = Payload::whereIn('id',$payloadIds)
            ->whereNotIn('channel_payloads.id', $this->modelNotIncluded)
            ->where('channel_payloads.account_deactivated',0)
            //Query Builder's where clause doesn't work here for some reason.
            //Don't remove this where query.
            //Ofcourse, unless you know what you are doing.
//            ->whereRaw(\DB::raw('channel_payloads.created_at >= subscribers.created_at'))
            ->orderBy('channel_payloads.created_at','desc')
            // ->skip($skip)
            // ->take($take)
            ->get();
        if($payloads->count() === 0){
            return [];
        }
        return $this->getMeta($payloads, $profileId, $request->user()->profile);
    }
    
    protected function removeReportedPayloads($profileId)
    {
        $reported_payload = Payload::leftJoin('report_content','report_content.payload_id','=','channel_payloads.id')
            ->where('report_content.profile_id', $profileId)
            ->pluck('channel_payloads.id')->toArray();
        $this->modelNotIncluded = array_merge($this->modelNotIncluded,$reported_payload);
    }

    protected function getPayloadIds($models, $skip, $take)
    {
        $payloadIds = [];
        for($i = $skip; $i < ($skip+$take) ; $i++) {
            if(isset($models[$i])) {
                $temp = explode("'",$models[$i]);
                $temp1 = Payload::where('model',$temp[0])
                                ->where('model_id',$temp[1])
                                ->first();
                if($temp1 == null && $temp[0] == 'App\\V2\\Photo') {
                    $temp[0] = 'App\\Photo';
                    $temp1 = Payload::where('model',$temp[0])
                                ->where('model_id',$temp[1])
                                ->first();
                }
                if($temp1 != null && isset($temp1))
                $payloadIds[] = $temp1->id;
            }
        }
       return $payloadIds;
    }

    private function getMeta(&$payloads, &$profileId, $profile)
    { 
        $indexTypeV2 = array("shared", "company", "sharedBy", "shoutout", "profile", "collaborate");
        $indexTypeV1 = array("photo", "polling");
        $index = 0;
        foreach ($payloads as $payload) {
            $type = null;
            $data = [];
            $cached = json_decode($payload->payload, true);
            foreach ($cached as $name => $key) {
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


            if ($payload->model !== null) {
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

        return array_values(array_filter($this->model));

    }

    private function getType($modelName)
    {
        $exploded = explode('\\',$modelName);
        return strtolower(end($exploded));
    }
}
