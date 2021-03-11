<?php

namespace App\Listeners\Notifications;

use App\CompanyUser;
use App\Events\Actions\SurveyAnswered as SurveyAnsweredEvent;
use App\Notify\Profile;
use Illuminate\Support\Facades\Notification;

class SurveyAnswered
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
    public function handle(SurveyAnsweredEvent $event)
    {
        
        $profileId = $event->model->profile_id;
        $profile = Profile::find($profileId);
        if(isset($profile))
        Notification::send($profile, new \App\Notifications\Actions\SurveyAnswered($event));
    }
}
