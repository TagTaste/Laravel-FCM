<?php


namespace App\Notifications;

use App\FCMPush;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;
// use App\Notifications\Action;

class CollaborationReportUpload extends Notification implements ShouldQueue
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
    public $notificationMode;
    public $profile;

    public function __construct($event)
    {
        $this->view = 'emails.document-reject';
        $this->sub = "New Report Uploaded";
        $this->model = $event->collaborate;
        $this->profile = $event->profile;
        $name = $this->model['title'];
        $this->notification = $event->content;
        $this->notificationMode = $event->notificationMode;

        $this->data = $event->collaborate;
        $this->model = $event->collaborate;
        $this->action = $event->action;
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
        $modes = explode(',',$this->notificationMode);
        $via = [];
        if(in_array('bell', $modes)){
            $via[] = 'database';
            $via[] = FCMPush::class;
        }

        if(in_array('mail', $modes) && $this->view && view()->exists($this->view)){
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
                $this->view, ['data' => $this->data,'model'=>$this->model,'notifiable'=>$notifiable, 'companyName'=>'hello']
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
            'profile' => $this->profile,
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