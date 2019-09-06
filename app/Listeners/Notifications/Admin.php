<?php

namespace App\Listeners\Notifications;

use App\Events\Actions\Admin as AdminEvent;
use App\Notify\Profile;
use Illuminate\Support\Facades\Notification;

class Admin
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
    public function handle(AdminEvent $event)
    {
        $userId = $event->model->user_id;
        if(!$userId){
            \Log::warning(get_class($event->model) . " doesn't have profile defined. Can't send notification.");
            return;
        }
        $profile = Profile::where('user_id',$userId)->first();
        $event->content = $event->model->name;
        Notification::send($profile, new \App\Notifications\Actions\Admin($event));
    }
}
