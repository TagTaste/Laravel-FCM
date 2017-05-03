<?php

namespace App\Listeners;

use App\Events\UpdateFeedable as UpdateFeedableEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        if(method_exists($event->model,'payload')){
            $event->model->payload->update(['payload'=>$event->model,get_class($event->model),$event->model->id]);
        }
    }
}
