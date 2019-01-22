<?php


namespace App\Notifications\Actions;

use App\Deeplink;
use App\Notifications\Action;
use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Crypt;

class ReviewComment extends Action
{
//    use HasPreviewContent;
    public $view;
    public $sub;
    public $notification;

    public function __construct($event)
    {
        \Log::info("here is sub ");

        parent::__construct($event);
        $this->view = 'emails.'.$this->data->action;
        $this->sub = $this->data->who['name'] ." commented on your review";
        if(method_exists($this->model,'getNotificationContent')){
            $this->allData = $this->model->getNotificationContent();
            $this->sub = $this->data->who['name'] ." commented on your review ".$this->allData['title'];

        }
        $this->notification = $this->sub;
        \Log::info("here is sub ".$this->sub);

    }

    public function toMail($notifiable)
    {

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
            'actionModel' => $this->data->actionModel,
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