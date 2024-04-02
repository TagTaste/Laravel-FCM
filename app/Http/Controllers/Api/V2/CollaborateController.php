<?php

namespace App\Http\Controllers\Api\V2;

use App\v1\Chat;
use App\V2\Detailed\Collaborate;
use App\CompanyUser;
use App\Events\Actions\Like;
use App\PeopleLike;
use App\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Api\CollaborateController as BaseController;
use Illuminate\Support\Facades\DB;

class CollaborateController extends BaseController
{
   /**
     * Variable to model
     *
     * @var collaborate
     */
    protected $model;

    /**
     * Create instance of controller with Model
     *
     * @return void
     */
    public function __construct(Collaborate $model)
    {
        $this->model = $model;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $collaboration = $this->model->where('id',$id)->where('state','!=',Collaborate::$state[1])->where('account_deactivated',0)->first();

        if ($collaboration === null) {
            return $this->sendError("Invalid Collaboration Project.");
        }

        $collaboration->videos_meta = json_decode($collaboration->videos_meta);

        $profileId = $request->user()->profile->id;
        if ($collaboration->state == 'Active' || $collaboration->state == 'Close' || $collaboration->state == 'Expired') {
            $meta = $collaboration->getMetaForV2($profileId);
            $collaboration_detail = $collaboration->toArray();
            $collaboration_detail["view_graph"] = $this->getViewGraph($id);
            $collaboration_detail["download_report"] = $this->getDownloadReport($id);
            $seoTags = $collaboration->getSeoTags($profileId);
            $this->model = ['collaboration'=>$collaboration_detail,'meta'=>$meta,'seoTags'=>$seoTags];
            return $this->sendResponse();
        }

        if ($collaboration->company_id != null) {
            $checkUser = CompanyUser::where('company_id',$collaboration->company_id)->where('profile_id',$profileId)->exists();
            if(!$checkUser){
                return $this->sendError("Invalid Collaboration Project.");
            }
        } else if($collaboration->profile_id != $profileId) {
            return $this->sendError("Invalid Collaboration Project.");
        }


        $meta = $collaboration->getMetaForV2($profileId);
        $seoTags = $collaboration->getSeoTags();
        $this->model = [
            'collaboration'=>$collaboration, 
            'meta'=>$meta,
            'seoTags'=>$seoTags
        ];
        return $this->sendResponse();
        
    }

    public function getDownloadReport($id)
    {
        $downloadReport = DB::table('collaborate_reports')->where("collaborate_id", $id)->whereNull("deleted_at")->first();
        
        if (!empty($downloadReport)) {
            return true;
        }
        return false;
    }

    public function getViewGraph($id)
    {
        $headerList = DB::Table("collaborate_tasting_header")->where("collaborate_id", $id)->where('is_active', 1)->whereIn("header_selection_type", [config("constant.COLLABORATE_HEADER_SELECTION_TYPE.NORMAL"), config("constant.COLLABORATE_HEADER_SELECTION_TYPE.PRODUCT_EXPERIENCE")])->get();
        $graphActive = false;
        foreach ($headerList as $headerValue) {
            $getQuestions = DB::table("collaborate_tasting_questions")->where('header_type_id', $headerValue->id)->where("collaborate_id", $id)->where('is_active', 1)->get();
            
            foreach ($getQuestions as $questionList) {
                $decodeJsonOfQuestions = json_decode($questionList->questions, true);

                if (json_last_error() == JSON_ERROR_NONE && isset($decodeJsonOfQuestions["create_graph"]) && $decodeJsonOfQuestions["create_graph"] == true) {
                    $graphActive = true;
                    break;
                }
                if ($graphActive) {
                    break;
                }
            }
        }
        return $graphActive;
    }

    public function getChatGroups(Request $request, $id){
        $loggedInProfileId =  $request->user()->profile->id;
        $chatGroups = Chat::where('model_id', $id)
        ->where('model_name',config("constant.CHAT_MODEL_SUPPORT.COLLABORATE"))
        ->where('chat_type',0)
        ->orderByRaw('ISNULL(batch_id), batch_id')
        ->orderBy('created_at', 'ASC')
        ->get();
        
        foreach ($chatGroups as $group) {
            $group->makeHidden(['latestMessages']);
        }

        $this->model = $chatGroups;
        return $this->sendNewResponse();
    }
}