<?php

namespace App\Http\Controllers\Auth;

use App\Events\Actions\JoinFriend;
use App\Exceptions\Auth\SocialAccountUserNotFound;
use App\Http\Controllers\Api\Controller;
use App\Invitation;
use App\Profile\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
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

    protected $validInviteCode = true;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest', ['except' => 'logout']);
        if($request->token){
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
        if(Auth::user()->hasRole('admin')){
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
    public function handleProviderCallback(Request $request,$provider)
    {
        $input = $request->all();
        $authUser = $this->findOrCreateUser($input, $provider);
      
        if(!$authUser)
        {
            return response()->json(['status'=>'failed','error'=>"Could not login.",'status'=>'failed'],400);
        }
        $result = ['status'=>'success' , 'newRegistered' => $this->newRegistered];
        if(!$this->validInviteCode)
        {
            return ['status'=>'failed','errors'=>"please use correct invite code",'status'=>'failed'];
        }
        $token = \JWTAuth::fromUser($authUser);
        unset($authUser['profile']);
        $result['result'] = ['user'=>$authUser,'token'=>$token];

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
            $user = \App\Profile\User::findSocialAccount($provider,$socialiteUser['id']);

        } catch (SocialAccountUserNotFound $e){
            //check if user exists,
            //then add social login
            if($socialiteUser['email']){
                $user = User::where('email','like',$socialiteUser['email'])->first();
            }
            else
            {
                return null;
            }
            if($user){
                //create social account;
                $this->newRegistered = false;
                $user->createSocialAccount($provider,$socialiteUser['id'],$socialiteUser['avatar_original'],$socialiteUser['token']);
            } else {
                $this->newRegistered = true;
                $inviteCode = $socialiteUser['invite_code'];
                $alreadyVerified = false;
                if(isset($inviteCode) && !empty($inviteCode))
                {
                    $invitation = Invitation::where('invite_code', $inviteCode)
                        ->where('state',Invitation::$mailSent)->first();
                    if(!$invitation)
                    {
                        $this->validInviteCode = false;
                        return ['status'=>'failed','errors'=>"please use correct invite code",'result'=>[]];
                    }
                    $alreadyVerified = true;
                    $profileId = $invitation->profile_id;
                }
                else
                {
                    $this->validInviteCode = false;
                    return ['status'=>'failed','errors'=>"please use correct invite code",'result'=>[]];
                }
              
                $user = \App\Profile\User::addFoodie($socialiteUser['name'],$socialiteUser['email'],str_random(6),
                    true,$provider,$socialiteUser['id'],$socialiteUser['avatar_original'],$alreadyVerified,$socialiteUser['token'],$inviteCode);

                if($alreadyVerified) {
                    $profiles = \App\Profile::with([])->where('id', $profileId)->orWhere('user_id', $user->id)->get();

                    $loginProfile = $profiles[0]->user_id == $user->id ? $profiles[0] : $profiles[1];
                    $profile = $profiles[0]->user_id != $user->id ? $profiles[0] : $profiles[1];
                    event(new JoinFriend($profile, $loginProfile));
                }
            }

        }
        return $user;

    }
}
