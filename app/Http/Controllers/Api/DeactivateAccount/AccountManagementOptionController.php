<?php namespace App\Http\Controllers\Api\DeactivateAccount;

use App\Collaborate;
use App\Company;
use App\DeactivateAccount\AccountManagementOptions as AccountManagementOptions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Payment\PaymentLinks;
use App\Polling;
use App\Surveys;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tagtaste\Api\SendsJsonResponse;
use Carbon\Carbon;
use PHPUnit\Util\Json;
use App\CompanyUser;


use function Clue\StreamFilter\append;

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
        return $this->sendNewResponse();
    }   

    public function get_user_activity(Request $request, $account_mgmt_id){
        
        $data = [];

        $account_mgmt_details = AccountManagementOptions::where('id',$account_mgmt_id)->first();
        if (empty($account_mgmt_details)) {
            return $this->sendNewError("Reason is mandatory.");
        }
        $description = 'Deactivating your account will disable your profile and your profile details like your name, photos, comments, etc will no longer be visible on the platform.';
        if($account_mgmt_details['slug'] == 'delete'){
            $description = 'Deleting your account will delete your profile and your profile details like your name, photos, comments, etc will no longer be visible on the platform.';
        }
        
        $overvire_obj = ['title'=>$request->user()->profile->name.', we’re sorry to see you go…', 'description'=>$description, 'ui_type'=>'card'];

        //get passbook activity
        $overvire_obj['elements'] = $this->get_user_passbook_details($request->user()->profile->id);
        $data[] = $overvire_obj;


        //get users post
        $user_posts = $this->get_user_posts($request->user()->profile->id);
        if(count($user_posts) > 0){
            $post_obj = ['title'=>'Ongoing Activities', 'sub_title'=>'Following ongoing activities will be closed once you deactivate or delete your account.', 'ui_type'=>'list','elements'=>$user_posts];
            $data[] = $post_obj;
        }
        
        //get company activity
        $user_companies = $this->get_user_companies($request->user()->id);
        if(count($user_companies) > 0){
            $user_company_obj = ['title'=>'TRANSFER COMPANY ACCESS', 'sub_title'=>'You’re either the super admin or the creator of the following companies. Please either transfer the super admin access or delete the company from companies setting page accessible via web.', 'ui_type'=> 'company_action', 'elements'=>$user_companies];
            $data[] = $user_company_obj;
        }
        
        return $this->sendNewResponse($data);
    }

    function get_user_passbook_details($profile_id){
        $data = [];
        $pending_balance = PaymentLinks::where('profile_id',$profile_id)
                            ->where('status_id', config('constant.PAYMENT_PENDING_STATUS_ID'))
                            ->whereNull('deleted_at')->sum('amount');

        if($pending_balance > 0){
            $passbook_obj = ['title'=>'Pending Balance', 'sub_title'=>'A pending balance indicates the portion of your earnings that has not been redeemed yet.','model_name'=>'passbook','description'=>'Amount to be redeemed','amount'=>'₹'.$pending_balance];
            $data[] = $passbook_obj;
            return $data;
        }else{
            return $data;
        }
    }

    function get_user_posts($profile_id){
        $data = [];
        //polls
        $poll_list = Polling::where('profile_id',$profile_id)->where('is_expired',0)->whereNull('deleted_at')->get();
        foreach($poll_list as $poll){
            $poll_obj = ['model_id'=>"$poll->id", 'model_name'=>'polling', 'title'=>$poll->title, 'sub_title'=>''];
            $data[] = $poll_obj;
        }

        //surveys
        $survey_list = Surveys::where('profile_id',$profile_id)->where('state', config('constant.SURVEY_STATES.PUBLISHED'))->whereNull('deleted_at')->get();
        foreach($survey_list as $survey){
            $survey_obj = ['model_id'=>"$survey->id", 'model_name'=>'surveys', 'title'=>$survey->title, 'sub_title'=>$survey->description];
            $data[] = $survey_obj;
        }

        //collaboration
        $collaborate_list = Collaborate::where('profile_id',$profile_id)->where('state', 1)->where('collaborate_type','collaborate')->whereNull('deleted_at')->get();
        foreach($collaborate_list as $collaborate){
            $collaborate_obj = ['model_id'=>"$collaborate->id", 'model_name'=>'collaborate', 'title'=>$collaborate->title, 'sub_title'=>$collaborate->description];
            $data[] = $collaborate_obj;
        }
        
        //private review
        $pr_list = Collaborate::where('profile_id',$profile_id)->where('state', 1)->where('collaborate_type','product-review')->whereNull('deleted_at')->get();
        foreach($pr_list as $private_collab){
            $private_collab_obj = ['model_id'=>"$private_collab->id", 'model_name'=>'product-review', 'title'=>$private_collab->title, 'sub_title'=>$private_collab->description];
            $data[] = $private_collab_obj;
        }
        
        return $data;
    }

    function get_user_companies($user_id){
        $data = [];
        
        $company_list = Company::where('user_id',$user_id)->whereNull('deleted_at')->get();
        
        foreach($company_list as $company){
            $admins = CompanyUser::getCompanyAdminIds($company->id);
            if(count($admins) > 1){
                $company_obj = ['model_id'=>"$company->id", 'model_name'=>'company','name'=>$company->name, 'sub_title'=>count($admins).' admins','image_meta'=>json_decode($company->logo_meta, true)];
            }else{
                $company_obj = ['model_id'=>"$company->id", 'model_name'=>'company','name'=>$company->name, 'sub_title'=>'You’re sole admin of the company','image_meta'=>json_decode($company->logo_meta, true)];
            }
            $data[] = $company_obj;
        }

        return $data;
    }
    
}
?>