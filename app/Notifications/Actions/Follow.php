<?php


namespace App\Notifications\Actions;

use App\Notifications\Action;

class Follow extends Action
{
    public $view;
    public $sub;
    public $notification;

    public function __construct($event)
    {
        parent::__construct($event);
        $this->view = 'emails.'.$this->data->action;
        $this->sub = "Yay! You have a new follower.";
        if(method_exists($this->model,'getNotificationContent')){
            $this->allData = $this->model->getNotificationContent($this->data->action);
        }
        $this->notification = $this->data->who['name'] ." has started following you.";
    }
}