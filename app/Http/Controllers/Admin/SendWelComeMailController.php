<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;

class SendWelComeMailController extends Controller
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

    protected $user;

    public function showMailForm()
    {
        return view('email.mail');
    }

    public function doMail(Request $request)
    {
        $data = ["email" =>$request['email'],'password'=>$request['password'],'name'=>$request['name'],'to'=>$request['to']];
        $this->user = $data;
        \Config::set('mail.driver', 'smtp');
        (new \Illuminate\Mail\MailServiceProvider(app()))->register();
        \Mail::send('email.invite', $data, function($message)
        {
            $message->to($this->user['to'], $this->user['name'])->subject("Welcome aboard, ".$this->user['name']."!");
        });

        return redirect()->to("/mail")->with(['message'=>'success']);
    }
}
