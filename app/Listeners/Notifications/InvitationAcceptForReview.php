<?php

namespace App\Listeners\Notifications;

use App\CompanyUser;
use App\Events\Actions\InvitationAcceptForReview as InvitationAcceptForReviewEvent;
use App\Notify\Profile;
use Illuminate\Support\Facades\Notification;

class InvitationAcceptForReview
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
    public function handle(InvitationAcceptForReviewEvent $event)
    {
        $profileId = $event->model->profile_id;
        $profile = Profile::find($profileId);
        if(isset($profile))
        Notification::send($profile, new \App\Notifications\Actions\InvitationAcceptForReview($event));
    }
}
