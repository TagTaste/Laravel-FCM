<?php

namespace App\Http\Controllers\Api\Skynet;

use App\Collaborate;
use App\CompanyUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Redis;
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
        $data = $request->input();
        $collaborate = Collaborate::where('id',$data["collaborate_id"])->first();
        if ($collaborate === null) {
            return $this->sendResponse(false);
        }
        
        if (isset($collaborate->company_id)&& (!is_null($collaborate->company_id))) {
            $company = Redis::get('company:small:' . $collaborate->company_id);
            $company = json_decode($company);
            $profileIds = CompanyUser::where('company_id',$collaborate->company_id)->get()->pluck('profile_id');
            foreach ($profileIds as $profileId) {
                $collaborate->profile_id = $profileId;
                file_put_contents(storage_path("logs") . "/skynet_test.txt", "\nHere profile id ". $profileId. "\n", FILE_APPEND);
                event(new \App\Events\CollaborationReportUpload($collaborate, $data["notification"], $data["mode"]));
            }
        } else {
            file_put_contents(storage_path("logs") . "/skynet_test.txt", "\nHere profile id ". $collaborate->profile_id. "\n", FILE_APPEND);
            event(new \App\Events\CollaborationReportUpload($collaborate, $data["notification"], $data["mode"]));
            // event(new \App\Events\Actions\Apply($collaborate, $request->user()->profile, $request->input("message","")));
        }
        

        file_put_contents(storage_path("logs") . "/skynet_test.txt", "\nTrying to push notification", FILE_APPEND);
        return $this->sendResponse(true);
    }
}
