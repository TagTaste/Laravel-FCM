<?php

namespace App\Listeners;

use App\Events\DeleteFilters as DeleteFilterEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteFilters
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
     * @param  ExpireModel  $event
     * @return void
     */
    public function handle(DeleteFilterEvent $event)
    {
        $class = "\App\Filter\\" . ucfirst($event->modelName);
        $filter = new $class;
        $filter::removeModel($event->modelId);
        \Log::debug("deleting filters for $event->modelName " . $event->modelId);
    }
}
