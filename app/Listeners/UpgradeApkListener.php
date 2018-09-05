<?php

namespace App\Listeners;

use App\Events\UpgradeApkEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpgradeApkListener
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
     * @param  UpgradeApk  $event
     * @return void
     */
    public function handle(UpgradeApkEvent $event)
    {
        //
        \Log::info("about to send notifics");
        Notification::send($event->profile_id, new \App\Notifications\UpgradeApk());
    }
}
