<?php

namespace App\Events\Chat\V1;

use App\V1\Chat;
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
    public $message;
    public $image;
    public $id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Chat\Message $message, $profile)
    {
        $this->id = $message->id;
        $this->chatId = $message->chat_id;
        $this->message = $message->message;
        $this->image = $message->fileUrl;
        $this->profile = $profile;
        $this->headerAction = $message->headerAction;
    }
}
