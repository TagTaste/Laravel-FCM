<?php

namespace App\Notifications;

use App\FCMPush;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

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

    public function __construct($event)
    {
        $this->view = 'emails.documentReject';
        $this->sub = "Your document has been rejected.";
        $this->notification = "Your document has been rejected.";
        $this->data = $event->collaborate;
        $this->model = $event->collaborate;
        $this->modelName = 'collaborate';
        if(method_exists($this->model,'getNotificationContent')){
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
        if($this->view && view()->exists($this->view)){
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
        if(view()->exists($this->view)) {
            return (new MailMessage())->subject($this->sub)->view(
                $this->view, []
            );//fill the data according yo the view in the array.
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
        return [
            //
        ];
    }
}
