<?php

namespace App\Listeners;

use App\Events\FeatureMailEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class FeatureMailListener
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
     * @param  FeatureMailEvent  $event
     * @return void
     */
    public function handle(FeatureMailEvent $event)
    {
        //
        $profiles = \App\Profile::whereIn('id',$event->userIds)->get();
        Notification::send($profiles, new \App\Notifications\FeatureMessage($event->data,$profiles));
    }
}
