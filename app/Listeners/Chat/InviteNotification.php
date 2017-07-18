<?php

namespace App\Listeners\Chat;

use App\Events\Chat\Invite;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteNotification
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
     * @param  Invite  $event
     * @return void
     */
    public function handle(Invite $event)
    {
        //
    }
}
