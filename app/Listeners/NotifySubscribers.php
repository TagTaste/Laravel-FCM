<?php

namespace App\Listeners;

use App\Events\Action;
use App\ModelSubscriber;
use App\Notify\Profile;
use \Illuminate\Support\Facades\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySubscribers
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
     * @param  Action  $event
     * @return void
     */
    public function handle(Action $event)
    {
        $modelId = $event->model->id;
        $model = $event->getModelName();
        
        $profiles = Profile::select('profiles.*')->join('model_subscribers','model_subscribers.profile_id','=','profiles.id')
                    ->where('model_subscribers.model','like',$model)
                    ->where('model_subscribers.model_id','=',$modelId)
                    ->whereNull('muted_on')
                    ->whereNull('model_subscribers.deleted_at')->get();
        
        Notification::send($profiles, new \App\Notifications\Action($model,$modelId,$event->action));
    }
}
