<?php
namespace App\Services;

use App\UserLoginInfo;
use App\Profile;
use App\User;
use App\OTPMaster;
use Illuminate\Support\Arr;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;

class UserService
{
    private $userLoginInfo;

    /**
    * UserService class Constructor
    *
    * @param UserLoginInfo $UserLoginInfo
    */

    public function __construct(UserLoginInfo $userLoginInfo)
    {
        $this->userLoginInfo = $userLoginInfo;
    }

    /**
     * Store user's login information in user_login_info table
     * 
     * @param int $user_id
     * @param \Illuminate\Http\Request  $request
     * @param text $token
     * @return \Illuminate\Http\Response
    */
    public function storeUserLoginInfo($user_id, $request, $token)
    {
        $token_exists = $this->userLoginInfo->where('jwt_token',$token)->exists();
        if($token_exists == true)
        {
            return false;
        }

        $profile_id = Profile::where('user_id',$user_id)->first()->id;
        $versionKey = 'X-VERSION';
        $versionKeyIos = 'X-VERSION-IOS';

        // platform info
        if ($request->hasHeader($versionKey)) 
        {
            $platform = "android";
        } 
        else if ($request->hasHeader($versionKeyIos))
        {
            $platform = "ios";
        } 
        else 
        {
            $platform = "web";
        }
        
        $login_info = array('profile_id' => $profile_id, 'jwt_token' => $token, 'platform' => $platform);
        
        // Store data in the database
        if($token && $profile_id)
        {
            $this->userLoginInfo->store($login_info);
        }
    }

    /**
     * Store user's login information in user_login_info table
     * 
     * @param array $condition
     * @return \Illuminate\Http\Response
    */
    public function forceLogoutUser($condition)
    {
        // remove null values to check for the specific condition
        $condition = array_filter($condition, function($condition) {
            return !is_null($condition);
        });

        // If array is null that means no conditionds are there, so all tokens will be fetched here.
        if (!$condition) 
        {
            $user_jwt_tokens = $this->userLoginInfo->getSpecificLoginInfo(array('jwt_token'));
        }

        // Else, tokens based on conditions will be fetched here.
        $user_jwt_tokens = $this->userLoginInfo->getSpecificLoginInfoBasedOnCondition($condition, array('jwt_token'));
        
        foreach($user_jwt_tokens as $user_jwt_token)
        {
            // Create a Token instance from the token string
            $token = new Token($user_jwt_token['jwt_token']);

            // Forceful invalidation of token
            try
            {
                $token_invalidation = JWTAuth::invalidate($token, true);
            } 
            catch(\Exception $e)
            {
                $errors = $e->getMessage();
            }
        
        }

        // remove tokens from user_login_info table which are invalidated.
        $tokens = $user_jwt_tokens->toArray();
        $tokens = Arr::flatten($tokens); //convert into a single array
        $this->userLoginInfo->removeMultiple($tokens);

        return true;
    }

    /**
     * Send verifcation email
     * 
     * @param string $email
    */
    public function emailVerification($email, $source, $platform, $mailType = null)
    {
        $verifyEmail = User::where("email", $email)->whereNull('deleted_at')->where('account_deactivated', 0)->first();
       
        if(empty($verifyEmail))
        {
            $error = "We could not find any account associated with this email ID. Please Try Again!";
            return ["result" => false, "error" => $error];
        }
        else if (!empty($verifyEmail->verified_at))
        {
            $error = "This email is already verified. Please try with another email or contact tagtaste for any query.";
            return ["result" => false, "error" => $error];
        }

        return $this->sendEmailOtp($verifyEmail->profile->id, $email, $source, $platform, $verifyEmail->name, $mailType);
       
    }

