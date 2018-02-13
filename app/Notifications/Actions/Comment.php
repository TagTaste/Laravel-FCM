<?php


namespace App\Notifications\Actions;

use App\FCMPush;
use App\Notifications\Action;

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
        if($notifiable->id != $this->model->profile_id) {
            $this->sub = $this->data->who['name'] ." commented on a post on which you have commented";
        }

        if(view()->exists($this->view)){
            return (new MailMessage())->subject($this->sub)->view(
                $this->view, ['data' => $this->data,'model'=>$this->allData,'notifiable'=>$notifiable,'content'=>$this->getContent($this->allData['content'])]
            );
        }
    }
}