<?php

namespace App\Listeners;

use App\Events\NewFeedable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PushNewFeedable
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
     * @param  NewFeedable  $event
     * @return void
     */
    public function handle(NewFeedable $event)
    {
        if(!method_exists($event->model,'owner')){
            throw new \Exception("Owner not defined on Feedable " . class_basename($event->model));
        }
    
        $event->model->owner()->pushToMyFeed($event->model);
    
        if(!method_exists($event->model,'privacy') || is_null($event->model->privacy)){
            //if Privacy is not defined on the model,
            //don't throw an Exception.
        
            //Don't push it to his network or public feed.
            \Log::warning("Privacy not defined for Feedable " . class_basename($event->model));
            \Log::warning("Not publishing it to network or public feed.");
            return;
        }
    
        if($event->model->privacy->isNetwork() || $event->model->privacy->isPublic()){
            $event->model->owner()->pushToNetwork($event->model);
        }
    
        if($event->model->privacy->isPublic()){
            $event->model->owner()->pushToPublic($event->model);
        }
    }
}
