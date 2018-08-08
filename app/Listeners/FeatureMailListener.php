<?php

namespace App\Listeners;

use App\Events\FeatureMailEvent;
use Illuminate\Support\Facades\Notification;
use App\Jobs\SendFeatureMessage as SendMessage;

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
        foreach ($event->profileIds as $id) {
            dispatch(new SendMessage($event->inputs,$id,$event->data['sender_info']->profile));
        }
        if(isset($event->inputs['is_mailable']) && $event->inputs['is_mailable'])
        {
            foreach ($event->profileIds as $profileId)
            {
                $profiles = \App\Profile::whereIn('id',[$profileId])->get();
                Notification::send($profiles, new \App\Notifications\FeatureMessage($event->data,$profiles));
            }

        }

    }
}
