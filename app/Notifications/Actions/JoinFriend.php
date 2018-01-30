<?php


namespace App\Notifications\Actions;

use App\Notifications\Action;

class JoinFriend extends Action
{
    public $view;
    public $sub;
    public $notification;

    public function __construct($event)
    {
        parent::__construct($event);
        $this->view = 'emails.'.$this->data->action;
        $this->sub = "Invitation Accepted";
        $this->notification = $this->data->who['name'] ." is now on TagTaste.";
    }
}