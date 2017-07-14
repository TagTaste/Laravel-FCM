<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Action extends Notification
{
    //use Queueable;
    
    public $data;
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
//    public function __construct($model, $modelId, $content = null, $image = null, $action = null)
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
            'action' => $this->data->action,
            'profile' => $this->data->
        ];
        
        try {
            $data['model'] = $this->data->model->getNotificationContent();
        } catch (\Exception $e){
            \Log::info($e->getMessage());
        }
        
        if(!isset($data['model'])){
            $data['model'] = [
                'name' => strtolower(class_basename($this->data->model)),
                'id' => $this->data->model->id,
                'content' => $this->data->content,
                'image' => $this->data->image
            ];
        }
        $data['created_at'] = Carbon::now()->toDateTimeString();
        return $data;
    }
}
