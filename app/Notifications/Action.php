<?php

namespace App\Notifications;

use App\CompanyUser;
use App\FCMPush;
use App\Setting;
use App\Traits\GetTags;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;

class Action extends Notification
{
    use GetTags, Queueable;
    
    public $data;
    public $model;
    public $modelId;
    public $action;
    public $content;
    public $image;
    public $modelName;
    public $allData ;
    public $settingId ;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
//    public function __construct($model, $modelId, $content = null, $image = null, $action = null)
    public function __construct($event)
    {
        $this->data = $event;
        $this->model = $event->model;
        $this->modelName = strtolower(class_basename($event->model));
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

        if(isset($this->allData['type']) && $this->allData['type'] == 'product')
        {
            return $via;
        }
        if($this->view && view()->exists($this->view)){
            $via[] = 'mail';

        }

        $preference = null;

        if(isset($this->model->company_id) && !is_null($this->model->company_id)) {

            //getting list of company admins
            $admins = CompanyUser::getCompanyAdminIds($this->model->company_id);

            // user is admin of the company
            if(in_array($notifiable->id, $admins)) {

                // company admin 'onlyme' case (user is owner)
                if($this->model->profile_id == $notifiable->id) {
                    $preference = Setting::getNotificationPreference($notifiable->id, $this->model->company_id, $this->data->action, 'onlyme');
                } else {
                    $preference = Setting::getNotificationPreference($notifiable->id, $this->model->company_id, $this->data->action);
                }
            }
            // user is just a subscriber of model
            else {
                $preference = Setting::getNotificationPreference($notifiable->id, null, $this->data->action);
            }

        } else {
            $preference = Setting::getNotificationPreference($notifiable->id, null, $this->data->action);
        }
        if(is_null($preference)) {
            return $via;
        }
        $this->settingId = $preference->setting_id;
        $via = [];
        if($preference->bell_value) {
            $via[] = 'broadcast';
            $via[] = 'database';
        }
        if($preference->email_value && $this->view && view()->exists($this->view)) {
            $via[] = 'mail';
        }
        if($preference->push_value) {
            $via[] = FCMPush::class;
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
        if(view()->exists($this->view)){

            if($this->data->action != 'expire'||$this->data->action != 'admin'||$this->data->action != 'delete-model')
        {
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
                $this->view, ['data' => $this->data,'model'=>$this->allData,'notifiable'=>$notifiable,'content'=>$this->getContent($this->allData['content']),'unsubscribeLink'=>$unsubscribeLink]
            );
        }
            return (new MailMessage())->subject($this->sub)->view(
                $this->view, ['data' => $this->data,'model'=>$this->allData,'notifiable'=>$notifiable,'content'=>$this->getContent($this->allData['content'])]
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
            'action' => $this->data->action,
            'profile' => isset(request()->user()->profile) ? request()->user()->profile : $this->data->who,
            'notification' => $this->notification
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

    public function getContent($text)
    {
        if(isset($text['text']))
        {
            $profiles = $this->getTaggedProfiles($text['text']);
            $pattern = [];
            $replacement = [];
            foreach ($profiles as $index => $profile)
            {
                $pattern[] = '/\@\['.$profile->id.'\:'.$index.'\]/i';
                $replacement[] = $profile->name;
            }
            $replacement = array_reverse($replacement);
            return preg_replace($pattern,$replacement,$text['text']);

        }
        elseif($text != '')
        {
            $profiles = $this->getTaggedProfiles($text);
            $pattern = [];
            $replacement = [];
            if($profiles == false) {
                return $text;
            }
            foreach ($profiles as $index => $profile)
            {
                $pattern[] = '/\@\['.$profile->id.'\:'.$index.'\]/i';
                $replacement[] = $profile->name;
            }
            $replacement = array_reverse($replacement);
            return preg_replace($pattern,$replacement,$text);
        }
        else
        {
            return "";
        }
    }

}
