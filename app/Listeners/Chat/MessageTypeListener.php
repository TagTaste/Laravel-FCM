<?php

namespace App\Listeners\Chat;

use App\Events\Chat\MessageTypeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageTypeListener
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
     * @param  MessageTypeEvent  $event
     * @return void
     */
    public function handle(MessageTypeEvent $event)
    {   
        $model = \App\V1\Chat\Message::create($event->info);
        $chatId = $event->info["chat_id"];
        $membersOfChat = \App\V1\Chat\Member::withTrashed()->where('chat_id',$chatId)->whereNull('exited_on')->pluck('profile_id');
        $recepient = [];
        foreach ($membersOfChat as $member) {
            $recepient[] = ['chat_id'=>$chatId, 'message_id'=>$model->id, 'recepient_id'=>$member, 'sent_on'=>$model->created_at, 'read_on'=>$model->created_at];
        }
        \DB::table('message_recepients')->insert($recepient);
    }
}
