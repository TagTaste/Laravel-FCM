<?php

namespace App\Events;

use App\ModelSubscriber;
use App\Profile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Action
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $model;
    public $who;
    public $action;
    public $image;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $model, Profile $who, $image = null, $action = null)
    {
        $this->model = $model;
        $this->who = $who;
        $this->action = $action === null ? strtolower(class_basename(static::class)) : $action;
        $this->image = $image;
    }

    public function getModelName(){
        return strtolower(class_basename($this->model));
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
