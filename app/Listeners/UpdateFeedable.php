<?php

namespace App\Listeners;

use App\Events\UpdateFeedable as UpdateFeedableEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Channel\Payload;


class UpdateFeedable
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UpdateFeedable  $event
     * @return void
     */
    public function handle(UpdateFeedableEvent $event)
    {
        if (method_exists($event->model, 'payload')) {
            if(!empty(Payload::withTrashed()->find($event->model->payload_id))){
            Payload::withTrashed()->find($event->model->payload_id)->restore(); 
            }           
        }
    }
}
