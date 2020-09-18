<?php

namespace App\Http\Controllers;

use App\Faqs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;

class CareerEmailController extends Controller
{
    public function careerMail(Request $request)
    {
        $emailTo = "jobs@tagtaste.com";
        if ("https://dev.tagtaste.com" == env("APP_URL")) {
            $emailTo = ["harsh@tagtaste.com", "sarvada@tagtaste.com"];
        }

        $attach = $request->file('resume');

        $data = array(
          'name' =>$request->input('name'),
          'description' => $request->input('description'),
          'email'=> $request->input('email'),
          'job' => $request->input('job')
        );
        Mail::send(
            'emails.jobApplyMail',
            [
                'data' => $data
            ], 
            function ($message) use ($emailTo, $attach) {
                $message->from('noreply@tagtaste.com','Tagtaste');
                $message->to($emailTo);
                $message->subject('Job at Tagtaste');
                $message->attach($attach,[
                    'as' => 'resume',
                    'mime' => 'application/pdf'
                ]);
        });
        return response()->json([
            'errors' => [],
            'data' => true,
            'messages' => "Email sent.",
        ]);
    }
}