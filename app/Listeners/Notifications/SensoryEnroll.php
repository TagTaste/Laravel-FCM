<?php

namespace App\Listeners\Notifications;

use App\Events\Actions\SensoryEnroll as ActionsSensoryEnroll;
use App\Notify\Profile;
use Illuminate\Support\Facades\Notification;

class SensoryEnroll
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
    public function handle(ActionsSensoryEnroll $event)
    {
        $profileId = $event->model->id;
        $profile = Profile::find($profileId);
        if(isset($profile))
        Notification::send($profile, new \App\Notifications\Actions\SensoryEnroll($event));
    }
}
