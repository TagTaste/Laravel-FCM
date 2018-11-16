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
        $model = \App\Chat\Message::create($event->info);
        $chatId = $event->info["chat_id"];
        $membersOfChat = \App\Chat\Member::where('chat_id',$chatId)->pluck('profile_id');
        foreach ($membersOfChat as $member) {
            \DB::table('message_recepients')->insert(['chat_id'=>$chatId, 'message_id'=>$model->id, 'recepient_id'=>$member, 'sent_on'=>$model->created_at, 'read_on'=>$model->created_at]);
        }
    }
}
