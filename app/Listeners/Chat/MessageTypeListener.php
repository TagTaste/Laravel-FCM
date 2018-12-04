<?php

namespace App\Listeners\Chat;

use App\Events\Chat\MessageTypeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageTypeListener
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
     * @param  MessageTypeEvent  $event
     * @return void
     */
    public function handle(MessageTypeEvent $event)
    {   
        $model = \App\V1\Chat\Message::create($event->info);
    }
}
