<?php

namespace App\Listeners\Notifications;

use App\CompanyUser;
use App\Events\Actions\RollbackTaster as rollbackTasterEvent;
use App\Notify\Profile;
use Illuminate\Support\Facades\Notification;

class RollbackTaster
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
    public function handle(rollbackTasterEvent $event)
    {
        $profileId = $event->model->profile_id;
        $profile = Profile::find($profileId);
        if(isset($profile))
        Notification::send($profile, new \App\Notifications\Actions\RollbackTaster($event));
    }
}
