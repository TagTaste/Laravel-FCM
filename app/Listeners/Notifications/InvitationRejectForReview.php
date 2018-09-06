<?php

namespace App\Listeners\Notifications;

use App\CompanyUser;
use App\Events\Actions\InvitationRejectForReview as InvitationRejectForReviewEvent;
use App\Notify\Profile;
use Illuminate\Support\Facades\Notification;

class InvitationRejectForReview
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
    public function handle(InvitationRejectForReviewEvent $event)
    {
        $profileId = $event->model->profile_id;
        $profile = Profile::find($profileId);
        Notification::send($profile, new \App\Notifications\Actions\InvitationRejectForReview($event));
    }
}
