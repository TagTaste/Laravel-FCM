<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\FCMPush;
use App\User;

class Action extends Notification
{
    //use Queueable;
    
    public $data;
    public $model;
    public $modelId;
    public $action;
    public $content;
    public $image;
    public $modelName;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
//    public function __construct($model, $modelId, $content = null, $image = null, $action = null)
    public function __construct($event)
    {
        $this->data = $event;
        $this->model = $event->model;
        $this->modelName = strtolower(class_basename($event->model));
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
            'profile' => $this->data->who
        ];

        if(method_exists($this->model,'getNotificationContent')){
            $data['model'] = $this->model->getNotificationContent();
        } else {
            \Log::warning(class_basename($this->modelName) . " doesn't specify notification content.");
            $data['model'] = [
                'name' => $this->modelName,
                'id' => $this->data->model->id,
                'content' => $this->data->content,
                'image' => $this->data->image
            ];
        }

        $data['created_at'] = Carbon::now()->toDateTimeString();
        if(\Redis::SISMEMBER("disconnected:profile",$this->data->model->id))
        {
            $fcm = new FCMPush();
            $fcm->fcmNotification($data,$this->data->model->id);
        }

        return $data;
    }
}
