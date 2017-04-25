<?php

namespace App\Http\Controllers\Api;

use App\Channel\Payload;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    //things that is displayed on my feed, and not on network or public
    public function feed(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $this->model = Payload::select('payload')
            ->join('subscribers','subscribers.channel_name','=','channel_payloads.channel_name')
            ->where('subscribers.profile_id',$profileId)
            ->where('subscribers.channel_name','not like','network.' . $profileId)
            ->where('subscribers.channel_name','not like','public.' . $profileId)
            ->orderBy('channel_payloads.created_at','desc')
            ->paginate();
        return $this->sendResponse();
    }
    
    //things that is deplayed on my public feed
    public function public(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $this->model = Payload::select('payload')
            ->where('channel_name','public.' . $profileId)
            ->orderBy('created_at','desc')->paginate();
        return $this->sendResponse();
    }
    
    //things that are posted by my network
    public function network(Request $request)
    {
        $profileId = $request->user()->profile->id;
        $this->model = Payload::select('payload')
            ->join('subscribers','subscribers.channel_name','=','channel_payloads.channel_name')
            ->where('subscribers.profile_id',$profileId)
            ->where('subscribers.channel_name','not like','feed.' . $profileId)
            ->where('subscribers.channel_name','not like','profile.' . $profileId)
            ->where('subscribers.channel_name','not like','network.' . $profileId)
            ->paginate();
        return $this->sendResponse();
    }
}
