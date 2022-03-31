<?php


namespace App\Notifications;

use App\Notifications\Action;

use App\FCMPush;
use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;

class ExpiryPoll extends Action
{
    public $view;
    public $sub;
    public $notification;
    public $surveyInfo;

    public function __construct($event)
    {
        parent::__construct($event);
        
        if (isset($event->model->isAdmin)) {
            
            $this->sub = "Your poll has closed: " . (strlen($event->model->title) > 80 ? substr($event->model->title, 0, 80) . "..." : $event->model->title);
        } else {
            
            $this->sub = htmlspecialchars_decode($event->who["name"]) . " poll has closed: " . (strlen($event->model->title) > 80 ? substr($event->model->title, 0, 80) . "..." : $event->model->title);
        }

        echo $this->sub;
        
        
        // $this->sub = htmlspecialchars_decode($this->data->who['name']) ." has assigned a new product (".$event->batchInfo->name.") for you to taste";
        
        $this->notification = $this->sub;
    }

    public function via($notifiable)
    {
        $via = ['database', FCMPush::class];
        if ($this->view && view()->exists($this->view)) {

            $via[] = 'mail';
        }
        return $via;
    }

    public function toMail($notifiable)
    {
        if (view()->exists($this->view)) {

            return (new MailMessage())->subject($this->sub)->view(
                $this->view,
                [
                    'data' => $this->data, 'model' => $this->allData, 'notifiable' => $notifiable,
                    'content' => $this->getContent($this->allData['content']),
                    'info' => $this->surveyInfo
                ]
            );
        }
    }

    public function toArray($notifiable)
    {
        $data = [
            'action' => $this->data->action,
            'profile' => isset(request()->user()->profile) ? request()->user()->profile : $this->data->who,
            'notification' => $this->notification,
        ];

        if (method_exists($this->model, 'getNotificationContent')) {
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
