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
        $receiverId = is_null($event->receiver) ? null : $event->receiver->id;
        $model=\App\Chat\Message::create(['message'=>$receiverId, 'chat_id'=>$event->info['chatId'], 'profile_id'=>$event->sender, 'type'=>$event->info['type']]);
        $members = \App\Chat\Member::where('chat_id',$event->info['chatId'])->pluck('profile_id');
        foreach ($members as $member) {
            \DB::table('chat_message_recepients')->insert(['message_id'=>$model->id, 'recepient_id'=>$member, 'chat_id'=>$event->info['chatId']]);
        }

    }
}