    public function sendEmailOtp($profile_id, $email, $source, $platform, $username, $mailType, $password = null){
        $otpCheck = OTPMaster::where("profile_id", $profile_id)->where('email', "=", $email)
            ->where("created_at", ">", date("Y-m-d H:i:s", strtotime("-" . config("constant.OTP_LOGIN_TIMEOUT_MINUTES") . " minutes")))
            ->where("source", $source)->orderBy("id", "desc")
            ->where("deleted_at", null)
            ->first();

        if ($otpCheck == null) 
        {
            // check for server
            $environment = env('APP_ENV');
            if($environment == "test")
            {
                $otpNo = 123456;
            }
            else
            {
                //Send OTP     
                $otpNo = mt_rand(100000, 999999);
                $this->emailService($username, $email, $mailType, $otpNo);
            }
            
            $insertOtp = OTPMaster::create(["profile_id" => $profile_id, "otp" => $otpNo, "email" => $email, "password" => $password ?? null, "source" => $source, "platform" => $platform ?? null, "expired_at" => date("Y-m-d H:i:s", strtotime("+10 minutes"))]);

            if(!$insertOtp)
            {
                $error = "Something went wrong!";
                return ["result" => false, "error" => $error];
            }
        }
        else {
            $error = "OTP sent already. Please try again in 1 minute.";
            return ["result" => false, "error" => $error];
        }
        
        return ["result" => true, "error" => ""];
    }

    public function sendPhoneOtp($profile_id, $number, $source, $platform, $country_code){
        $otpCheck = OTPMaster::where("profile_id", $profile_id)->where('mobile', "=", $number)->where("created_at", ">", date("Y-m-d H:i:s", strtotime("-" . config("constant.OTP_LOGIN_TIMEOUT_MINUTES") . " minutes")))
        ->where("source", $source)->orderBy("id", "desc")
        ->where("deleted_at", null)
        ->first();

        if ($otpCheck == null) 
        {
            if (strlen($number) == 13) {
                $number = substr($number, 3);
            }

            // check for server
            $environment = env('APP_ENV');
            if($environment == "test")
            {
                $otpNo = 123456;
                $getResp = "test response";
            } else {
                $otpNo = mt_rand(100000, 999999);
                $text = "Use OTP ".$otpNo." to verify your TagTaste account. Please DO NOT share this OTP with anyone.";
                $getResp = $this->smsService($country_code, $number, $text);
            }

            $insertOtp = OTPMaster::create(["profile_id" => $profile_id, "otp" => $otpNo, "mobile" => $number, "source" => $source, "platform" => $platform ?? null, "expired_at" => date("Y-m-d H:i:s", strtotime("+5 minutes"))]);

            if(!$getResp || !$insertOtp)
            {
                return ["result" => false, "error" => "Something went wrong!"];
            }

        } else {
            return ["result" => false, "error" => "OTP sent already. Please try again in 1 minute."];
        }
        return ["result" => true, "error" => ""];
    }

    //when otp is sent to both via email & phone
    public function sendOtp($profile_id, $number, $country_code, $email, $source, $platform, $username, $mailType, $password){
        $otpCheck = OTPMaster::where("profile_id", $profile_id)->where('mobile', "=", $number)->where('email',"=",$email)->where("created_at", ">", date("Y-m-d H:i:s", strtotime("-" . config("constant.OTP_LOGIN_TIMEOUT_MINUTES") . " minutes")))
        ->where("source", $source)->orderBy("id", "desc")
        ->where("deleted_at", null)
        ->first();

        if ($otpCheck == null) 
        {
            // check for server
            $environment = env('APP_ENV');
            if($environment == "test")
            {
                $otpNo = 123456;
                $getResp = "test response";
            }
            else
            {
                //Send OTP     
                $otpNo = mt_rand(100000, 999999);
                $this->emailService($username, $email, $mailType,$otpNo); //via email
                $text = "Use OTP ".$otpNo." to verify your TagTaste account. Please DO NOT share this OTP with anyone.";
                $getResp = $this->smsService($text, $country_code, $number); //via sms
            }
            
            $insertOtp = OTPMaster::create(["profile_id" => $profile_id, "otp" => $otpNo, "mobile" => $number,"email" => $email, "password" => $password, "source" => $source, "platform" => $platform ?? null, "expired_at" => date("Y-m-d H:i:s", strtotime("+10 minutes"))]);

            if(!$getResp || !$insertOtp)
            {
                $error = "Something went wrong!";
                return ["result" => false, "error" => $error];
            }
        } else {
            return ["result" => false, "error" => "OTP sent already. Please try again in 1 minute."];
        }
        return ["result" => true, "error" => ""];
    }

