<?php

namespace App\Listeners;

use App\Events\EmailVerification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;


class EamilVarificationNotification extends Mailable implements ShouldQueue
{
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
     * @param  EmailVerification  $event
     * @return void
     */
    public function handle(EmailVerification $event)
    {
        \Mail::to($event->user->email)->send($this->emailTemplate($event));
    }

    public function build()
    {
        return [];
    }

    public function emailTemplate($event)
    {
        return $this->view("email.email")->with([
            "email_token" => $event->user->email_token,
        ]);
    }
}
