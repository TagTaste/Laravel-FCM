<?php

namespace App\Http\Controllers\Api;

use App\Channel\Payload;
use App\Strategies\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class FeedController extends Controller
{
    protected $model = [];
    protected $modelNotIncluded = [];
    //things that is displayed on my (private) feed, and not on network or public
    public function feed(Request $request)
    {
        $page = $request->input('page');
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
    protected function removeReportedPayloads($profileId)
    {
        $reported_payload = Payload::leftJoin('report_content','report_content.payload_id','=','channel_payloads.id')
            ->where('report_content.profile_id', $profileId)
            ->pluck('channel_payloads.id')->toArray();
        $this->modelNotIncluded = array_merge($this->modelNotIncluded,$reported_payload);
    }

    protected function validatePayloadForVersion($request)
    {
        if(($request->header('x-version') != null 
                && $request->header('x-version') < 80) || 
            ($request->header('x-ios-version') != null 
                && version_compare("4.2.7", $request->header('x-ios-version'))))   {
                    $pollPayloadIds = $this->getNewVersionOfPollPayloads();
                    $this->modelNotIncluded = array_merge($this->modelNotIncluded,$pollPayloadIds);
            }
    }
    protected function getNewVersionOfPollPayloads()
    {
        $modelNotIncluded = [];
        $pollPayloadsWithImage = \App\Polling::where('type','!=',3)
                ->pluck('payload_id')->toArray();
        $sharedPollWithImage = \App\Polling::join('polling_shares','polling_shares.poll_id','=','poll_questions.id')
                ->where('type','!=',3)
                ->pluck('polling_shares.payload_id')
                ->toArray();
        //      return array_merge($pollPayloadsWithImage,$pollPayloadWithOptionImage);
        return  Payload::whereIn('id',array_merge($pollPayloadsWithImage,$sharedPollWithImage))->pluck('channel_payloads.id')->toArray();
    }
    //things that is displayed on my public feed
    public function public(Request $request, $profileId)
    {
        $page = $request->input('page',1);
        $take = 20;
        $skip = $page > 1 ? ($page - 1) * $take : 0;

        $profile_id = $request->user()->profile->id;
        $this->validatePayloadForVersion($request);
        $this->removeReportedPayloads($profileId);
        
        $payloads = Payload::where('channel_payloads.channel_name','public.' . $profileId)
            ->whereNotIn('channel_payloads.id', $this->modelNotIncluded)
            ->orderBy('channel_payloads.created_at','desc')
            ->skip($skip)
            ->take($take)
            ->get();
        $this->getMeta($payloads,$profile_id);

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
//        if($payloads->count() === 0){
//            $this->errors[] = 'No more feeds';
//            return;
//        }

        foreach($payloads as $payload){
            $type = null;
            $data = [];

            $cached = json_decode($payload->payload, true);

            foreach($cached as $name => $key){
                $cachedData = Redis::get($key);
                if(!$cachedData){
                    \Log::warning("could not get from $key");
                }
                $data[$name] = json_decode($cachedData,true);
            }
            if($payload->model !== null){
                $model = $payload->model;
                $type = $this->getType($payload->model);
                $model = $model::with([])->where('id',$payload->model_id)->first();
                if($model !== null && method_exists($model, 'getMetaFor') && $profileId != null){
                    $data['meta'] = $model->getMetaFor($profileId);;
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

        $profile_id = $request->user()->profile->id;
        $reported_payload = Payload::leftJoin('report_content','report_content.payload_id','=','channel_payloads.id')
            ->where('report_content.profile_id', $profile_id)
            ->pluck('channel_payloads.id')->toArray();
            
        $payloads = Payload::where('channel_payloads.channel_name','company.public.' . $companyId)
            ->whereNotIn('channel_payloads.id', $reported_payload)
            ->orderBy('channel_payloads.created_at','desc')
            ->skip($skip)
            ->take($take)
            ->get();
        $this->getMeta($payloads,$profile_id);

        return $this->sendResponse();
    }


}
