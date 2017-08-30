<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SendInvitationEmail
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $user;
    public $email;
    public $inviteUser;

    public function __construct($user,$inviteUser,$email)
    {
        $this->user = $user;
        $this->inviteUser = $inviteUser;
        $this->email = $email;
    }

}
