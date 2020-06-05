<?php

namespace App\Notifications;

use App\FCMPush;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class DocumentRejected extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $view;
    public $sub;
    public $notification;
    public $data;
    public $model;
    public $modelName;
    public $allData;
    public $action;
    public $company;
    public $companyName;
    public $isContest;
    public function __construct($event)
    {
        $this->view = 'emails.document-reject';
        $this->sub = "Re-submit Documents";
        $this->companyName = "";
        $this->model = $event->collaborate;
        $name = $this->model['title'];
        $this->isContest = $event->collaborate['is_contest'];
        if($this->isContest) {
            $this->notification = "Admin has requested you to reupload the document for collaboration $name ";
        } else {
            $this->notification = "Documents you submitted, do not match our criteria. This could either be due to a blurry upload or absence of required information to validate your age. Tap here to submit again.";

        if (isset($event->company['name'])) {
            $this->companyName = $event->company['name']; 
            $this->notification = "Documents you submitted to ".$event->company['name'] ." do not match our criteria. This could either be due to a blurry upload or absence of required information to validate your age. Tap here to submit again.";
        }
        }
        
        $this->data = $event->collaborate;
        $this->model = $event->collaborate;
        $this->action = $event->action;
        $this->company = $event->company;
        $this->modelName = 'collaborate';
        if (method_exists($this->model,'getNotificationContent')) {
            $this->allData = $this->model->getNotificationContent();
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = ['database',FCMPush::class,'broadcast'];
        if ($this->view && view()->exists($this->view)) {
            $via[] = 'mail';
        }
        return $via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (view()->exists($this->view)) {
            return (new MailMessage())->subject($this->sub)->view(
                $this->view, ['data' => $this->data,'model'=>$this->model,'notifiable'=>$notifiable, 'companyName'=>$this->companyName]
            );
        }
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
            'action' => $this->action,
            'profile' => isset($this->company) ? $this->company : null,
            'notification' => $this->notification,
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
