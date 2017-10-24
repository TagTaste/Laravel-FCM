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
            $inputs[]['invite_code'] = str_random(15);
            $inputs[]['profile_id'] = $request->user()->profile->id;
            $inputs[]['state'] = 1;
            $inputs[]['mail_code'] = str_random(15);
            $inputs[]['email'] = $email['email'];
            $inputs[]['name'] = $email['name'];

            $inputs[]['accepted_at'] = null;

            $mail = (new SendInvitation($request->user(),$inputs[],$request->input("email"),
                $inputs[]['invite_code'],$inputs[]['mail_code']))->onQueue('emails');
            \Log::info('Queueing send invitation...');

            dispatch($mail);
        }

        $this->model = Invitation::create($inputs);

        return $this->sendResponse();
    }
}
