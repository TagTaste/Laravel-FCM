<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Update
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $modelId;
    public $modelName;
    public $profileId;
    public $content;


    public function __construct($modelId,$modelName,$profileId,$content)
    {
        //
        $this->modelId=$modelId;
        $this->modelName=$modelName;
        $this->profileId=$profileId;
        $this->content=$content;
    }
}
