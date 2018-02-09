<?php


namespace App\Notifications\Actions;

use App\Notifications\Action;

class Like extends Action
{
    public $view = null;
    public $sub = 'Notification from Tagtaste';
    public $notification ;

    public function __construct($event)
    {
        parent::__construct($event);
        $this->notification = $this->data->who['name'] . " liked a post.";
    }

    // Overriding this function to prevent self like notification
    public function via($notifiable)
    {
        if($this->data->who['id'] == $notifiable->id) {
            return [];
        }

        $via = ['database',FCMPush::class,'broadcast'];

        if($this->view && view()->exists($this->view)){
            $via[] = 'mail';

        }
        return $via;
    }
}