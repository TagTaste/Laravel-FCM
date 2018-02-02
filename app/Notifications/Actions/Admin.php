<?php


namespace App\Notifications\Actions;

use App\Notifications\Action;

class Admin extends Action
{
    public $view = null;
    public $sub = 'Notification from Tagtaste';
    public $notification ;

    public function __construct($event)
    {
        parent::__construct($event);
        $this->notification =$this->data->who['name'] . " has made you an admin of ".$this->data->content.".";
    }
}