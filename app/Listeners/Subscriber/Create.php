<?php

namespace App\Listeners\Subscriber;

use App\Events\Model\Subscriber\Create as ModelCreateEvent;
use App\ModelSubscriber;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Create
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
     * @param  Create  $event
     * @return void
     */
    public function handle(ModelCreateEvent $event)
    {
        ModelSubscriber::updateSubscriberTimestamp(strtolower(class_basename($event->model)),$event->model->id,$event->profile->id);
    }
}
