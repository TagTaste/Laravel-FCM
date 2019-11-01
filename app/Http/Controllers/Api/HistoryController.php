<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class HistoryController extends Controller
{
    public function history(Request $request,$type)
    {
        $userId = $request->user()->id;
        $this->model = Redis::lRange("history:$type:$userId",0,9);
        return $this->sendResponse();
    }
    
}
