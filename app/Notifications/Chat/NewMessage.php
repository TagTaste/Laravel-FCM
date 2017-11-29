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
        return ['database',FCMPush::class,'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $profile = request()->user()->profile;
        $data = [
            'action' => 'chat',
            'profile' =>$profile
        ];
        $profileId = $profile->id;
        $chat = Chat::with([])->where('id',$this->data->chatId)->first();
        $data['model'] = [
            'name' => 'chat',
            'id' => $this->data->chatId,
            'content' => $this->data->message,
            'image' => $this->data->image,
            'data' => $chat
            ];

        $data['created_at'] = Carbon::now()->toDateTimeString();

        return $data;
    }
}