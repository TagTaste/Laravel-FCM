<?php

namespace App\Subscribers;


use App\Events\Actions\Comment;
use App\Events\Actions\Like;
use App\ModelSubscriber;
use App\Notify\Profile;
use Illuminate\Support\Facades\Notification;

class Actions
{
    public function addOrUpdateSubscriber($event)
    {
        ModelSubscriber::updateSubscriberTimestamp($event->model,$event->model->id,$event->who['id']);
    }
    
    public function notifySubscribers($event)
    {
        $modelId = $event->model->id;
        $model = get_class($event->model);
        $profiles = Profile::select('profiles.*')->join('model_subscribers','model_subscribers.profile_id','=','profiles.id')
            ->where('model_subscribers.model','=',$model)
            ->where('model_subscribers.model_id','=',$modelId)
            ->where('model_subscribers.profile_id','!=',$event->who['id'])
            ->whereNull('muted_on')
            ->whereNull('model_subscribers.deleted_at')->get();
        //send notification
        if($profiles->count() === 0) {
            \Log::info("No model subscribers. Not sending notification.");
            return;
        }
        $class = "\App\Notifications\Actions\\" . ucwords($event->action);
        Notification::send($profiles, new $class($event));
    }
    
    public function subscribe($events)
    {
        $events->listen(
            [
                Like::class,
                Comment::class
            ],
            'App\Subscribers\Actions@notifySubscribers');
        
        $events->listen(
            [
//                Like::class, //Liking doesn't make you a subscriber.
                Comment::class
            ],
            'App\Subscribers\Actions@addOrUpdateSubscriber');
    }
}