<?php

namespace App\Http\Controllers\Api;

use App\Invitation;
use App\Jobs\SendConnectionInvite;
use App\Jobs\SendInvitation;
use App\User;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    //state mail-sent =1 , mail-opened =2 , registered = 3
    public function invite(Request $request)
    {
        $emails = $request->input("email");
        $message = $request->input('message');
        $inputs = [];
        \Log::info("here");
        foreach ($emails as $email)
        {
            $temp = [];
            $temp['invite_code'] = 123456;
            $temp['profile_id'] = $request->user()->profile->id;
            $temp['state'] = Invitation::$mailSent;
            $temp['mail_code'] = str_random(15);
            $temp['email'] = $email['email'];
            $temp['name'] = $email['name'];
            $temp['message'] = $message;

            $temp['accepted_at'] = null;
            $mail = (new SendInvitation($request->user(),$temp))->onQueue('invites');
            \Log::info('Queueing send invitation...');
            dispatch($mail);
            $inputs[] = $temp;
        }

        $this->model = Invitation::insert($inputs);

        return $this->sendResponse();
    }

    public function mailTrack(Request $request, $mailCode)
    {
        $this->model = Invitation::where('mail_code',$mailCode)->update(['state'=>2]);

        return $this->sendResponse();
    }
}