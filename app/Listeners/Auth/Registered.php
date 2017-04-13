<?php

namespace App\Listeners\Auth;

use App\Notifications\UserWelcome;
use App\Events\Auth\Registered as RegisteredEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Registered
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
     * @param  Registered  $event
     * @return void
     */
    public function handle(RegisteredEvent $event)
    {
        \Log::warning("NOT SENDING WELCOME EMAIL.");
        //$event->user->notify(new UserWelcome());
    }
}
