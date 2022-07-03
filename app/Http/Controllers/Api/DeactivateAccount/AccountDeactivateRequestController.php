<?php
namespace App\Http\Controllers\Api\DeactivateAccount;

use App\DeactivateAccount\AccountDeactivateRequests as AccountDeactivateRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tagtaste\Api\SendsJsonResponse;
use Carbon\Carbon;


class AccountDeactivateRequestController extends Controller
{
    use SendsJsonResponse;

    protected $model;
    public function __construct(AccountDeactivateRequests $model){
        $this->model = $model;
    }

    public function create(Request $request, $account_mgmt_id){
        $profile_id = $request->user()->profile->id;
        $reason_id = $request->reason_id;
        $value = $request->value;

        if (empty($reason_id)) {
            return $this->sendError("Reason is mandatory.");
        }
        
        $data = AccountDeactivateRequests::insert(['profile_id' => $profile_id, 'reason_id' => $reason_id, 'account_management_id' => $account_mgmt_id]);

        if($data){
            return $this->sendResponse();
        }else{
            return $this->sendError(["status"=>false, "display_messgae"=>"Error occured"]);
        }
    }   

}

?>