<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth\SocialAccountUserNotFound;
use App\Http\Controllers\Api\Controller;
use App\User;
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
    public function handleProviderCallback(Request $request, $provider)
    {
        \Log::info($request->all());
        dd($request->all());
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            \Log::warning($e->getMessage());
            \Log::warning((string) $e->getResponse()->getBody());
            return response()->json(['error'=>"Could not login."],400);
        } catch (\GuzzleHttp\Exception\ClientException $e){
            \Log::warning($e->getMessage());
            \Log::warning((string) $e->getResponse());
            return response()->json(['error'=>"Could not login."],400);
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
            $user = \App\Profile\User::findSocialAccount($provider,$socialiteUser->getId());
        } catch (SocialAccountUserNotFound $e){
            //check if user exists,
            //then add social login
            if($socialiteUser->getEmail()){

                $user = User::where('email','like',$socialiteUser->getEmail())->first();
            }
            else
            {
                return redirect('/');
            }
            if($user){
                //create social account;
                $user->createSocialAccount($provider,$socialiteUser->getId(),$socialiteUser->getAvatar());
            } else {
                $user = \App\Profile\User::addFoodie($socialiteUser->getName(),$socialiteUser->getEmail(),str_random(6),true,1,$provider,$socialiteUser->getId(),$socialiteUser->getAvatar());
            }
        }
        return $user;

    }
}
