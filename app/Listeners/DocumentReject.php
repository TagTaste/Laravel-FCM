<?php

namespace App\Listeners;

use App\Events\DocumentRejectEvent;
//use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;
use Monolog\Formatter\NormalizerFormatter;
use App\Notifications\DocumentRejected;
use Notification;
use App\Notify\Profile;

class DocumentReject implements ShouldQueue
{
    //use Notifiable;
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
     * @param  DocumentReject  $event
     * @return void
     */
    public function handle(DocumentRejectEvent $event)
    {
        $profile = Profile::where('id',$event->profileId)->get();
        Notification::send($profile, new DocumentRejected($event));
    }
}
