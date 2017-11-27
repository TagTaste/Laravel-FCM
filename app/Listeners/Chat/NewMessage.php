<?php

namespace App\Listeners\Chat;

use App\Chat\Member;
use App\Events\Chat\Message;
use App\Notifications\Chat\NewMessage as ChatMessage;
use App\Notify\Profile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NewMessage
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
     * @param  Message  $event
     * @return void
     */
    public function handle(Message $event)
    {
        $profiles = Profile::join('chat_members','chat_members.profile_id','=','profiles.id')
                    ->where('chat_id',$event->chatId)
                    ->where('profile_id','!=',$event->profile->id)->whereNull('chat_members.exited_on')->get();
        
        if($profiles->count() == 0){
            return;
        }
        \Log::info($profiles);
        Notification::send($profiles, new ChatMessage($event));
    }
}
