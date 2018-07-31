<?php

namespace App\Listeners;

use App\Events\FeatureMailEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Jobs\SendFeatureMessage as SendMessage;

class FeatureMailListener implements ShouldQueue
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
        foreach ($event->profileIds as $id) {
            dispatch(new SendMessage($event->inputs,$id,$event->data['sender_info']->profile));
        }
        if($event->inputs['is_mailable'])
        {
            $profiles = \App\Profile::whereIn('id',$event->profileIds)->get();
            Notification::send($profiles, new \App\Notifications\FeatureMessage($event->data,$profiles));

        }

    }
}
