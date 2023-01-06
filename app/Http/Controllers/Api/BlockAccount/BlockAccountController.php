<?php

namespace App\Http\Controllers\Api\BlockAccount;

use App\BlockAccount\BlockAccount;
use App\V2\CompanyUser;
use App\Profile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tagtaste\Api\SendsJsonResponse;
use Carbon\Carbon;
use App\Subscriber;
use Illuminate\Support\Facades\Redis;


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
            $smallProfile = \App\V2\Profile::find($profile_id);
            $success_msg = $smallProfile->name.' profile is unblocked';
            if($block_profile){
                //Unfollow user from here
                $this->unfollowProfile($request, $profile_id);
                $success_msg = 'Successfully blocked';
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
            $company = Company::find($company_id);
            $success_msg = $company->name.' profile is unblocked';

            $success_msg = 'Unblocked successfully.';
            if($block_company){
                //Unfollow company from here
                $this->unfollowCompany($request, $company_id);
                $success_msg = 'Successfully blocked';
            }
            return $this->sendNewResponse(['title'=>$success_msg, 'sub_title'=>'','description'=>'']);
        }else{
            return $this->sendNewError("Something went wrong. Please try again.");
        }
    }


    private function unfollowProfile(Request $request, $profile_id){
        $channel_owner_profile_id = $profile_id;
        $channel_owner = Profile::find($channel_owner_profile_id);
        if (!$channel_owner) {
            throw new ModelNotFoundException();
        }

        $this->model = $request->user()->completeProfile->unsubscribeNetworkOf($channel_owner);

        if (!$this->model) {
            $this->sendError("You are not following this profile.");
        }

        $profile_id = $request->user()->profile->id;

        // remove profiles the logged in user is following
        Redis::sRem("following:profile:" . $profile_id, $channel_owner_profile_id);

        // remove profiles that are following $channel_owner in redis
        Redis::sRem("followers:profile:" . $channel_owner_profile_id, $profile_id);

        $subscriber = new Subscriber();
        $subscriber->unfollowProfileSuggestion((int)$profile_id, (int)$channel_owner_profile_id);

        return $this->sendResponse();
    }

    private function unfollowCompany(Request $request, $company_id){
        $channel_owner = Company::find($company_id);
        if(!$channel_owner){
            throw new ModelNotFoundException("Company not found.");
        }

        $this->model = $request->user()->completeProfile->unsubscribeNetworkOf($channel_owner);
        if (!$this->model) {
            return $this->sendError("You are not following this company.");
        }

        $profile_id = $request->user()->profile->id;
        
        // remove companies the logged in user is following
        Redis::sRem("following:profile:".$profile_id, "company.$company_id");
    
        // remove profiles that are following $channelOwner
        Redis::sRem("followers:company:".$company_id, $profile_id);

        $subscriber = new Subscriber();
        $subscriber->unfollowCompanySuggestion((int)$profile_id, (int)$company_id);

        return $this->sendResponse();
    }
}

?>