<?php

namespace App\Listeners\Subscriber;

use App\Events\Model\Subscriber\Destroy;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Destroy
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
     * @param  Destroy  $event
     * @return void
     */
    public function handle(Destroy $event)
    {
        //
    }
}
