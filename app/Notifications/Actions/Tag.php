<?php

namespace App\Notifications\Actions;

use App\Notifications\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Tag extends Action
{
    public $view;
    public $sub;

    public function __construct($event)
    {
        parent::__construct($event);
        $this->view = 'emails.'.$this->data->action;
        $this->sub = $this->data->who['name'] ." mentioned you in a post";
    }
}
