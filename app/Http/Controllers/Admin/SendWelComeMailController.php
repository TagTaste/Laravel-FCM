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

        \Mail::send('emails.welcome', $data, function($message)
        {
            $emails = explode(",",$this->user['to']);
            foreach($emails as $email){
                $message->bcc("aman@tagtaste.com","Aman")->to($email, $this->user['name'])->subject("Welcome aboard, ".$this->user['name'].".");
//                $message->bcc('tanvi@tagtaste.com','Tanvi')->bcc("core@tagtaste.com",'Core Team')->to($email, $this->user['name'])->subject("Welcome aboard, ".$this->user['name'].".");
            }
        });

        return redirect()->to("/mail")->with(['message'=>'success']);
    }
}
