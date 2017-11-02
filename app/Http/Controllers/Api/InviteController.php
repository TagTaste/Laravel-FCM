<?php

namespace App\Http\Controllers\Api;

use App\Invitation;
use App\Jobs\SendInvitation;
use App\User;
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

            $userExist = User::where('email',$email)->exists();
            if($userExist)
            {
                $mail = (new SendInvitation($request->user(),$email))->onQueue('invites');
                \Log::info('Queueing send invitation...');
                dispatch($mail);
                continue;
            }

            $mail = (new SendInvitation($request->user(),$temp))->onQueue('invites');
            \Log::info('Queueing send invitation...');
            dispatch($mail);
            $inputs[] = $temp;
        }

        $this->model = Invitation::insert($inputs);

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
