<?php

namespace App\Http\Controllers\Api\Skynet;

use App\Collaborate;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Carbon\Carbon;

class NotificationController extends Controller
{

    // protected $model;
    // protected $now;
    
    /**
     * Create instance of controller with Model
     *
     * @return void
     */

    public function notifyUsers(Request $request)
    {
        
        file_put_contents(storage_path("logs") . "/skynet_test.txt", "\nTrying to push notification", FILE_APPEND);
        return $this->sendResponse();
    }

}
