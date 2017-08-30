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
        $this->inviteCode = $this->generate();
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

    public static function generate()
    {
        $exists = true;
        while ($exists) {
            $code = str_random(15);
            $check = Invitation::where('invite_code', $code)->first();
            if( ! $check){
                $exists = false;
            }
        }
        return $code;
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
