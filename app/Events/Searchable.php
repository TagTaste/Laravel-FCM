<?php

namespace App\Events;

use App\Documents\Document;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Searchable
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $document;
    
   
    public function __construct(Document $document)
    {
        $this->document = $document;
    }
}
