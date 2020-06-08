<?php

namespace App\Listeners\Chat;

use App\Events\DocSubmissionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notify\Profile;
use App\Notifications\DocumentSubmission;
use Notification;

class DocSubmissionListener implements ShouldQueue
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
     * @param  DocSubmissionEvent  $event
     * @return void
     */
    public function handle(DocSubmissionEvent $event)
    {
        $profile = Profile::where('id',$event->profileId)->get();
        Notification::send($profile, new DocumentSubmission($event));
    }
}
