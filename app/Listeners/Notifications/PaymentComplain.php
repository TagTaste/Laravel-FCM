<?php

namespace App\Listeners\Notifications;

use App\Events\Actions\PaymentComplain as complaint;
use App\Notify\Profile;
use Illuminate\Support\Facades\Notification;

class PaymentComplain
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
     * @param  Share  $event
     * @return void
     */
    public function handle(complaint $event)
    {
        $profileId = $event->model->profile_id;
        $profile = Profile::find($profileId);
        
        if (isset($profile))
            Notification::send($profile, new \App\Notifications\Actions\PaymentComplain($event));
    }
}
