<?php

namespace App\Notifications;

use App\FCMPush;
use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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
        $via = ['database',FCMPush::class,'broadcast'];
        
        $view = null;
        if($this->data->action == 'apply' || $this->data->action == 'tag' || $this->data->action == 'comment')
        {
            if($this->data->action == 'apply')
            {
                $view = 'emails.'.$this->data->action.'-'.$this->modelName;
            }
            else
            {
                $view = $this->data->action;
            }
        }

        if($view && view()->exists($view)){
            $via[] = 'mail';

        }
        \Log::info($via);
        return $via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $view = null;
        if($this->data->action == 'apply' || $this->data->action == 'tag' || $this->data->action == 'comment')
        {
            if($this->data->action == 'apply')
            {
                $view = 'emails.'.$this->data->action.'-'.$this->modelName;
            }
            else{
                $view = $this->data->action;
            }
        }

        if(view()->exists($view)){
            return (new MailMessage())->view(
                $view, ['data' => $this->data,'model'=>$this->model,'notifiable'=>$notifiable]
            );
        }
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

        return $data;
    }
}
