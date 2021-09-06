<?php

namespace App\Listeners\Notifications;

use App\Events\Actions\ExpireModel as ExpireModelEvent;
use App\Notify\Profile;
use App\Payment\PaymentDetails;
use Illuminate\Support\Facades\Notification;

class ExpireModel
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
    public function handle(ExpireModelEvent $event)
    {
        $profileId = $event->model->profile_id;
        if(!$profileId){
            \Log::warning(get_class($event->model) . " doesn't have profile defined. Can't send notification.");
            return;
        }
        PaymentDetails::where('model_id', $event->model->id)->update(['is_active' => 0]);
        $profile = Profile::find($profileId);
        if(isset($profile))
        Notification::send($profile, new \App\Notifications\Actions\ExpireModel($event));
    }
}
