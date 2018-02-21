<?php


namespace App\Notifications\Actions;

use App\Notifications\Action;
use Illuminate\Notifications\Messages\MailMessage;

class ExpireModel extends Action
{
    public $view = null;
    public $sub = 'Notification from Tagtaste';
    public $notification ;
    private $mailData;

    public function __construct($event)
    {
        parent::__construct($event);

        $this->view = 'emails.expire-'.$this->modelName;

        $this->notification = __("mails.expire:$this->modelName:expired:notification", ['title' => $this->elipsis($this->model->title, 15)]);
        $this->sub = $this->notification;

    }

    public function via($notifiable)
    {
        $via = [];

        if($this->view && view()->exists($this->view)){
            $via[] = 'mail';

        }
        return $via;
    }


    /**
     * Get the mail representation of the notification.
     * Overrides mail method of action.php
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $image = 'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/emails/placeholders/'.$this->modelName.'.png';
        $name = 'Your';
        $isCompany = false;

        if($this->model->company_id != null) {
            $isCompany= true;
            $company = $this->model->company;
            $name = $company->name;
            $image = $company->logo != null ? $company->logo : 'http://www.tagtaste.com/images/default_company_avatar.jpg';
        }

        $this->mailData = [
            'title' => __("mails.expire:$this->modelName:expired:title"),

            $this->modelName => [
                'id' => $this->model->id,
                'title' => $this->model->title,
                'owner_name' => $isCompany ? $name : $notifiable->name,
                'imageUrl' => $image,
            ],

            'master_btn_text' => 'RENEW NOW',
            'master_btn_url' => env('APP_URL').'/'.$this->modelName.'/'.$this->model->id.'/edit',

        ];

        $func = $this->modelName.'Data';
        $this->$func();

        if(view()->exists($this->view)){
            return (new MailMessage())->subject($this->sub)->view(
                $this->view, ['data' => $this->mailData]
            );
        }
    }

    /**
     * Sets mail meta data for collaborate
     * expire mails.
     * @author aman
     * @return null
     */
    private function collaborateData()
    {
        $interested = $this->model->getInterestedAttribute();
        if($interested['count'] <=  0) {
            return;
        }
        $this->mailData['msg2'] = __('mails.expire:collaborate:msg2');
        $this->mailData['profile_count'] = $interested['count']-3;
        $this->mailData['profiles'] =[];

        $count = 3;     // no. of interested profiles to send with email

        foreach ($interested['profiles'] as $profile) {
            $this->mailData['profiles'][] = [
                'id' => $profile->id,
                'imageUrl' => $profile->imageUrl != null ? $profile->imageUrl : 'https://www.tagtaste.com/images/emails/profile-circle.png',
                'name' => $profile->name,
                'tagline' => !empty($profile->tagline) ? $profile->tagline : '',
            ];
            if(!$count--) break;
        }
    }

    /**
     * Sets mail meta data for job expire
     * mails.
     * @author aman
     * @return null
     */
    private function jobData()
    {
        $this->mailData['master_btn_url'] = env('APP_URL').'/jobs/'.$this->model->id.'/edit';
        $this->mailData['job']['btn_url'] = env('APP_URL').'/jobs/'.$this->model->id;
        $this->mailData['profile_count'] = $this->model->getApplicationCountAttribute();
    }

    private function elipsis($str, $len)
    {
        if(strlen($str) > $len) {
            return '"'.substr($str, 0 ,$len).'..."';
        } else {
            return '"'.$str.'"';
        }
    }
}