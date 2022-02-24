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
        if($data["type"] == "report_upload" || $data["type"] == "report_removed"){
            $collaborate = Collaborate::where('id',$data["collaborate_id"])->first();
            if ($collaborate === null) {
                return $this->sendResponse(false);
            }
            $collaborate->report_link = $data["report_link"] ?? "";
            
            if($data["type"] == "report_upload")
                $data["notification"] = "TagTaste has added a report to your collaboration ";
            else if($data["type"] == "report_removed")
                $data["notification"] = "A report has been removed from your collaboration ";

            if (isset($collaborate->company_id)&& (!is_null($collaborate->company_id))) {
                $company = Redis::get('company:small:' . $collaborate->company_id);
                $company = json_decode($company);
                $profileIds = CompanyUser::where('company_id',$collaborate->company_id)->get()->pluck('profile_id');
                foreach ($profileIds as $profileId) {
                    $collaborate->profile_id = $profileId;
                    event(new \App\Events\CollaborationReportUpload($collaborate, $data["notification"], $data["mode"]));
                }
            } else {
                event(new \App\Events\CollaborationReportUpload($collaborate, $data["notification"], $data["mode"]));
            }
        }
        return $this->sendResponse(true);
    }
}
