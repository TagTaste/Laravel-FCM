<?php

namespace App\Http\Controllers\Api\BlockAccount;

use App\BlockAccount\BlockAccount;
use App\V2\CompanyUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tagtaste\Api\SendsJsonResponse;
use Carbon\Carbon;


class BlockAccountController  extends Controller
{
    use SendsJsonResponse;
    
    protected $model;
    public function __construct(BlockAccount $model){
        $this->model = $model;
    }
    
    public function blockProfile(Request $request, $profile_id){
        $req_profile_id = $request->user()->profile->id;
        $block_profile = $request->block_profile ?? true;

        if($block_profile){
            $already_blocked = BlockAccount::where('profile_id', $req_profile_id)
            ->where('blocked_profile_id', $profile_id)
            ->whereNull('deleted_at')->exists();

            if($already_blocked){
                return $this->sendNewError("You have already blocked this profile. Please refresh your screens.");
            }

            $data = ['profile_id'=>$req_profile_id, 'blocked_profile_id'=>$profile_id,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()];
            $data = BlockAccount::insert($data);
        }else{
            $data = BlockAccount::where('profile_id', $req_profile_id)
            ->where('blocked_profile_id', $profile_id)
            ->whereNull('deleted_at')->update(['deleted_at'=>Carbon::now()]);
        }

        if($data){
            $success_msg = 'Unblocked successfully.';
            if($block_profile){
                //Unfollow user from here
                $success_msg = 'Blocked successfully.';
            }
            return $this->sendNewResponse(['title'=>$success_msg, 'sub_title'=>'','description'=>'']);
        }else{
            return $this->sendNewError("Something went wrong. Please try again.");
        }
    }

    public function blockCompany(Request $request, $company_id){
        $req_profile_id = $request->user()->profile->id;
        $block_company = $request->block_company ?? true;

        if($block_company){
            $already_blocked = BlockAccount::where('profile_id', $req_profile_id)
            ->where('blocked_company_id', $company_id)
            ->whereNull('deleted_at')->exists();

            if($already_blocked){
                return $this->sendNewError("You have already blocked this company. Please refresh your screens.");
            }

            //check if user is the admin on company
            $is_company_admin = CompanyUser::where('company_id',$company_id)->where('profile_id',$req_profile_id)->first();

            if(!empty($is_company_admin)){
                return $this->sendNewError('You are the company admin. You are not allowed block your own company.');
            }

            $data = ['profile_id'=>$req_profile_id, 'blocked_company_id'=>$company_id,'created_at'=>Carbon::now(),'updated_at'=>Carbon::now()];
            $data = BlockAccount::insert($data);
        }else{
            $data = BlockAccount::where('profile_id', $req_profile_id)
            ->where('blocked_company_id', $company_id)
            ->whereNull('deleted_at')->update(['deleted_at'=>Carbon::now()]);
        }
        
        if($data){
            $success_msg = 'Unblocked successfully.';
            if($block_company){
                //Unfollow company from here
                $success_msg = 'Blocked successfully.';
            }
            return $this->sendNewResponse(['title'=>$success_msg, 'sub_title'=>'','description'=>'']);
        }else{
            return $this->sendNewError("Something went wrong. Please try again.");
        }
    }
    
}

?>