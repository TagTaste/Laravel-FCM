<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Action extends Notification
{
    //use Queueable;
    
    public $model;
    public $modelId;
    public $action;
    public $content;
    public $image;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($model, $modelId, $content = null, $image = null, $action = null)
    {
        $this->model = $model;
        $this->modelId = $modelId;
        $this->action = $action;
        $this->content = $content;
        $this->image = $image;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
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
            'action'=>$this->action,
            'model' => ['name'=>$this->model,'id'=>$this->modelId,'content'=>$this->content,'image'=>$this->image],
            'profile' => $notifiable
        ];
        
        return $data;
    }
    
    protected function appendAttributes(&$data){
    
    }
}
