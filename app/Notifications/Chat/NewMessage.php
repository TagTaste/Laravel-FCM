<?php

namespace App\Notifications\Chat;

use App\Chat;
use App\FCMPush;
use App\Recipe\Profile;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewMessage extends Notification
{
    //use Queueable;

    public $data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($event)
    {
        $this->data = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = [];
        if(isset($this->data->headerAction) && !empty($this->data->headerAction))
            $via = ['broadcast'];
        else
            $via = [FCMPush::class,'broadcast'];
        return $via;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $data = [
            'action' => 'chat',
            'profile' => isset(request()->user()->profile) ? request()->user()->profile : \App\Chat\Profile::where($this->data->profile->id)->first(),
        ];
        $chat = \DB::table('chats')->where('id',$this->data->chatId)->first();
        $data['model'] = [
            'name' => $chat->name,
            'id' => $this->data->chatId,
            'imageUrl' => null,
            'message'=>['id' => $this->data->id,'image'=>$this->data->image,'content'=>$this->data->message],
            'is_enabled'=>true,
            'messageType' => isset($this->data->message) && !empty($this->data->message) ? null : 'media',
            'headerAction' => $this->data->headerAction
            ];

        $data['created_at'] = Carbon::now()->toDateTimeString();
        if(isset($chat->name)&&!empty($chat->name))
        {   
            if(isset($this->data->message))
            {
                $notification = request()->user()->name ." messaged you ".$this->data->message." on ".$chat->name." group";
            }
            else
            {
                $notification = request()->user()->name ." messaged you on ".$chat->name." group";

            }
        }
        else{
            if(isset($this->data->message))
            {
                $notification = request()->user()->name ." messaged you ".$this->data->message;
            }
            else
            {
                $notification = request()->user()->name ." messaged you";

            }
        }
        $data['notification'] = $notification;
        return $data;
    }
}
