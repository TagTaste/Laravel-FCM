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
            return $this->sendError(["display_message"=>"Reason is mandatory.", "status"=>false]);
        }
        
        $data = AccountDeactivateRequests::insert(['profile_id' => $profile_id, 'reason_id' => $reason_id, 'account_management_id' => $account_mgmt_id, 'value' => $value, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()]);
        
        if($data){
            return $this->sendResponse(['title'=>'Your account is deactivated as per your request. Your account will be hidden from the TagTaste community. You will not receive any notification or update until you log in with the same email.', 'sub_title'=>'','description'=>'']);
        }else{
            return $this->sendError(["display_message"=>"Something went wrong. Please try again.", "status"=>false]);
        }
    }   
}

?>