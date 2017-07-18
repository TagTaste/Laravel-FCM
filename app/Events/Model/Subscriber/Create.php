<?php

namespace App\Events\Model\Subscriber;

use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Create
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $model;
    public $profile;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $model, $profile)
    {
        $this->model = $model;
        $this->profile = $profile;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
