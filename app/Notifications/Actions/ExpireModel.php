<?php


namespace App\Notifications\Actions;

use App\Notifications\Action;

class ExpireModel extends Action
{
    public $view = null;
    public $sub = 'Notification from Tagtaste';
    public $notification ;

    public function __construct($event)
    {
        parent::__construct($event);
        $this->notification ="Your ".$this->modelName." ".$this->model->title." has expired.";
    }
}