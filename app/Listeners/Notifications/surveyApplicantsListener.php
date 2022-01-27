<?php

namespace App\Listeners\Notifications;

use App\CompanyUser;
use App\Events\Actions\surveyApplicantEvents;
use App\Notify\Profile;
use Illuminate\Support\Facades\Notification;

class surveyApplicantsListener
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
    public function handle(surveyApplicantEvents $event)
    {
        
        $profileId = $event->who["id"];
        $profile = Profile::find($profileId);
        dd($event->who);
        if(isset($profile))
        Notification::send($profile, new \App\Notifications\Actions\surveyApplicantsNotifications($event));
    }
}
