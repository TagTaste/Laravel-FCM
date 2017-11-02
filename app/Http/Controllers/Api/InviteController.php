<?php

namespace App\Http\Controllers\Api;

use App\Invitation;
use App\Jobs\SendInvitation;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    //state mail-sent =1 , mail-opened =2 , registered = 3
    public function invite(Request $request)
    {
        $emails = $request->input("email");
        $inputs = [];
        foreach ($emails as $email)
        {
            $temp = [];
            $temp['invite_code'] = str_random(15);
            $temp['profile_id'] = $request->user()->profile->id;
            $temp['state'] = 1;
            $temp['mail_code'] = str_random(15);
            $temp['email'] = $email['email'];
            $temp['name'] = $email['name'];
            $temp['message'] = $email['message'];

            $temp['accepted_at'] = null;
            $mail = (new SendInvitation($request->user(),$temp))->onQueue('invites');
            \Log::info('Queueing send invitation...');
            $inputs[] = $temp;
            dispatch($mail);
        }

        $this->model = Invitation::create($inputs[0]);

        return $this->sendResponse();
    }
//
//    public function checkinvitation(Request $request)
//    {
//        $this->model = Invitation::where('email',$request->input('email'))->where('profile_id',$request->user()->profile->id)->exists();
//
//        if($this->model)
//        {
//            return $this->sendError("Already invite sent.");
//        }
//        return $this->sendResponse();
//    }
}
