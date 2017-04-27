<?php

namespace App\Listeners;

use App\Events\DeleteFeedable as DeleteFeedableEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteFeedable
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
     * @param  DeleteFeedable  $event
     * @return void
     */
    public function handle(DeleteFeedableEvent $event)
    {
        \Log::info(method_exists($event->model,'payload'));
        if(method_exists($event->model,'payload')){
            $response = $event->model->payload->delete();
            \Log::info($response);
        }
    }
}
