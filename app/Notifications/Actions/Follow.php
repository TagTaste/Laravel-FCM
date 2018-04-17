<?php


namespace App\Notifications\Actions;

use App\Notifications\Action;
use App\Profile;
use Carbon\Carbon;

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

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $data = [
            'action' => $this->data->action,
            'profile' => isset(request()->user()->profile) ? request()->user()->profile : $this->data->who,
            'notification' => $this->notification,
            'isFollowing' => Profile::isFollowing($notifiable->id, $this->data->who['id']),
        ];

        if(method_exists($this->model,'getNotificationContent')){
            $data['model'] = $this->allData;
        } else {
            \Log::warning(class_basename($this->modelName) . " doesn't specify notification content.");
            $data['model'] = [
                'name' => $this->modelName,
                'id' => $this->data->model->id,
                'content' => $this->data->content,
                'image' => $this->data->image
            ];
        }

        $data['created_at'] = Carbon::now()->toDateTimeString();

        return $data;
    }
}