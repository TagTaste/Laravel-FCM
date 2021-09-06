<?php

namespace App\Listeners\Notifications;

use App\Events\Actions\TasterEnroll as ActionsTasterEnroll;
use App\Notify\Profile;
use Illuminate\Support\Facades\Notification;

class TasterEnroll
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
    public function handle(ActionsTasterEnroll $event)
    {
        $profileId = $event->model->id;
        $profile = Profile::find($profileId);
        if(isset($profile))
        Notification::send($profile, new \App\Notifications\Actions\TastingEnroll($event));
    }
}
