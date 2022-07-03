<?php namespace App\Http\Controllers\Api\DeactivateAccount;

use App\DeactivateAccount\AccountManagementOptions as AccountManagementOptions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tagtaste\Api\SendsJsonResponse;
use Carbon\Carbon;


class AccountManagementOptionController extends Controller
{
    use SendsJsonResponse;

    protected $model;
    public function __construct(AccountManagementOptions $model){
        $this->model = $model;
    }

    public function index(Request $request){
        
        $data = AccountManagementOptions::whereNull('deleted_at')->get();
        $this->model = $data;
        return $this->sendResponse();
    }   
}
?>