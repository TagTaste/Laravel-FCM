<?php

namespace App\Notifications\Actions;

use App\Deeplink;
use App\Notifications\Action;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Crypt;

class Tag extends Action
{
    public $view;
    public $sub;
    public $notification;


    public function __construct($event)
    {
        parent::__construct($event);
        $this->view = 'emails.'.$this->data->action;
        $this->sub = $this->data->who['name'] ." mentioned you in a post";
        $this->notification = $this->data->who['name'] . " tagged you in a post.";
        if($this->modelName == 'review')
        {
            if(method_exists($this->model,'getNotificationContent')){
                $this->allData = $this->model->getNotificationContent();
                $this->sub = $this->data->who['name'] ." commented on your review of ".$this->allData['title'];

            }
            $this->sub = $this->data->who['name'] . " tagged you in a comment on review of ".$this->allData['title'];
        }
    }

    public function toMail($notifiable)
    {
        if($this->modelName != 'review')
        {
            $langKey = $this->data->action;

            $langKey = isset($this->data->actionModel) ? $langKey.':'.strtolower(class_basename($this->data->actionModel)) : $langKey.':'.$this->modelName;

            if(isset($this->allData['shared']) && $this->allData['shared'] == true) {
                $this->allData['url'] = Deeplink::getShortLink($this->modelName, $this->allData['id'], true, $this->allData['share_id']);
            } else {
                $this->allData['url'] = Deeplink::getShortLink($this->modelName, $this->allData['id']);
            }

            $langKey = $langKey.':title';
            $this->sub = __('mails.'.$langKey, ['name' => $this->data->who['name']]);
            $this->allData['title'] = $this->sub;
            $this->notification = $this->sub;

            if(view()->exists($this->view)){
                $action = $this->data->action;
                $profileId = $notifiable->id;
                $model = $this->modelName;
                if($this->model->company_id != null)
                {
                    $companyId = $this->model->company_id;
                    $encrypted = Crypt::encryptString($this->settingId."/".$profileId."/".$companyId);
                }
                else{
                    $companyId = null;
                    $encrypted = Crypt::encryptString($this->settingId."/".$profileId."/".$companyId);
                }
                $unsubscribeLink = env('APP_URL')."/api/settingUpdate/unsubscribe/?k=".$encrypted;
                return (new MailMessage())->subject($this->sub)->view(
                    $this->view, ['data' => $this->data,'model'=>$this->allData,'notifiable'=>$notifiable, 'comment'=> $this->getContent($this->data->content),'content'=>$this->getContent($this->allData['content']),'unsubscribeLink'=>$unsubscribeLink]
                );
            }
        }
    }

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
