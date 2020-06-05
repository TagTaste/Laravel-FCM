<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DocSubmissionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $collaborate;
    public $profileId;
    public $profile;
    public $action;
    public function __construct($profileId,$collaborate,$profile,$files)
    {
        $this->profileId = $profileId;
        $this->collaborate = $collaborate;
        $this->profile = $profile;
        $this->action = 'document_submission';
        $this->files = $files;
    }
}
