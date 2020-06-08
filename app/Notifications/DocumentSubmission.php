<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\FCMPush;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class DocumentSubmission extends Notification
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
    public $files;
    public function __construct($event)
    {
        $this->view = 'emails.document-submission';
        $this->sub = "Document Submission";
        $this->companyName = "";
        $userName = $event->profile->name;
        $this->notification = "$userName has uploaded a document for your collaboration ".$event->collaborate->title." ";
        // if (isset($event->company['name'])) {
        //     $this->companyName = $event->company['name']; 
        //     $this->notification = "User ".$this->companyName." has uploaded a document for your collaboration ".$event->collaborate->title." ";
        // }
        $this->file = $event->files;
        $this->data = $event->collaborate;
        $this->model = $event->collaborate;
        $this->action = $event->action;
        $this->profile = $event->profile;
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
                $this->view, ['data' => $this->data,'model'=>$this->model,'notifiable'=>$notifiable,'files'=>$event->files]
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
            'profile' => isset($this->profile) ? $this->profile : null,
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
