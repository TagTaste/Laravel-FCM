<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\PasswordReset;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->expires = 60 * 60;
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        if ($token != NULL) {
            $validateToken = $this->exists($token);
            if ($validateToken) {
                $email = PasswordReset::where('token', $token)->first(); 
                return view('auth.passwords.reset')->with(['token' => $token, 'email' => $email->email]);
            } else {
                return view('auth.passwords.reset')->withErrors(['expired' => 'This link has already expired!']);
            }
        } else {
            return view('auth.passwords.reset')->withErrors(['expired' => 'This link has already expired!']);
        }
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
        $this->validate($request, $this->rules());
        $response = $this->resetPassword($request['email'], $request['password'], $request['token']);

        if ($response != 'false') {
            Auth::login($response, true);
            $this->delete($response->email);
            return redirect($this->redirectPath())->with('status', "Password reset successfully.");
        } else {
            return redirect()->back()->withInput($request->only('email'))->withErrors(['email' => 'Invalid Email Address.']);
        }
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required', 'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($email, $password, $token)
    {
        $emailActual = PasswordReset::where('token', $token)->first();
        if ($emailActual->email == $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->password = bcrypt($password);
                $user->remember_token = Str::random(60);
                $user->save();
                return $user;
            } else {
                return 'false';
            }
        } else {
            return 'false';
        }
    }

    /**
     * Determine if a token record exists and is valid.
     * @param  string  $token
     * @return bool
     */
    public function exists($token)
    {
        $token = PasswordReset::where('token', $token)->first();
        return $token && ! $this->tokenExpired($token);
    }

    /**
     * Determine if the token has expired.
     *
     * @param  array  $token
     * @return bool
     */
    protected function tokenExpired($token)
    {
        $expiresAt = Carbon::parse($token['created_at'])->addSeconds($this->expires);
        return $expiresAt->isPast();
    }

    /**
     * Delete a token record by token.
     *
     * @param  string  $token
     * @return void
     */
    public function delete($email)
    {
        PasswordReset::where('email', $email)->delete();
    }

    /**
     * Delete expired tokens.
     *
     * @return void
     */
    public function deleteExpired()
    {
        $expiredAt = Carbon::now()->subSeconds($this->expires);

        PasswordReset::where('created_at', '<', $expiredAt)->delete();
    }
}
