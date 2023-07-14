<?php
namespace App\Services;

use App\UserLoginInfo;
use App\Profile;
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
        $userAgent = $request->header('User-Agent');

        // platform info
        if (strpos($userAgent, 'Android') !== false) 
        {
            $platform = "android";
        } 
        elseif (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false || strpos($userAgent, 'iPod')) 
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
            $token_invalidation = JWTAuth::invalidate($token, true); 
            if(!$token_invalidation)
            {
                return false;
            }
        }

        // remove tokens from user_login_info table which are invalidated.
        $tokens = $user_jwt_tokens->toArray();
        $tokens = Arr::flatten($tokens); //convert into a single array
        $this->userLoginInfo->removeMultiple($tokens);

        return true;
    }
}