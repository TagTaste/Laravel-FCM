<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewFeedable
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    /**
     * Model that is pushed on to feed
     *
     * @var
     */
    public $model;
    
    /**
     * The creator of the model. Could as well be somebody else.
     *
     * Profile or Company.
     *
     * @var
     */
    public $owner;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $model, Model $owner = null)
    {
        $this->model = $model;
        $this->owner = $owner;
        if(is_null($owner)){
            $this->owner = $model->getOwner();
        }
    }
}