    public function verifyOtp($source, $otp, $phone = null, $email = null){
        if(!empty($phone) && !empty($email)){
            $otpVerification = OTPMaster::where('mobile', $phone)
                ->where('email', $email)
                ->where("source",$source)
                ->whereNull("deleted_at")
                ->orderBy("id", "desc")
                ->first();
            $otpMasterObj = OTPMaster::where('mobile', $phone)->where('email', $email)->where("source",$source);
        } else if(isset($phone) && !empty($phone)){
            $otpVerification = OTPMaster::where('mobile', $phone)
                ->where("source",$source)
                ->whereNull("deleted_at")
                ->orderBy("id", "desc")
                ->first();
            $otpMasterObj = OTPMaster::where('mobile', $phone)->where("source",$source);
        } else if(isset($email) && !empty($email)){
            $otpVerification = OTPMaster::where('email', $email)
                ->where("source",$source)
                ->whereNull("deleted_at")
                ->orderBy("id", "desc")
                ->first();
            $otpMasterObj = OTPMaster::where('email', $email)->where("source",$source);
        }

        if(empty($otpVerification))
        {
            return ["result" => false, "error" => "Something went wrong! Please regenerate OTP or try other methods."];
        }

        //check for otp attempts
        if (isset($otpVerification) && $otpVerification->attempts >= config("constant.OTP_LOGIN_VERIFY_MAX_ATTEMPT")) {
            $otpMasterObj->update(["deleted_at" => date("Y-m-d H:i:s")]);
            return ["result" => false, "error" => "OTP attempts exhausted. Please regenerate OTP or try other methods."];
        }
        
        if ($otpVerification && $otpVerification->otp == $otp) {
            //check for otp expiration 
            if($otpVerification->expired_at < date("Y-m-d H:i:s"))
            {
                return ["result" => false, "error" => "OTP has expired. Please try again!"];
            }

            //Update attempts
            $otpVerification->update(["attempts" => $otpVerification->attempts + 1]);
            $password = $otpVerification->password;

            //delete all records associated with this phone no or email
            $otpMasterObj->update(["deleted_at" => date("Y-m-d H:i:s")]);

            //check whether password is there or not and send password for password update
            if(!empty($password)){
                return ["result" => true, "error" => "", "password" => $password];
            } 
        } else {
            $otpVerification->update(["attempts" => $otpVerification->attempts + 1]);
            return ["result" => false, "error" => "Incorrect OTP entered. Please try again."];
        }
        return ["result" => true, "error" => ""];
    }

    public function emailService($username, $email, $mailType, $otpNo){
        switch ($mailType) {
            case 'signup':
                $mailDetails = ["username" => $username, "email" => $email, "otp" => $otpNo];
                $mail = new \App\Jobs\SignupEmailOtpVerification($mailDetails);
                break;
            case 'create_password':
                $mailDetails = ["username" => $username, "email" => $email, "otp" => $otpNo, "mail" => $mailType];
                $mail = new \App\Jobs\NewPasswordEmailVerification($mailDetails);
                break;
            case 'change_password':
                $mailDetails = ["username" => $username, "email" => $email, "otp" => $otpNo, "mail" => $mailType];
                $mail = new \App\Jobs\NewPasswordEmailVerification($mailDetails);
                break;
            case 'forgot_password':
                $mailDetails = ["username" => $username, "email" => $email, "otp" => $otpNo, "mail" => $mailType];
                $mail = new \App\Jobs\NewPasswordEmailVerification($mailDetails);
                break;
            default:
            $mailDetails = ["username" => $username, "email" => $email, "otp" => $otpNo];
                $mail = new \App\Jobs\EmailOtpVerification($mailDetails); // email verification on profile page
                break;
        };

        \Log::info('Queueing email for otp verification...');
        dispatch($mail);
    }

    public function smsService($country_code, $number, $text){
        $service = "twilio";
        return SMS::sendSMS($country_code . $number, $text, $service);
    }
}