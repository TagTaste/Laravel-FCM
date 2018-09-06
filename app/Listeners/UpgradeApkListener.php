<?php

namespace App\Listeners;

use App\Events\UpgradeApkEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

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
        Notification::send($event->profile, new \App\Notifications\UpgradeApk());
    }
}
