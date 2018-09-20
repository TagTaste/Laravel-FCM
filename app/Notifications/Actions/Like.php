<?php


namespace App\Notifications\Actions;

use App\CompanyUser;
use App\Notifications\Action;
use App\FCMPush;
use App\Setting;

class Like extends Action
{
    public $view = null;
    public $sub = 'Notification from Tagtaste';
    public $notification ;

    public function __construct($event)
    {
        parent::__construct($event);
        $this->notification = $this->data->who['name'] . " liked a post.";
    }

    // Overriding this function to prevent self like notification
    public function via($notifiable)
    {
        if($this->data->who['id'] == $notifiable->id) {
            return [];
        }

        $via = ['database',FCMPush::class,'broadcast'];

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
}