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
    public function sendVerificationEmail($email, $source, $platform)
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

        $profile_id = $verifyEmail->profile->id;
       
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
                $mail = (new \App\Jobs\EmailOtpVerification($otpNo))->onQueue('otp_emails');
                \Log::info('Queueing Verified Email...');

                dispatch($mail);
            }

            $insertOtp = OTPMaster::create(["profile_id" => $profile_id, "otp" => $otpNo, "email" => $email, "source" => $source, "platform" => $platform ?? null, "expired_at" => date("Y-m-d H:i:s", strtotime("+5 minutes"))]);

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
}