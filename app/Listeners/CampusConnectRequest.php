<?php

namespace App\Listeners;

use App\Events\CampusConnectRequestEvent;
//use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;
use Monolog\Formatter\NormalizerFormatter;
use App\Profile;
use Mail;

class CampusConnectRequest
{
    //use Notifiable;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DocumentReject  $event
     * @return void
     */
    public function handle(CampusConnectRequestEvent $event)
    {
        $profile = Profile::where('id',$event->profileId)->first();
        $data = array(
            'profile-id' => $event->profileId,
            'name' => $profile->name,
            'email' => $event->userEmail,
            'contact' => $profile->phone,
            'campus-name' => $event->campusName
        );
        Mail::send(
            'emails.campus-connect', 
            [
                'data' => $data
            ], 
            function ($mail) use ($data) {
                $mail->from(config('mail.from.address'), config('mail.from.name'));
                $mail->to(config('mail.tagtaste_campus_connect_mail_id'), null)->subject('New Campus Connect Request');
            }
        );
    }
}
