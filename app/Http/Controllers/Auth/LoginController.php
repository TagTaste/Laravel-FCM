<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

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
            Session::put('admin', User::find(1));
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
        if (Auth::user()->hasRole('foodie')) {
            return '/home';
        } else {
            return '/logout';
        }
    }
}
