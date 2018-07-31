<?php

namespace App\Listeners\Notifications;

use App\CompanyUser;
use App\Events\Actions\BeginTasting as BeginTastingEvent;
use App\Notify\Profile;
use Illuminate\Support\Facades\Notification;

class BeginTasting
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
    public function handle(BeginTastingEvent $event)
    {
        $profileId = $event->model->profile_id;
        $profile = Profile::find($profileId);
        Notification::send($profile, new \App\Notifications\Actions\BeginTasting($event));
    }
}
