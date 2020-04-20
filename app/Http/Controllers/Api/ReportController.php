<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Api\Controller;
use App\Strategies\Paginator;
use Illuminate\Http\Request;
use App\ReportType;
use App\ReportContent;
use App\ReportUser;
use Carbon\Carbon;
use App\Channel\Payload;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getReportTypeList(Request $request, $reportCategory)
    {
        $this->errors['status'] = 0;

        $report_category_type = array("content", "profile", "company");
        if (!in_array($reportCategory, $report_category_type)) {
            $this->errors['status'] = 1;
            $this->errors['message'] = 'Please provide proper reporty category type such as "content", "profile", "company".';
            return $this->sendResponse();
        }

        $this->model = ReportType::where("type", $reportCategory)->where('is_active', 1)->get();
        return $this->sendResponse();
    }

    public function reportContent(Request $request)
    {
        $this->errors['status'] = 0;
        $profile_id = $request->user()->profile->id;

        $email_input = array(
            "type" => "Post",
            "profile_url" => env('APP_URL')."/@".$request->user()->profile->handle,
            "url" => null,  
            "issue" => null,
            "report_on" => null, 
            "reporter_name" => null != $request->user()->name ? $request->user()->name : "N/A",
            "email_id" => null != $request->user()->email ? $request->user()->email : "N/A",
            "phone_number" => null != $request->user()->profile->phone ? $request->user()->profile->phone : "N/A"
        );

        // reported profile id check
        $reported_profile_id = null !== $request->input('reported_profile_id') ? $request->input('reported_profile_id') : null; 
        if ("" == $reported_profile_id) {
            $reported_profile_id = null;
        }
        if (preg_match('/[^0-9]/', $reported_profile_id)) {
          $this->errors['status'] = 1;
          $this->errors['message'] = 'Please provide valid profile id to whom you want to report.';
          return $this->sendResponse();
        } else if (!is_null($reported_profile_id)) {
            $reported_profile_id = (int)$reported_profile_id;
        }

        // reported company id check
        $reported_company_id = null !== $request->input('reported_company_id') ? $request->input('reported_company_id') : null; 
        if ("" == $reported_company_id) {
            $reported_company_id = null;
        }
        if (preg_match('/[^0-9]/', $reported_company_id)) {
          $this->errors['status'] = 1;
          $this->errors['message'] = 'Please provide valid company id to whom you want to report.';
          return $this->sendResponse();
        } else if (!is_null($reported_company_id)) {
            $reported_company_id = (int)$reported_company_id;
        }

        // category of content
        $valid_content_type = array("shoutout", "photo", "product", "polling", "collaborate");
        $content_type = null !== $request->input('content_type') ? $request->input('content_type') : null;
        if (!in_array($content_type, $valid_content_type)) {
            $this->errors['status'] = 1;
            $this->errors['message'] = 'Please provide proper content type to which you want to report.';
            return $this->sendResponse();
        }

        // content id
        $content_id = null !== $request->input('content_id') ? $request->input('content_id') : null; 
        if ("" == $content_id || is_null($content_id)) {
            $this->errors['status'] = 1;
            $this->errors['message'] = 'Please provide valid content id to which you want to report.';
            return $this->sendResponse();
        } else {
            if ($content_type != "product") {
                if (preg_match('/[^0-9]/', $content_id)) {
                  $this->errors['status'] = 1;
                  $this->errors['message'] = 'Please provide valid content id to whom you want to report.';
                  return $this->sendResponse();
                }  else {
                    $content_id = (int)$content_id;
                }
            }
        }

        // is shared
        $shared_id = null;
        $is_shared = null !== $request->input('is_shared') ? (bool)$request->input('is_shared') : null;
        if (is_null($is_shared)) {
            $this->errors['status'] = 1;
            $this->errors['message'] = 'Please provide where content is shared content or not.';
            return $this->sendResponse();
        } else if ($is_shared) {
            // content id
            $shared_id = null !== $request->input('shared_id') ? $request->input('shared_id') : null; 
            if ("" == $shared_id || is_null($shared_id)) {
                $this->errors['status'] = 1;
                $this->errors['message'] = 'Please provide valid share id to which you want to report.';
                return $this->sendResponse();
            } else if (preg_match('/[^0-9]/', $shared_id)) {
              $this->errors['status'] = 1;
              $this->errors['message'] = 'Please provide valid share id to whom you want to report.';
              return $this->sendResponse();
            } else {
                $shared_id = (int)$shared_id;
            }
        }
        

        // report type id
        $report_type_id = null;
        $report_type_name = null;
        $report_id = null !== $request->input('report_type_id') ? $request->input('report_type_id') : null;
        if ("" == $report_id || is_null($report_id)) {
            $this->errors['status'] = 1;
            $this->errors['message'] = 'Please provide valid report type id.';
            return $this->sendResponse();
        } else if (preg_match('/[^0-9]/', $report_id)) {
          $this->errors['status'] = 1;
          $this->errors['message'] = 'Please provide valid report type id.';
          return $this->sendResponse();
        } else {
            $report_type_detail = ReportType::where('id',(int)$report_id)
                ->where('is_active', 1)
                ->get()
                ->first();
            if (is_null($report_type_detail)) {
                $this->errors['status'] = 1;
                $this->errors['message'] = 'Provided report type id is not associated with our system.';
                return $this->sendResponse();
            }
            $report_type_id = $report_type_detail->id;
            $report_type_name = $report_type_detail->name;
        }

        $email_input["issue"] = null != $report_type_name ? $report_type_name : "N/A";
        
        // report content
        $report_comment = null !== $request->input('report_comment') ? $request->input('report_comment') : null;

        $payload_info = array(
            "photo" => array("App\Photo", "App\V2\Photo"),
            "shoutout" => array("App\Shoutout"),
            "polling"=> array("App\Polling"),
            "collaborate"=> array("App\Collaborate"),
            "shareable_photo" => array("App\Shareable\Photo"),
            "shareable_shoutout" => array("App\Shareable\Shoutout"),
            "shareable_polling"=> array("App\Shareable\Polling"),
            "shareable_product"=> array("App\Shareable\Product"),
            "shareable_collaborate"=> array("App\Shareable\Collaborate"),

        );

        $payload_id = null;
        $payload_url = null;
        if ($is_shared) {
            if ("photo" == $content_type) {
                $payload_id = $this->getPayloadId($payload_info["shareable_photo"], $shared_id);
                $payload_url = env('APP_URL')."/shared/".$shared_id."/photo/".$content_id;
            } else if ("shoutout" == $content_type) {
                $payload_id = $this->getPayloadId($payload_info["shareable_shoutout"], $shared_id);
                $payload_url = env('APP_URL')."/shared/".$shared_id."/shoutout/".$content_id;
            } else if ("collaborate" == $content_type) {
                $payload_id = $this->getPayloadId($payload_info["shareable_collaborate"], $shared_id);
                $payload_url = env('APP_URL')."/shared/".$shared_id."/collaborate/".$content_id;
            } else if ("product" == $content_type) {
                $payload_id = $this->getPayloadId($payload_info["shareable_product"], $shared_id);
                $payload_url = env('APP_URL')."/shared/".$shared_id."/product/".$content_id;
            } else if ("polling" == $content_type) {
                $payload_id = $this->getPayloadId($payload_info["shareable_polling"], $shared_id);
                $payload_url = env('APP_URL')."/shared/".$shared_id."/polling/".$content_id;
            }
        } else {
            if ("photo" == $content_type) {
                $payload_id = $this->getPayloadId($payload_info["photo"], $content_id);
                $payload_url = env('APP_URL')."/photo/".$content_id;
            } else if ("shoutout" == $content_type) {
                $payload_id = $this->getPayloadId($payload_info["shoutout"], $content_id);
                $payload_url = env('APP_URL')."/shoutout/".$content_id;
            } else if ("collaborate" == $content_type) {
                $payload_id = $this->getPayloadId($payload_info["collaborate"], $content_id);
                $payload_url = env('APP_URL')."/collaborations/".$content_id;
            } else if ("polling" == $content_type) {
                $payload_id = $this->getPayloadId($payload_info["polling"], $content_id);
                $payload_url = env('APP_URL')."/polling/".$content_id;
            }
        }

        if (is_null($payload_id)) {
            $this->errors['status'] = 1;
            $this->errors['message'] = 'Provided data of content_id and content_type is not associated with our system.';
            return $this->sendResponse();
        }

        $input = array(
            "report_type_id" => $report_type_id,  
            "report_type_name" => $report_type_name,
            "report_comment" => $report_comment, 
            "payload_id" => $payload_id,
            "data_type" => $content_type,
            "data_id" => (string)$content_id,
            "is_shared" => (bool)$is_shared,
            "shared_id" => $shared_id,
            "profile_id" => $profile_id,
            "reported_profile_id" => $reported_profile_id,
            "reported_company_id" => $reported_company_id,
            "is_active" => (bool)1,
        );

        $report_exist = ReportContent::where("profile_id", $input['profile_id'])
            ->where("data_type", $input['data_type'])
            ->where("data_id", $input['data_id'])
            ->where("is_shared", $input['is_shared'])
            ->where("shared_id", $input['shared_id'])
            ->get()
            ->first();
        if (!is_null($report_exist)) {
            $this->model = $report_exist;
            $this->errors['message'] = "Already reported.";
        } else {
            $this->model = ReportContent::create($input);
            $this->errors['message'] = "Reported.";
            $email_input["url"] = $payload_url;
            $email_input["report_on"] = \Carbon\Carbon::now()->format('jS F Y \\a\\t h:i:s A');
            event(new \App\Events\ReportContentUserEvent(
                $email_input["type"],
                $email_input["profile_url"],
                $email_input["url"],
                $email_input["issue"],
                $email_input["report_on"],
                $email_input["reporter_name"],
                $email_input["email_id"],
                $email_input["phone_number"]
            ));
        }
        
        return $this->sendResponse();
    }

    public function getPayloadId($model_type, $model_id)
    {
        $payload_detail = Payload::whereIn("model", $model_type)
            ->where("model_id", $model_id)
            ->first();
        if (is_null($payload_detail)) {
            return null;
        }
        return $payload_detail['id'];
    }

    public function reportUser(Request $request)
    {
        $this->errors['status'] = 0;
        $profile_id = $request->user()->profile->id;

        $email_input = array(
            "type" => "N/A",
            "profile_url" => env('APP_URL')."/@".$request->user()->profile->handle,
            "url" => null,  
            "issue" => null,
            "report_on" => null, 
            "reporter_name" => null != $request->user()->name ? $request->user()->name : "N/A",
            "email_id" => null != $request->user()->email ? $request->user()->email : "N/A",
            "phone_number" => null != $request->user()->profile->phone ? $request->user()->profile->phone : "N/A"
        );

        // category of user
        $valid_user_type = array("profile", "company");
        $user_type = null !== $request->input('user_type') ? $request->input('user_type') : null;
        if (!in_array($user_type, $valid_user_type)) {
            $this->errors['status'] = 1;
            $this->errors['message'] = 'Please provide proper user type to which you want to report.';
            return $this->sendResponse();
        }

        // user id
        $user_id = null !== $request->input('user_id') ? $request->input('user_id') : null; 
        $payload_url = null;
        if ("" == $user_id || is_null($user_id)) {
            $this->errors['status'] = 1;
            $this->errors['message'] = 'Please provide valid profile/company id to which you want to report.';
            return $this->sendResponse();
        } else {
            if (preg_match('/[^0-9]/', $user_id)) {
              $this->errors['status'] = 1;
              $this->errors['message'] = 'Please provide valid profile/company id to whom you want to report.';
              return $this->sendResponse();
            }  else {
                $user_id = (int)$user_id;
                if ($user_type == "profile") {
                    $checkProfileExist = \App\V2\Profile::where("id", $user_id)->exists();
                    if (!$checkProfileExist) {
                        $this->errors['status'] = 1;
                        $this->errors['message'] = 'Please provide valid profile id to whom you want to report.';
                        return $this->sendResponse();
                    }
                    $payload_url = env('APP_URL')."/profile/".$user_id;
                    $email_input["type"] = "Profile";
                } else {
                    $checkCompanyExist = \App\V2\Company::where("id", $user_id)->exists();
                    if (!$checkCompanyExist) {
                        $this->errors['status'] = 1;
                        $this->errors['message'] = 'Please provide valid company id to whom you want to report.';
                        return $this->sendResponse();
                    }
                    $payload_url = env('APP_URL')."/companies/".$user_id;
                    $email_input["type"] = "Company";
                }
            }
        }

        // report type id
        $report_type_id = null;
        $report_type_name = null;
        $report_id = null !== $request->input('report_type_id') ? $request->input('report_type_id') : null;
        if ("" == $report_id || is_null($report_id)) {
            $this->errors['status'] = 1;
            $this->errors['message'] = 'Please provide valid report type id.';
            return $this->sendResponse();
        } else if (preg_match('/[^0-9]/', $report_id)) {
          $this->errors['status'] = 1;
          $this->errors['message'] = 'Please provide valid report type id.';
          return $this->sendResponse();
        } else {
            $report_type_detail = ReportType::where('id',(int)$report_id)
                ->where('is_active', 1)
                ->get()
                ->first();
            if (is_null($report_type_detail)) {
                $this->errors['status'] = 1;
                $this->errors['message'] = 'Provided report type id is not associated with our system.';
                return $this->sendResponse();
            }
            $report_type_id = $report_type_detail->id;
            $report_type_name = $report_type_detail->name;
        }
        $email_input["issue"] = null != $report_type_name ? $report_type_name : "N/A";
        
        // report content
        $report_comment = null !== $request->input('report_comment') ? $request->input('report_comment') : null;

        $input = array(
            "report_type_id" => $report_type_id,  
            "report_type_name" => $report_type_name,
            "report_comment" => $report_comment, 
            "user_type" => $user_type,  
            "user_id" => $user_id,
            "profile_id" => $profile_id,
            "is_active" => true,
        );

        $report_exist = ReportUser::where("profile_id", $input['profile_id'])
            ->where("user_id", $input['user_id'])
            ->where("user_type", $input['user_type'])
            ->get()
            ->first();

        if (!is_null($report_exist)) {
            $this->model = $report_exist;
            $this->errors['message'] = "Already reported.";
        } else {
            $this->model = ReportUser::create($input);
            $this->errors['message'] = "Reported.";
            $email_input["url"] = $payload_url;
            $email_input["report_on"] = \Carbon\Carbon::now()->format('jS F Y \\a\\t h:i:s A');
            event(new \App\Events\ReportContentUserEvent(
                $email_input["type"],
                $email_input["profile_url"],
                $email_input["url"],
                $email_input["issue"],
                $email_input["report_on"],
                $email_input["reporter_name"],
                $email_input["email_id"],
                $email_input["phone_number"]
            ));
        }
        return $this->sendResponse();
    }
}
