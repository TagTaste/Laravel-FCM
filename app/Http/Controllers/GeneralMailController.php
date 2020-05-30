<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralMailController extends Controller
{
    public function tieReportMail(Request $request)
    {
        $failed = $this->verifyMail($request->email);
        if($failed) {
            return $this->sendError('Invalid Email Id Given');
        } else {
            $this->sendMail($request->email);
        }
    }

    protected function verifyEmail($email)
    {
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email',
        ]);
        return $validator->fails();
    }

    protected function sendMail($email)
    {
        
    }
}
