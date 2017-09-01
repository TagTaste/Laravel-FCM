<?php

namespace App\Listeners;

use App\Events\SendFeedback;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;


class SendFeedbackNotifications extends Mailable
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $feedbackEmail;
    public function __construct()
    {
        $this->feedbackEmail = 'feedback@tagtaste.com';

    }

    /**
     * Handle the event.
     *
     * @param  SendFeedback  $event
     * @return void
     */
    public function handle(SendFeedback $event)
    {
        \Mail::to($this->feedbackEmail)->send($this->mailView($event));
    }

    public function mailView($event)
    {
        return $this->view("invitation.invitation")->with([
            "feedbackInfo"=>$event->info
        ]);
    }
}
