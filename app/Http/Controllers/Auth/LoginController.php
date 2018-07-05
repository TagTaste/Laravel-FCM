<?php

namespace App\Http\Controllers\Auth;

use App\Events\Actions\JoinFriend;
use App\Exceptions\Auth\SocialAccountUserNotFound;
use App\Http\Controllers\Api\Controller;
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
        $result = ['status'=>'success' , 'newRegistered' => $this->newRegistered];
        $input = $request->all();
        \Log::info($input);
        $authUser = $this->findOrCreateUser($input, $provider);
        if(!$authUser)
        {
            return ['status'=>'failed','errors'=>"Could not login.",'result'=>[],'newRegistered' => false];
        }

        $token = \JWTAuth::fromUser($authUser);
        unset($authUser['profile']);
        $result['result'] = ['user'=>$authUser,'token'=>$token];
        $result['newRegistered'] = $this->newRegistered;

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
                $socialiteUserLink = isset($socialiteUser['user']['link']) ? $socialiteUser['user']['link']:(isset($socialiteUser['user']['publicProfileUrl']) ? $socialiteUser['user']['publicProfileUrl'] : null);
                $user->createSocialAccount($provider,$socialiteUser['id'],$socialiteUser['avatar_original'],$socialiteUser['token'],$socialiteUserLink,$socialiteUser['user']);
            } else {

                $this->newRegistered = true;
                $socialiteUserLink = isset($socialiteUser['user']['link']) ? $socialiteUser['user']['link']:(isset($socialiteUser['user']['publicProfileUrl']) ? $socialiteUser['user']['publicProfileUrl'] : null);

                $user = \App\Profile\User::addFoodie($socialiteUser['name'],$socialiteUser['email'],null,
                    true,$provider,$socialiteUser['id'],$socialiteUser['avatar_original'],false,$socialiteUser['token'],$socialiteUserLink,$socialiteUser['user']);
            }

        }
        return $user;

    }
}
