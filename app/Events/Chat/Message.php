<?php

namespace App\Events\Chat;

use App\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Message
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatId;
    public $profile;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Chat\Message $message, $profile)
    {
        $this->chatId = $message->chat_id;
        $this->profile = $profile;
    }
}
