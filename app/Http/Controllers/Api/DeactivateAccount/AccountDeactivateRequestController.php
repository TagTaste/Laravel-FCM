<?php
namespace App\Http\Controllers\Api\DeactivateAccount;

use App\Company;
use App\DeactivateAccount\AccountDeactivateRequests as AccountDeactivateRequests;
use App\DeactivateAccount\AccountManagementOptions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\AccountDeactivateChanges;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tagtaste\Api\SendsJsonResponse;
use Carbon\Carbon;
use App\OTPMaster;
use App\Profile;
use App\Profile\User;
use App\Services\SMS;
use App\User as AppUser;
use Illuminate\Support\Facades\Redis;

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
        $user = $request->user();
        $account_mgmt_details = AccountManagementOptions::where('id',$account_mgmt_id)->first();

        if (empty($reason_id) || empty($account_mgmt_details)) {
            return $this->sendNewError("Reason is mandatory.");
        }
        
        $check_user = AccountDeactivateRequests::where('profile_id',$profile_id)->whereNull('deleted_at')->first();
        if(!empty($check_user)){
            return $this->sendNewError("We already have your request.");
        }

        $company_list = Company::where('user_id',$request->user()->id)->whereNull('deleted_at')->get();
        if (count($company_list) > 0){
            return $this->sendNewError("You are still the superadmin of a company. Please transfer your ownership or delete the company.");
        }

        $user_detail = ["name"=>$user->name, "email"=>$user->email, "gender"=>$user->profile->gender, "dob"=>$user->profile->dob, "phone"=>$user->profile->phone];
        
        $user_detail = json_encode($user_detail);
        $insert_data = ['profile_id' => $profile_id, 'reason_id' => $reason_id, 'user_detail'=> $user_detail ,'account_management_id' => $account_mgmt_id, 'value' => $value, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()];
        if($account_mgmt_details['slug'] == 'delete'){
            $deleted_date = Carbon::now()->startOfDay();
            $deleted_date->addDays(15);
            $insert_data['deleted_on'] = $deleted_date;
        }
        $data = AccountDeactivateRequests::insert($insert_data);
        
        if($data){
            //deactivate user
            $user = User::findOrFail($user->id);
            $user->account_deactivated = true;
            $user->save();
            
            Redis::lpush('deactivated_users',$user->id); 
            
            //send a deactivate changes in queue
            $deactivate_changes = (new AccountDeactivateChanges($profile_id, true));
            dispatch($deactivate_changes);
            return $this->sendNewResponse(['title'=>'Your account is deactivated as per your request. Your account will be hidden from the TagTaste community. You will not receive any notification or update until you log in with the same email.', 'sub_title'=>'','description'=>'']);
        }else{
            return $this->sendNewError("Something went wrong. Please try again.");
        }
    }   

    public function send_otp(Request $request){
        $source = config("constant.LOGIN_OTP_SOURCE");
        $profile_id = $request->user()->profile->id;        
        //verifyIfOtpAlreadySent 
        $check = OTPMaster::where("profile_id", $profile_id)
            ->where("created_at", ">", date("Y-m-d H:i:s", strtotime("-" . config("constant.OTP_LOGIN_TIMEOUT_MINUTES") . " minutes")))
            ->where("expired_at", '>', date("Y-m-d H:i:s"))
            ->where("source", $source)->orderBy("id", "desc")
            ->where("deleted_at", null)
            ->first();
        
        if ($check == null) {
            //Send OTP     
            $otpNo = mt_rand(100000, 999999);
            $text =  "Use OTP ".$otpNo." to verify your TagTaste account.Please DO NOT share OTP with anyone.";
            $country_code = $request->user()->profile->country_code;
            $phone = $request->user()->profile->phone;

            if($country_code == '+91' || $country_code == '91'){
                if(!empty($phone)){
                    $service = 'gupshup';
                    $getResp = SMS::sendSMS($country_code . $phone, $text, $service);    
                }
            }else if(!empty($phone) && !empty($country_code)){
                $service = "twilio";
                $getResp = SMS::sendSMS($country_code . $phone, $text, $service);
            }
            
            //send otp on mail
            $email = $request->user()->email;
            
            $user = \App\Profile\User::where('id', $request->user()->id)->whereNull('deleted_at')->first();
            if ($user) {  
                \Mail::raw($text, function ($message) use($user) {
                    $message->to($user->email, $user->name);
                    $message->subject('OTP Verification');                
                });
                
                // \Mail::send('emails.verify-mail', $data, function($message)
                // {
                //     $message->to($this->user->email, $this->user->name)->subject('Verify your email');
                // });

                // $mail = (new \App\Jobs\EmailVerification($user))->onQueue('emails');
                // \Log::info('Queueing OTP Email...');    
                // dispatch($mail);
            }

            $insert = OTPMaster::create(["profile_id" => $profile_id, "otp" => $otpNo, "mobile" => $phone, "service" => $service, "source" => $source, "platform" => $request->profile["platform"] ?? null, "expired_at" => date("Y-m-d H:i:s", strtotime("+5 minutes"))]);
            if ($insert) {
                $this->model = true;
                return $this->sendNewResponse();
            }
        } else {
            return $this->sendNewError('OTP sent already. Please try again in 1 minute.');
        }
        return $this->sendNewError('Something went wrong. Please try again.');
    }

    public function verify_otp(Request $request){
        $source = config("constant.LOGIN_OTP_SOURCE");
        $profile_id = $request->user()->profile->id;

        $otp = OTPMaster::where('profile_id', "=", $profile_id)
            ->where("expired_at", '>', date("Y-m-d H:i:s"))
            ->where("source", $source)
            ->orderBy("id", "desc")
            ->where("deleted_at", null)->first();
        
        if(empty($otp)){
            return $this->sendNewError('Please generate new OTP. Existing OTP might expired.');
        }

        if ($otp && $otp->attempts > config("constant.OTP_LOGIN_VERIFY_MAX_ATTEMPT")) {
            $otp->update(["deleted_at" => date("Y-m-d H:i:s")]);
            return $this->sendNewError('OTP attempts exhausted. Please regenerate OTP.');
        }

        if ($otp) {
            $otp->update(["attempts" => $otp->attempts + 1]);
        }
        
        if ($otp && $otp->otp==$request->otp) {
            $otp->update(["deleted_at" => date("Y-m-d H:i:s")]);
            $this->model = true;
            return $this->sendNewResponse();
        }
        $this->model = false;
        return $this->sendNewError('Incorrect OTP entered. Please try again.');
    }
}

?>