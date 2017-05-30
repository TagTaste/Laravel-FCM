<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth\SocialAccountUserNotFound;
use App\SocialAccount;
use App\User;
use App\Role;
use App\Http\Controllers\Api\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
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
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            return Redirect::to('/login');
        }

        $authUser = $this->findOrCreateUser($user, $provider);
        
        $token = \JWTAuth::fromUser($authUser);
        
        return response()->json(compact('token'));
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
            $user = User::findSocialAccount($provider,$socialiteUser->getId());
        } catch (SocialAccountUserNotFound $e){
            $user = User::addFoodie($socialiteUser->getName(),$socialiteUser->getEmail(),str_random(15),true,$provider,$socialiteUser->getId(),$socialiteUser->getAvatar());
        }

        return $user;

    }
}
