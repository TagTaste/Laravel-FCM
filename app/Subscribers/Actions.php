<?php

namespace App\Subscribers;


use App\Events\Actions\Comment;
use App\Events\Actions\Like;
use App\Events\Actions\Share;
use App\ModelSubscriber;
use App\Notify\Profile;
use Illuminate\Support\Facades\Notification;

class Actions
{
    public function notifySubscribers($event)
    {
        ModelSubscriber::updateSubscriberTimestamp($event->getModelName(),$event->model->id,$event->who->id);
    }
    
    public function addOrUpdateSubscriber($event)
    {
        $modelId = $event->model->id;
        $model = $event->getModelName();
    
        $profiles = Profile::select('profiles.*')->join('model_subscribers','model_subscribers.profile_id','=','profiles.id')
            ->where('model_subscribers.model','like',$model)
            ->where('model_subscribers.model_id','=',$modelId)
            ->where('model_subscribers.profile_id','!=',$event->who->id)
            ->whereNull('muted_on')
            ->whereNull('model_subscribers.deleted_at')->get();
        
        //send notification
        if($profiles->count()) {
            $class = "\App\Notifications\Actions\\" . ucwords($event->action);
            Notification::send($profiles, new $class($event));
        }
    }
    
    public function subscribe($events)
    {
        $events->listen(
            [Like::class,Share::class,Comment::class],
            'App\Subscribers\Actions@notifySubscribers');
        
        $events->listen(
            [Like::class,Share::class,Comment::class],
            'App\Subscribers\Actions@addOrUpdateSubscriber');
    }
}