<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function feed(Request $request)
    {
        $profile = $request->user()->profile;
        $this->model = $profile->feed();
        return $this->sendResponse();
    }
    
    public function profile(Request $request)
    {
        $profile = $request->user()->profile;
        $this->model = $profile->profileFeed();
        return $this->sendResponse();
    }
    
    public function network(Request $request)
    {
        $profile = $request->user()->profile;
        $this->model = $profile->subscribedNetworksFeed();
        return $this->sendResponse();
    }
}
