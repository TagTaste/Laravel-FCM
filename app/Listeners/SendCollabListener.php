<?php

namespace App\Listeners;

use App\Events\SendCollabEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendCollabListener
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
     * @param  SendCollabEvent  $event
     * @return void
     */
    public function handle(SendCollabEvent $event)
    {
        //
        foreach ($event->users as $user) {
            # code...
            Mail::to($user)->queue(new \App\Mail\SendTopCollab());
        }
    }
}
