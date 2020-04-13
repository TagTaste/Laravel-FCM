<?php

namespace App\Listeners;

use App\Events\ReportContentUserEvent;
//use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;
use Monolog\Formatter\NormalizerFormatter;
use App\Profile;
use Mail;

class ReportContentUser
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
    public function handle(ReportContentUserEvent $event)
    {
        $data = array(
            'type' => $event->type,
            'profileUrl' => $event->profileUrl,
            'reportedUrl' => $event->reportedUrl,
            'issue' => $event->issue,
            'reportedOn' => $event->reportedOn,
            'reporterName' => $event->reporterName,
            'emailId' => $event->emailId,
            'phoneNumber' => $event->phoneNumber,
        );
        
        Mail::send(
            'emails.report-content-user', 
            [
                'data' => $data
            ], 
            function ($mail) use ($data) {
                $mail->from(config('mail.from.address'), config('mail.from.name'));
                $mail->to(config('mail.tagtaste_report_content_user_mail_id'), null)->subject('IMPORTANT - Reported '.$data['type'].' on TagTaste');
            }
        );
    }
}
