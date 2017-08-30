<?php

namespace App\Listeners;

use App\Events\SendInvitationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use App\Invitation;

class SendInvitationNotifications extends Mailable
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $inviteCode;

    public function __construct()
    {
        $this->inviteCode = str_random(15);
    }

    /**
     * Handle the event.
     *
     * @param  SendInvitationEmail  $event
     * @return void
     */
    public function handle(SendInvitationEmail $event)
    {

        \Mail::to($event->email)->send($this->mailView($event));
        Invitation::create(['invite_code'=>$this->inviteCode,'name'=>$event->inviteUser->name,'email'=>$event->email, 'accepted_at'=>null]);
    }

    public function build()
    {
        return [];
    }

    public function mailView($event)
    {
        return $this->view("invitation.invitation")->with([
            "userName"=>$event->user->name,
            "inviteCode" => $this->inviteCode,
        ]);
    }
}
