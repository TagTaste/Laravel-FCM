<?php


namespace App\Notifications\Actions;

use App\Notifications\Action;
use Carbon\Carbon;

class Expire extends Action
{
    public $view = null;
    public $sub = 'Notification from Tagtaste';
    public $notification ;

    public function __construct($event)
    {
        parent::__construct($event);

        $expire_on = strtotime($event->model->expires_on);

        if($event->model->expires_on >= Carbon::now()->addDays(1)->toDateTimeString() && $event->model->expires_on <= Carbon::now()->addDays(1)->toDateTimeString())
        {
            $this->notification ="Your ".$this->modelName." ".$this->model->title." will expire in 2 days.";
        }
        else if($event->model->expires_on >= Carbon::now()->toDateTimeString() && $event->model->expires_on <= Carbon::now()->addDays(1)->toDateTimeString())
        {
            $this->notification ="Your ".$this->modelName." ".$this->model->title." will expire in 1 days.";
        }
        else if($event->model->expires_on >= Carbon::now()->addDays(7)->toDateTimeString() && $event->model->expires_on <= Carbon::now()->addDays(8)->toDateTimeString())
        {
            $this->notification ="Your ".$this->modelName." ".$this->model->title." will expire in 8 days.";
        }
        else
        {
            $this->notification ="Your ".$this->modelName." ".$this->model->title." will expire soon.";
        }
        \Log::info($this->notification);
    }
}