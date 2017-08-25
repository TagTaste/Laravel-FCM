<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Notifications\SendInvitation;


class SendInvitationEmail
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    protected $user;
    protected $email;
    public function __construct($user,$email)
    {
        $this->user = $user;
        $user = new SendInvitation($this->user,$email);
        \Mail::to($email)->send($user);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function handle()
    {
        //
    }
}
