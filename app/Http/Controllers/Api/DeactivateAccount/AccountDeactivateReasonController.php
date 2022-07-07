<?php

namespace App\Http\Controllers\Api\DeactivateAccount;

use App\DeactivateAccount\AccountDeactivateReasons as AccountManagementReasons;
use App\DeactivateAccount\AccountDeactivateReasons;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tagtaste\Api\SendsJsonResponse;
use Carbon\Carbon;


class AccountDeactivateReasonController extends Controller
{
    use SendsJsonResponse;

    protected $model;
    public function __construct(AccountDeactivateReasons $model){
        $this->model = $model;
    }
    
    public function index(Request $request){
        
        $data = AccountDeactivateReasons::whereNull('deleted_at')->get();
        $this->model = $data;
        return $this->sendNewResponse();
    }

}

?>