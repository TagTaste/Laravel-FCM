<?php


namespace App\Notifications\Actions;

use App\Deeplink;
use App\FCMPush;
use App\Notifications\Action;
use App\Traits\GetTags;
use App\Traits\HasPreviewContent;
use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Crypt;

class Comment extends Action
{
//    use HasPreviewContent;
    public $view;
    public $sub;
    public $notification;
    
    public function __construct($event)
    {
        parent::__construct($event);
        $this->view = 'emails.'.$this->data->action;
        $this->sub = $this->data->who['name'] ." commented on your post";
        if($this->model == 'review')
            $this->sub = $this->data->who['name'] ." commented on your review";
        $this->notification = $this->sub;

    }

    public function toMail($notifiable)
    {
        if($this->model != 'review') {

            $langKey = $this->data->action.':'.$this->modelName;

            // owner or subscriber
            $langKey = $notifiable->id == $this->model->profile_id ? $langKey.':owner' : $langKey.':subscriber';

            if(isset($this->allData['shared']) && $this->allData['shared'] == true) {
                $this->allData['url'] = Deeplink::getShortLink($this->modelName, $this->allData['id'], true, $this->allData['share_id']);
                $langKey = $langKey.':shared';
            } else {
                $this->allData['url'] = Deeplink::getShortLink($this->modelName, $this->allData['id']);
                $langKey = $langKey.':original';
            }

            $langKey = $langKey.':title';
            $this->sub = __('mails.'.$langKey, ['name' => $this->data->who['name']]);
            $this->allData['title'] = $this->sub;
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
                $unsubscribeLink = env('APP_URL')."/settingUpdate/unsubscribe/?k=".$encrypted;
                return (new MailMessage())->subject($this->sub)->view(
                    $this->view, ['data' => $this->data,'model'=>$this->allData,'notifiable'=>$notifiable, 'comment'=> $this->getContent($this->data->content), 'content'=>$this->getContent($this->allData['content']),'unsubscribeLink'=>$unsubscribeLink]
                );
            }
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