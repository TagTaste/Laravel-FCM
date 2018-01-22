<?php

namespace App\Notifications;

use App\FCMPush;
use App\Traits\GetTags;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Action extends Notification implements ShouldQueue
{
    use GetTags, Queueable;
    
    public $data;
    public $model;
    public $modelId;
    public $action;
    public $content;
    public $image;
    public $modelName;
    public $allData ;
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
        if(method_exists($this->model,'getNotificationContent')){
            $this->allData = $this->model->getNotificationContent();
        }

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

        if($this->view && view()->exists($this->view)){
            $via[] = 'mail';

        }
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
        if(view()->exists($this->view)){
            return (new MailMessage())->subject($this->sub)->view(
                $this->view, ['data' => $this->data,'model'=>$this->allData,'notifiable'=>$notifiable,'content'=>$this->getContent($this->allData['content'])]
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
            $data['model'] = $this->allData;
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

    public function getContent($text)
    {
        if(isset($text['text']))
        {
            $profiles = $this->getTaggedProfiles($text['text']);
            $pattern = [];
            $replacement = [];
            foreach ($profiles as $index => $profile)
            {
                $pattern[] = '/\@\['.$profile->id.'\:'.$index.'\]/i';
                $replacement[] = $profile->name;
            }
            $replacement = array_reverse($replacement);
            return preg_replace($pattern,$replacement,$text['text']);

        }
        elseif($text != '')
        {
            $profiles = $this->getTaggedProfiles($text);
            $pattern = [];
            $replacement = [];
            if($profiles == false) {
                return $text;
            }
            foreach ($profiles as $index => $profile)
            {
                $pattern[] = '/\@\['.$profile->id.'\:'.$index.'\]/i';
                $replacement[] = $profile->name;
            }
            $replacement = array_reverse($replacement);
            return preg_replace($pattern,$replacement,$text);
        }
        else
        {
            return "";
        }
    }

}
