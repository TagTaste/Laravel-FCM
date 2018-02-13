<?php


namespace App\Notifications\Actions;

use App\Deeplink;
use App\FCMPush;
use App\Notifications\Action;
use Illuminate\Notifications\Messages\MailMessage;

class Comment extends Action
{
    public $view;
    public $sub;
    public $notification;

    public function __construct($event)
    {
        parent::__construct($event);
        $this->view = 'emails.'.$this->data->action;
        $this->sub = $this->data->who['name'] ." commented on your post";
        $this->notification = $this->sub;

    }

    public function toMail($notifiable)
    {
        $langKey = $this->data->action.':'.$this->modelName;

        // owner or subscriber
        $notifiable->id == $this->model->profile_id ? $langKey = $langKey.':owner' : $langKey = $langKey.':subscriber';

        if(isset($this->allData['shared']) && $this->allData['shared'] == true) {
            $this->allData['url'] = Deeplink::getLongLink($this->modelName, $this->allData['id'], true, $this->allData['share_id']);
            $langKey = $langKey.':shared';
        } else {
            $this->allData['url'] = Deeplink::getLongLink($this->modelName, $this->allData['id']);
            $langKey = $langKey.':original';
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