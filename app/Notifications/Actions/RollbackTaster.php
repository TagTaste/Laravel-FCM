<?php


namespace App\Notifications\Actions;

use App\Notifications\Action;

use App\FCMPush;
use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;

class RollbackTaster extends Action
{
    public $view;
    public $sub;
    public $notification ;
    public $info;

    public function __construct($event)
    {
        parent::__construct($event);
        $this->info = $event->info;
        // $this->view = 'emails.begintasting';

        if(isset($info["is_survey"]) && $event->info["is_survey"] == 1){
            if($event->info["is_invited"]){
                $this->sub = htmlspecialchars_decode($this->data->who['name']) ." invited you to survey  (".htmlspecialchars_decode($event->model->title).") by mistake and it has been reversed.";

            }
            else{
                $this->sub = htmlspecialchars_decode($this->data->who['name']) ." accepted your survey participation request by mistake and it has been reversed. ";

            }
        }
        else{
        $this->sub = htmlspecialchars_decode($this->data->who['name']) ." assigned product (".$event->info->name.") by mistake and it has been reversed.";
        }
        if(!is_null($this->data->content)) {
            $this->allData['message'] = ['id' => null,'image'=>null,'content'=>$this->data->content];

        }
        $this->notification = $this->sub;

    }

    public function via($notifiable)
    {
        $via = ['database',FCMPush::class,'broadcast'];

        if($this->view && view()->exists($this->view)){
            $via[] = 'mail';
        }
        return $via;
    }

    public function toMail($notifiable)
    {
        if(view()->exists($this->view)){
            return (new MailMessage())->subject($this->sub)->view(
                $this->view, ['data' => $this->data,'model'=>$this->allData,'notifiable'=>$notifiable,
                    'content'=>$this->getContent($this->allData['content']),'batchInfo'=>$this->info]
            );
        }
    }

    public function toArray($notifiable)
    {
        $data = [
            'action' => $this->data->action,
            'profile' => isset(request()->user()->profile) ? request()->user()->profile : $this->data->who,
            'notification' => $this->notification,
            'batchInfo'=>$this->info
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