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
        $id = (isset($event->model->company_id) && !empty($event->model->company_id) ?  $event->model->profile_id : null);
        $profileId = ($id==null) ? $event->who["id"] : $id;
        $profile = Profile::find($profileId);
        if(isset($profile))
        Notification::send($profile, new \App\Notifications\Actions\surveyApplicantsNotifications($event));
    }
}
