<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DeleteFeedable
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $model;
    public $modelName;
    public $modelId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($model,$modelName,$modelId)
    {
        $this->model = $model;
        $this->modelName = $modelName;
        $this->modelId = $modelId;
    }
}
