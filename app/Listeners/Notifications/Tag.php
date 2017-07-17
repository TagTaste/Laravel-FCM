<?php

namespace App\Listeners\Notifications;

use App\Events\Actions\Tag as TaggedEvent;
use App\Notify\Profile;
use App\Traits\GetTags;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class Tag
{
    use GetTags;
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
     * @param  Tag  $event
     * @return void
     */
    public function handle(TaggedEvent $event)
    {
        $profiles = $event->model->content['profiles'];
        
        if(!$profiles){
            return;
        }
        $profiles = collect($profiles);
        $profiles = Profile::whereIn('id',$profiles->pluck('id'))->get();

        if($profiles->count() === 0){
            return;
        }
        
        Notification::send($profiles, new \App\Notifications\Actions\Tag($event));
        
    }
}
