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
}