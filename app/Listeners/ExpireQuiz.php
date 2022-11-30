<?php

namespace App\Listeners;

use App\Events\ExpireQuiz as ExpireQuizEvent;
use App\Notifications\ExpiryQuiz as ActionsExpiryQuiz;
use Illuminate\Support\Facades\Notification;

class ExpireQuiz
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
     * @param  ExpireQuiz  $event
     * @return void
     */
    public function handle(ExpireQuizEvent $event)
    {
        \Log::info("notifying");
        
        Notification::send($event->model->profile, new ActionsExpiryQuiz($event));
        
    }
}
