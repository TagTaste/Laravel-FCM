<?php


namespace App\Notifications\Actions;

use App\Deeplink;
use App\FCMPush;
use App\Notifications\Action;
use App\Traits\GetTags;
use App\Traits\HasPreviewContent;
use Illuminate\Notifications\Messages\MailMessage;

class Comment extends Action
{
//    use HasPreviewContent;
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
        $langKey = $notifiable->id == $this->model->profile_id ? $langKey.':owner' : $langKey.':subscriber';

        if(isset($this->allData['shared']) && $this->allData['shared'] == true) {
            $this->allData['url'] = Deeplink::getShortLink($this->modelName, $this->allData['id'], true, $this->allData['share_id']);
            $langKey = $langKey.':shared';
        } else {
            $this->allData['url'] = Deeplink::getShortLink($this->modelName, $this->allData['id']);
            $langKey = $langKey.':original';
        }

        $langKey = $langKey.':title';
        $this->sub = __('mails.'.$langKey, ['name' => $this->data->who['name']]);
        $this->allData['title'] = $this->sub;
        if(view()->exists($this->view)){
            return (new MailMessage())->subject($this->sub)->view(
                $this->view, ['data' => $this->data,'model'=>$this->allData,'notifiable'=>$notifiable, 'comment'=> $this->getContent($this->data->content), 'content'=>$this->getContent($this->allData['content'])]
            );
        }
    }
}