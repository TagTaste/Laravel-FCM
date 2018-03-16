<?php


namespace App\Notifications\Actions;

use App\Notifications\Action;
use App\FCMPush;
use App\Setting;

class Apply extends Action
{
    public $view;
    public $sub;
    public $notification ;

    public function __construct($event)
    {
        parent::__construct($event);
        $this->view = 'emails.'.$this->data->action.'-'.$this->modelName;

        if($this->modelName == 'collaborate')
        {
            $this->sub = $this->data->who['name'] ." wants to collaborate with you on ".$this->model->title;
            if(!is_null($this->data->content)) {
                $this->allData['message'] = ['id' => null,'image'=>null,'content'=>$this->data->content];

            }
        }
        else
        {
            $this->sub = $this->data->who['name'] ." applied to your job : ".$this->model->title;

        }
        $this->notification = $this->sub;

    }

    public function via($notifiable)
    {
        $via = ['database',FCMPush::class,'broadcast'];

        if($this->view && view()->exists($this->view)){
            $via[] = 'mail';
        }

        $preference = Setting::getNotificationPreference($notifiable->id, null, $this->data->action,null,$this->modelName);
        \Log::info("ACTION.PHP ".print_r($preference, true));
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