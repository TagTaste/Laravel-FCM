<?php

namespace App\Listeners;


use App\Events\Update as UpdateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use \App\Update;


class UpdateNotification
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
     * @param  UpdateFeedable  $event
     * @return void
     */
    public function handle(UpdateEvent $event)
    {
        \App\Update()::create(['model_id'=>$event->modelId,'model_name'=>$event->modelName,'profile_id'=>$event->profileId,'content'=>$event->content]);
    }
}
