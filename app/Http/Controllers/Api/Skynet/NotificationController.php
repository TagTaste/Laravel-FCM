<?php

namespace App\Http\Controllers\Api\Skynet;



use App\Collaborate;
use App\Company;
use App\CompanyUser;
use App\Deeplink;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Surveys;
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
        $data = $request->all();
        if($data["type"] == "report_upload" || $data["type"] == "report_removed"){
            $collaborate = Collaborate::where('id',$data["collaborate_id"])->first();
            if ($collaborate === null) {
                return $this->sendResponse(false);
            }
            $collaborate->report_link = $data["report_link"] ?? '';
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
        }else if($data["type"] == "survey_user_invite"){
            $survey = Surveys::where('id', $data["survey_id"])->first();
            if($survey == null){
                return $this->sendResponse(false);
            }
            $companyObj = null;
            if (!empty($survey->company_id)) {
                $companyObj = Company::find($survey->company_id);
            }
            $profileIdsList = explode(',',$data["profile_ids"]);
            foreach ($profileIdsList as $profileId) {
                Redis::set("surveys:application_status:$survey->id:profile:$profileId", 1);
                $survey->profile_id = $profileId;                
                event(new \App\Events\Actions\surveyApplicantEvents(
                    $survey,
                    null,
                    null,
                    null,
                    'fill_survey',
                    null,
                    ["survey_url" => Deeplink::getShortLink("surveys", $survey->id), "survey_name" => $survey->title, 
                    "survey_id" => $survey->id, "profile" => (object)["id" => $companyObj->id, 
                    "name" => $companyObj->name, "image" => $companyObj->image], "is_private" => $survey->is_private, 
                    "type" => "inviteForReview"]
                ));
            }
        }
        return $this->sendResponse(true);
    }
}
