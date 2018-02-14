<?php

namespace App\Notifications\Actions;

use App\Deeplink;
use App\Notifications\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Tag extends Action
{
    public $view;
    public $sub;
    public $notification;

    public function __construct($event)
    {
        parent::__construct($event);
        $this->view = 'emails.'.$this->data->action;
        $this->sub = $this->data->who['name'] ." mentioned you in a post";
        $this->notification = $this->data->who['name'] . " tagged you in a post.";
    }

    public function toMail($notifiable)
    {
        $langKey = $this->data->action;

        $langKey = isset($this->data->actionModel) ? $langKey.':'.strtolower(class_basename($this->data->actionModel)) : $langKey.':'.$this->modelName;

//        \Log::info("------------------------------\n\t".strtolower(class_basename($this->event->actionModel))."\t$langKey");

        if(isset($this->allData['shared']) && $this->allData['shared'] == true) {
            $this->allData['url'] = Deeplink::getLongLink($this->modelName, $this->allData['id'], true, $this->allData['share_id']);
        } else {
            $this->allData['url'] = Deeplink::getLongLink($this->modelName, $this->allData['id']);
        }

        $langKey = $langKey.':title';
        $this->sub = __('mails.'.$langKey, ['name' => $this->data->who['name']]);
        $this->allData['title'] = $this->sub;

        if(view()->exists($this->view)){
            return (new MailMessage())->subject($this->sub)->view(
                $this->view, ['data' => $this->data,'model'=>$this->allData,'notifiable'=>$notifiable,'content'=>$this->getContent($this->allData['content'])]
            );
        }
    }
}
