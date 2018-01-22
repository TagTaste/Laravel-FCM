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
        $view = null;
        if($this->data->action == 'apply' || $this->data->action == 'tag' || $this->data->action == 'comment' || $this->data->action == 'follow' || $this->data->action == 'joinfriend')
        {
            if($this->data->action == 'apply')
            {
                $view = 'emails.'.$this->data->action.'-'.$this->modelName;
            }
            else
            {
                $view = 'emails.'.$this->data->action;
            }
        }
        if($view && view()->exists($view)){
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
        $view = null;
        $sub = 'Notification from Tagtaste';

        if($this->data->action == 'apply')
        {
            $view = 'emails.'.$this->data->action.'-'.$this->modelName;
            if($this->modelName == 'collaborate')
            {
                $sub = $this->data->who['name'] ." wants to collaborate with you on ".$this->model->title;
                if(!is_null($this->data->content)) {
                    $this->allData['message'] = $this->data->content;
                }
            }
            else
            {
                $sub = $this->data->who['name'] ." applied to your job : ".$this->model->title;

            }
        }
        elseif ($this->data->action == 'tag')
        {
            $view = 'emails.'.$this->data->action;
            $sub = $this->data->who['name'] ." mentioned you in a post";
        }
        elseif ($this->data->action == 'comment')
        {
            $view = 'emails.'.$this->data->action;
            $sub = $this->data->who['name'] ." commented on your post";

        }
        elseif( $this->data->action == 'joinfriend')
        {
            $view = 'emails.' . $this->data->action;
            $sub = "Invitation Accepted";
        }

        elseif ($this->data->action == 'follow')
        {
            $view = 'emails.'.$this->data->action;
            $sub = "Yay! You have a new follower.";
            if(method_exists($this->model,'getNotificationContent')){
                $this->allData = $this->model->getNotificationContent($this->data->action);
            }

        }

        if(view()->exists($view)){
            return (new MailMessage())->subject($sub)->view(
                $view, ['data' => $this->data,'model'=>$this->allData,'notifiable'=>$notifiable,'content'=>$this->getContent($this->allData['content'])]
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
