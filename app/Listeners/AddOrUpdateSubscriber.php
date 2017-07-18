<?php

namespace App\Listeners;

use App\Events\Action;
use App\ModelSubscriber;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddOrUpdateSubscriber
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
     * @param  Action  $event
     * @return void
     */
    public function handle(Action $event)
    {
        ModelSubscriber::updateSubscriberTimestamp($event->getModelName(),$event->model->id,$event->who->id);
    }
}
