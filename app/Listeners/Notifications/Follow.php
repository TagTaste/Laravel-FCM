<?php

namespace App\Listeners\Notifications;

use App\Events\Actions\Follow as FollowEvent;
use App\Notify\Profile;
use Illuminate\Support\Facades\Notification;

class Follow
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
    public function handle(FollowEvent $event)
    {
        $profileId = $event->model->id;
        if(!$profileId){
            \Log::warning(get_class($event->model) . " doesn't have profile defined. Can't send notification.");
            return;
        }
        $profile = Profile::find($profileId);
        if(isset($profile))
        Notification::send($profile, new \App\Notifications\Actions\Follow($event));
    }
}
