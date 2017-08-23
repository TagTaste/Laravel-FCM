<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\EmailVerification;

class SendVerificationEmail implements ShouldQueue
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    protected $user;
    public function __construct($user)
    {
        $this->user = $user;
        $email = new EmailVerification($this->user);
        \Mail::to($this->user->email)->send($email);
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
