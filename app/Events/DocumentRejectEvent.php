<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DocumentRejectEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $collaborate;
    public $profileId;
    public $company;
    public $action;
    public function __construct($profileId, $company = null, $action = null, $collaborate)
    {
        $this->profileId = $profileId;
        $this->collaborate = $collaborate;
        $this->$company = null;
        if (isset($company)) {
            $this->company = ['id'=>$company->id, 'name'=>$company->name, 'imageUrl'=>$company->logo, 'type'=>'company', 'tagline'=>$company->tagline];
        }
        $this->action = $action === null ? strtolower(class_basename(static::class)) : $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
//    public function broadcastOn()
//    {
//        return new PrivateChannel('channel-name');
//    }
}
