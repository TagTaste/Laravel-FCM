<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FeatureMessage extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $info;
    public $profiles;
    public function __construct($data,$profiles)
    {
        //
        $this->info = $data;
        $this->profiles = $profiles;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $button_link = env('APP_URL').'/'.$this->info['model_name'].'/'.$this->info['model_id'];
        return (new MailMessage())
                    ->subject($this->info['sender_info']->name." sent you a new message on TagTaste!")
                    ->view('emails.collab-message', ["name"=>$this->profiles->name,"username"=>$this->info['username'],"message1"=>$this->info['message'],
                        "model_name"=>$this->info['model_name'], "model_title"=>$this->info['model_title'], "button_link"=>$button_link]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
