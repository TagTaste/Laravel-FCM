<?php

namespace App\Listeners\Notifications;

use App\Events\Actions\ReviewComment as ReviewCommentEvent;
use App\Notify\Profile;
use Illuminate\Support\Facades\Notification;

class ReviewComment
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
    public function handle(ReviewCommentEvent $event)
    {
        $profileId = $event->model->profile_id;
        if(!$profileId){
            \Log::warning(get_class($event->model) . " doesn't have profile defined. Can't send notification.");
            return;
        }
        $profile = Profile::where('id',$profileId)->first();
        if(isset($profile))
        Notification::send($profile, new \App\Notifications\Actions\ReviewComment($event));
    }
}
