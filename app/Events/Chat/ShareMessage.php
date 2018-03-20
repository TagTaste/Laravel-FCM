<?php

namespace App\Events\Chat;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShareMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $chatIds;
    public $profileIds;
    public $inputs;
    public $user;


    public function __construct($chatIds,$profileIds,$inputs,$user)
    {
        $this->chatIds=$chatIds;
        $this->profileIds=$profileIds;
        $this->inputs=$inputs;
        $this->user = $user;
    }
}
