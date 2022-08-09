<?php

namespace App\Http\Controllers\Auth;

use App\Events\Actions\JoinFriend;
use App\Exceptions\Auth\SocialAccountUserNotFound;
use App\Http\Controllers\Api\Controller;
use App\OTPMaster;
use App\Profile;
use App\Profile\User;
use App\Services\SMS;
use App\User as AppUser;
use Carbon\Carbon;

use function GuzzleHttp\uri_template;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Laravel\Socialite\Facades\Socialite;
use Tagtaste\Api\SendsJsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Jobs\AccountDeactivateChanges;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    use  SendsJsonResponse;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $newRegistered = false;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest', ['except' => 'logout']);
        if ($request->token) {
            $request->merge(['code' => $request->token]);
        }
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * validates login.
     *
     * @param Request $value login form data
     *
     * @return [type] [description]
     */
    public function doLogin(Request $request)
    {
        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password'], 'is_active' => "1"])) {
            //Session::put('admin', User::find(1));
            return redirect($this->getRedirectPath());
        } else {
            return redirect('/login');
        }
    }

    /**
     * [getRedirectPath returns redirect path according to user role.
     *
     * @return [string] redirectPath
     */
    public function getRedirectPath()
    {
        if (Auth::user()->hasRole('admin')) {
            return route("admin.dashboard");
        }

        return route('home');
    }

    /**
     * Redirect the user to the social site authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from social site.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        $result = ['status' => 'success', 'newRegistered' => $this->newRegistered];
        $input = $request->all();
        $authUser = $this->findOrCreateUser($input, $provider);
        if (!$authUser) {
            return ['status' => 'failed', 'errors' => "Could not login.", 'result' => [], 'newRegistered' => false];
        }
        
        $token = \JWTAuth::fromUser($authUser);
        unset($authUser['profile']);
        $result['result'] = ['user' => $authUser, 'token' => $token];
        $result['newRegistered'] = $this->newRegistered;
        $this->checkForDeactivationViaSocial($authUser);
        return response()->json($result);
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $socialLiteUser
     * @param $key
     * @return User
     */
    private function findOrCreateUser($socialiteUser, $provider)
    {
        try {
            $this->newRegistered = false;
            $socialiteUserLink = isset($socialiteUser['user']['link']) ? $socialiteUser['user']['link'] : (isset($socialiteUser['user']['publicProfileUrl']) ? $socialiteUser['user']['publicProfileUrl'] : null);
            $user = \App\Profile\User::findSocialAccount($provider, $socialiteUser['id'], $socialiteUser, $socialiteUserLink);
        } catch (SocialAccountUserNotFound $e) {
            //check if user exists,
            //then add social login
            if ($socialiteUser['email']) {
                $user = User::where('email', 'like', $socialiteUser['email'])->first();
            } else {
                return null;
            }
            if ($user) {
                //create social account;
                $this->newRegistered = false;
                $userInfo = isset($socialiteUser['user']) ? $socialiteUser['user'] : null;
                $user->createSocialAccount($provider, $socialiteUser['id'], $socialiteUser['avatar_original'], $socialiteUser['token'], $socialiteUserLink, false, $userInfo);
            } else {

                $this->newRegistered = true;
                $userInfo = isset($socialiteUser['user']) ? $socialiteUser['user'] : null;
                $user = \App\Profile\User::addFoodie(
                    $socialiteUser['name'],
                    $socialiteUser['email'],
                    null,
                    true,
                    $provider,
                    $socialiteUser['id'],
                    $socialiteUser['avatar_original'],
                    false,
                    $socialiteUser['token'],
                    $socialiteUserLink,
                    $userInfo
                );
                $companies = \App\Company::whereIn('id', [111, 137, 322])->get();
                foreach ($companies as $company) {
                    $model = $user->completeProfile->subscribeNetworkOf($company);
                    if ($model) {
                        //companies the logged in user is following
                        \Redis::sAdd("following:profile:" . $user->profile->id, "company.$company->id");

                        //profiles that are following $channelOwner
                        \Redis::sAdd("followers:company:" . $company->id, $user->profile->id);
                    }
                }
            }
        }
        return $user;
    }
    
    public function loginLinkedin(Request $request)
    {
        $code = $request->input('code');
        $redirect_uri = $request->input('redirect_uri');
        $client = new \GuzzleHttp\Client();
        $params['headers'] = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $client_id = config("constant.LINKEDIN_CLIENTID");
        $client_secret = config("constant.LINKEDIN_SECRET");
        $link = 'https://www.linkedin.com/oauth/v2/accessToken?grant_type=authorization_code&code=' . $code . '&redirect_uri=' . $redirect_uri . '&client_id=' . $client_id . '&client_secret=' . $client_secret;
        $res = $client->request('POST', $link, [$params]);
        $response = $res->getBody()->getContents();
        $response = json_decode($response);
        $accessToken = $response->access_token;
        $linkedInData = "https://api.linkedin.com/v2/me?projection=(id,firstName,lastName,profilePicture(displayImage~:playableStreams))";
        $bearerToken = "Bearer " . $accessToken;
        $linkedInParam["headers"] = ["Authorization" => $bearerToken];
        $linkedInRes = $client->request('GET', $linkedInData, $linkedInParam);
        $linkedInResponse = $linkedInRes->getBody()->getContents();
        $linkedInEmailData = "https://api.linkedin.com/v2/emailAddress?q=members&projection=(elements*(handle~))";
        $bearerToken = "Bearer " . $accessToken;
        $linkedInEmailParam["headers"] = ["Authorization" => $bearerToken];
        $linkedInEmailRes = $client->request('GET', $linkedInEmailData, $linkedInEmailParam);
        $linkedInEmailResponse = $linkedInEmailRes->getBody()->getContents();
        $data = [];
        $linkedInResponse = json_decode($linkedInResponse);
        $data['email'] = json_decode($linkedInEmailResponse)->elements[0]->{'handle~'}->emailAddress;
        $data['id'] = $linkedInResponse->id;
        $data['name'] = $linkedInResponse->firstName->localized->en_US . ' ' . $linkedInResponse->lastName->localized->en_US;
        $data['avatar_original'] = $linkedInResponse->profilePicture->{'displayImage~'}->elements[0]->identifiers[0]->identifier;
        $data['token'] = $accessToken;
        return $data;
    }

    public function loginViaOTP(Request $request)
    {
        $source = config("constant.LOGIN_OTP_SOURCE");
        $verifyNumber = Profile::where("phone", $request->profile["mobile"])->where("country_code", "LIKE",'%'.trim(str_replace("+", "", $request->profile["country_code"])))->where("verified_phone","=",1)->get();

        if ($verifyNumber->count() == 0) {
            return $this->sendError('We could not find any account associated with this phone number. Try other login methods.');
        }

        $id = $verifyNumber->first();

        if ($id->verified_phone != 1) {
            return $this->sendError('We could not find any account associated with this phone number. Try other login methods.');
        }
        //verifyIfOtpAlreadySent 

        $check = OTPMaster::where("profile_id", $id->id)->where('mobile', "=", $request->profile["mobile"])
            ->where("created_at", ">", date("Y-m-d H:i:s", strtotime("-" . config("constant.OTP_LOGIN_TIMEOUT_MINUTES") . " minutes")))
            ->where("expired_at", '>', date("Y-m-d H:i:s"))
            ->where("source", $source)->orderBy("id", "desc")
            ->where("deleted_at", null)
            ->first();

        if ($check == null) {
            //Send OTP     
            $otpNo = mt_rand(100000, 999999);
            // $text =   $otpNo . " is your OTP to verify your number with TagTaste.";
            $text =  "Use OTP ".$otpNo." to login to your TagTaste account. DO NOT share OTP with anyone.";

            if ($request->profile["country_code"] == "+91" || $request->profile["country_code"] == "91") {
                $service = "gupshup";
                $getResp = SMS::sendSMS($request->profile["country_code"] . $request->profile["mobile"], $text, $service);
            } else {
                $service = "twilio";
                $getResp = SMS::sendSMS($request->profile["country_code"] . $request->profile["mobile"], $text, $service);
            }

            $insert = OTPMaster::create(["profile_id" => $id->id, "otp" => $otpNo, "mobile" => $request->profile["mobile"], "service" => $service, "source" => $source, "platform" => $request->profile["platform"] ?? null, "expired_at" => date("Y-m-d H:i:s", strtotime("+5 minutes"))]);
            if ($getResp && $insert) {
                $this->model = true;
                return $this->sendResponse();
            }
        } else {
            return $this->sendError("OTP sent already. Please try again in 1 minute.");
        }
        return $this->sendError("Something went wrong. Please try again.");
    }

    // public function resendOTP(Request $request)
    // {
    //     $verifyNumber = Profile::where("phone", $request->profile["mobile"])->where("country_code", trim(str_replace("+", "", $request->profile["country_code"])))->get();

    //     if ($verifyNumber->count() == 0) {
    //         return $this->sendError('The number is not registered.');
    //     }

    //     $id = $verifyNumber->first();

    //     $check = OTPMaster::where("profile_id", $id->id)->where('mobile', "=", $request->profile["mobile"])->where("created_at", ">", Carbon::now()->subMinutes(config("constant.OTP_LOGIN_TIMEOUT_MINUTES")))->where("expired_at", null)->orderBy("id", "desc")->first();

    //     if ($check) {
    //         $id = $verifyNumber->first();
    //         $otpNo = mt_rand(100000, 999999);
    //         $text =  "Use OTP " . $otpNo . " to login to your TagTaste account. DO NOT share OTP with anyone.";
    //         if ($request->profile["country_code"] == "+91" || $request->profile["country_code"] == "91") {
    //             $service = "gupshup";
    //             $getResp = SMS::sendSMS($request->profile["country_code"] . $request->profile["mobile"], $text, $service);
    //         } else {
    //             $service = "twilio";
    //             $getResp = SMS::sendSMS($request->profile["country_code"] . $request->profile["mobile"], $text, $service);
    //         }
    //         OTPMaster::where("profile_id",$id->id)->update(["expired_at"=>date("Y-m-d H:i:s")]);
    //         $insert = OTPMaster::create(["profile_id" => $id->id, "otp" => $otpNo, "mobile" => $request->profile["mobile"], "service" => $service]);
    //         if ($getResp && $insert) {
    //             $this->model = true;
    //             return $this->sendResponse();
    //         }
    //     } else {
    //         return $this->sendError("OTP not generated");
    //     }
    // }
    
    public function verifyOTP(Request $request)
    {
        $source = config("constant.LOGIN_OTP_SOURCE");

        $otp = OTPMaster::where('mobile', "=", $request->profile["mobile"])

            ->where("expired_at", '>', date("Y-m-d H:i:s"))
            ->where("source", $source)
            ->orderBy("id", "desc")
            ->where("deleted_at", null)->first();
        if ($otp) {
            $otp->update(["attempts" => $otp->attempts + 1]);
        }
        
        //for testing
        $getOTP = OTPMaster::where('mobile', "=", $request->profile["mobile"])

            // ->where("otp", $request->otp)
            ->where("expired_at", '>', date("Y-m-d H:i:s"))
            ->where("source", $source)
            ->orderBy("id", "desc")
            ->where("deleted_at", null)
            ->first();
        
        if ($getOTP && $getOTP->attempts > config("constant.OTP_LOGIN_VERIFY_MAX_ATTEMPT")) {
            $getOTP->update(["deleted_at" => date("Y-m-d H:i:s")]);
            return $this->sendError("OTP attempts exhausted. Please regenerate OTP or try other login methods.");
        }
        if ($getOTP && $getOTP->otp==$request->otp) {
            $getProfileUser = Profile::where("id", $getOTP->profile_id)->first();
            $user = AppUser::find($getProfileUser->user_id);
            $token = JWTAuth::fromUser($user);
            if (!$token) {
                return $this->sendError("Failed to login");
            }
            OTPMaster::where("profile_id", $getOTP->profile_id)->update(["deleted_at" => date("Y-m-d H:i:s")]);
            $this->checkForDeactivationViaOTP($user);
            $this->model = ["token" => $token];
            return $this->sendResponse();
        }
        
        return $this->sendError("Incorrect OTP entered. Please try again.");
    }
    
    public function checkForDeactivation(Request $request){
        $credentials = $request->only('email','password');
        $user = \App\User::where('email',$credentials['email'])->whereNull('deleted_at')->where('account_deactivated',1)->pluck('id')->toArray();
        if (count($user) > 0){
            $profile_id = \App\Profile::where('user_id',$user[0])->pluck('id')->toArray();

            $req_data = DB::table('account_deactivate_requests')->where('profile_id', $profile_id[0])->first();
            $user_update_data = ['account_deactivated'=>0];
            $user_detail = json_decode($req_data->user_detail, true);
            if(!empty($user_detail['verified_at'])){
                $user_update_data['verified_at'] = $user_detail['verified_at'];
            }

            \App\User::where('email',$credentials['email'])->whereNull('deleted_at')->where('account_deactivated',1)->update($user_update_data);
            Redis:: lrem('deactivated_users', 0, $user[0]);
            DB::table('account_deactivate_requests')->where('profile_id', $profile_id[0])->update(['deleted_at'=>Carbon::now()]);         
            $deactivate_changes = (new AccountDeactivateChanges($profile_id[0], false));
            dispatch($deactivate_changes);
            $user = \App\User::where('email',$credentials['email'])->whereNull('deleted_at')->first();
            $data = ['name'=>$user->name, 'email'=>$user->email];
            Mail::send('emails.account-reactivate', ["data" => $data], function($message) use($user){
                $message->to($user->email, $user->name)->subject('Welcome back to TagTaste');
            });
        }
    }
    
    public function checkForDeactivationViaOTP(AppUser $userApp){
        $user = \App\User::where('email',$userApp->email)->whereNull('deleted_at')->where('account_deactivated',1)->pluck('id')->toArray();
        if (count($user) > 0){
            $profile_id = \App\Profile::where('user_id',$user[0])->pluck('id')->toArray();

            $req_data = DB::table('account_deactivate_requests')->where('profile_id', $profile_id[0])->first();
            $user_update_data = ['account_deactivated'=>0];
            $user_detail = json_decode($req_data->user_detail, true);
            if(!empty($user_detail['verified_at'])){
                $user_update_data['verified_at'] = $user_detail['verified_at'];
            }
            
            \App\User::where('email',$userApp->email)->whereNull('deleted_at')->where('account_deactivated',1)->update($user_update_data);
            Redis:: lrem('deactivated_users', 0, $user[0]);
            DB::table('account_deactivate_requests')->where('profile_id', $profile_id[0])->update(['deleted_at'=>Carbon::now()]);         
            $deactivate_changes = (new AccountDeactivateChanges($profile_id[0], false));
            dispatch($deactivate_changes);

            $user = \App\User::where('email',$userApp->email)->whereNull('deleted_at')->first();
            $data = ['name'=>$user->name, 'email'=>$user->email];
            Mail::send('emails.account-reactivate', ["data" => $data], function($message) use($user){
                $message->to($user->email, $user->name)->subject('Welcome back to TagTaste');
            });
        }
    }
    
    public function checkForDeactivationViaSocial(User $userApp){
        $user = \App\User::where('email',$userApp->email)->whereNull('deleted_at')->where('account_deactivated',1)->pluck('id')->toArray();
        if (count($user) > 0){
            $profile_id = \App\Profile::where('user_id',$user[0])->pluck('id')->toArray();

            $req_data = DB::table('account_deactivate_requests')->where('profile_id', $profile_id[0])->first();
            $user_update_data = ['account_deactivated'=>0];
            $user_detail = json_decode($req_data->user_detail, true);
            if(!empty($user_detail['verified_at'])){
                $user_update_data['verified_at'] = $user_detail['verified_at'];
            }
            
            \App\User::where('email',$userApp->email)->whereNull('deleted_at')->where('account_deactivated',1)->update($user_update_data);
            Redis:: lrem('deactivated_users', 0, $user[0]);
            DB::table('account_deactivate_requests')->where('profile_id', $profile_id[0])->update(['deleted_at'=>Carbon::now()]);         
            $deactivate_changes = (new AccountDeactivateChanges($profile_id[0], false));
            dispatch($deactivate_changes);

            $user = \App\User::where('email',$userApp->email)->whereNull('deleted_at')->first();
            $data = ['name'=>$user->name, 'email'=>$user->email];
            Mail::send('emails.account-reactivate', ["data" => $data], function($message) use($user){
                $message->to($user->email, $user->name)->subject('Welcome back to TagTaste');
            });
        }
    }
}
