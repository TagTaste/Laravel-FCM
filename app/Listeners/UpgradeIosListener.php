<?php

namespace App\Listeners;

use App\Events\UpgradeIosEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpgradeIosListener
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
     * @param  UpgradeIosEvent  $event
     * @return void
     */
    public function handle(UpgradeIosEvent $event)
    {
        //
        Notification::send($event->profile, new \App\Notifications\UpgradeIos());
    }
}
