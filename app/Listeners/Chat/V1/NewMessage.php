<?php

namespace App\Listeners\Chat\V1;

use App\V1\Chat\Member;
use App\Events\Chat\V1\Message;
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
        $profiles = Profile::select('profiles.*')->join('chat_members','profiles.id','=','chat_members.profile_id')
                    ->where('chat_id',$event->chatId)
                    ->where('profile_id','!=',$event->profile->id)->whereNull('chat_members.exited_on')->get();
        
        file_put_contents(storage_path("logs") . "/nikhil_socket_test.txt", "\nHere in Newmeesage listener to send notification for message id : ".$event->id." to profile id : ".$profiles."\n", FILE_APPEND); 
        if($profiles->count() == 0){
            file_put_contents(storage_path("logs") . "/nikhil_socket_test.txt", "\n Profile count 0 so return\n", FILE_APPEND); 
            return;
        }
        file_put_contents(storage_path("logs") . "/nikhil_socket_test.txt", "\n Moving forward to Send notification to profiles.\n", FILE_APPEND); 
        Notification::send($profiles, new ChatMessage($event));
    }
}
