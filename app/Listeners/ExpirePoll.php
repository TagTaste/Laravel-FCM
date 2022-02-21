<?php

namespace App\Listeners;

use App\Notify\Profile;
use App\Events\ExpirePoll as ExpirePollEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ExpiryPoll;

class ExpirePoll
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
     * @param  ExpirePoll  $event
     * @return void
     */
    public function handle(ExpirePollEvent $event)
    {
        \Log::info("notifying");
        $profiles =  Profile::select('profiles.*')->join('poll_votes', 'poll_votes.profile_id', '=', 'profiles.id')
            ->where('poll_votes.poll_id', $event->model->id)->get();
        $admin = Profile::where('id', $event->model->profile_id)->get();
        $profiles->push($admin);
        foreach ($profiles as $profile) {
            Notification::send($profile, new ExpiryPoll($event));
        }
    }
}
