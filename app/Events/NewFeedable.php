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
    
    
    public $payloadable;
    
    
    /**
     * NewFeedable constructor.
     * @param Model $model The model to push on the feed
     * @param Model|null $owner The profile on whose feed the $model is pushed to
     * @param Model|null $payloadable The model which gets the payload_id;
     */
    public function __construct(Model $model, Model $owner = null, Model $payloadable = null)
    {
        $this->model = $model;
        $this->owner = $owner;
        $this->payloadable = $payloadable;
        
        if(!$payloadable){
            $this->payloadable = $model;
        }
        if(is_null($owner)){
            $this->owner = $model->getOwner();
        }
    }
}
