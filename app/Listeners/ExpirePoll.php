<?php

namespace App\Listeners;

use App\Notify\Profile;
use App\Events\ExpirePoll as ExpirePollEvent;
use App\Notifications\ExpiryPoll as ActionsExpiryPoll;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class ExpirePoll
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
     * @param  ExpirePoll  $event
     * @return void
     */
    public function handle(ExpirePollEvent $event)
    {
        \Log::info("notifying");
        
        Notification::send($event->model->profile, new ActionsExpiryPoll($event));
        
    }
}
