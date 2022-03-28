<?php

namespace App\Listeners;

use App\Events\CollaborationReportUpload as CollaborationReportUploadEvent;

use App\Notifications\CollaborationReportUpload as CollaborateReportNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;
use Monolog\Formatter\NormalizerFormatter;
use Illuminate\Support\Facades\Notification;

use App\Notify\Profile;


class CollaborationReportUpload 
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
    public function handle(CollaborationReportUploadEvent $event)
    {
        $profileId = $event->collaborate->profile_id;
        $profile = Profile::find($profileId);
        $event->profile = $profile;
        if(isset($profile))
            Notification::send($profile, new CollaborateReportNotification($event));
    }
}
