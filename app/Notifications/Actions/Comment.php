<?php


namespace App\Notifications\Actions;

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
}