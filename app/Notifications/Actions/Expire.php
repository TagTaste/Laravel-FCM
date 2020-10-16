<?php


namespace App\Notifications\Actions;

use App\Notifications\Action;
use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;

class Expire extends Action
{
    public $view = null;
    public $sub = 'Notification from Tagtaste';
    public $notification ;
    private $days;
    private $mailData;

    public function __construct($event)
    {
        parent::__construct($event);

        $this->view = 'emails.expire-'.$this->modelName;

        // expire in 2 days
        if($event->model->expires_on >= Carbon::now()->addDays(1)->toDateTimeString() && $event->model->expires_on <= Carbon::now()->addDays(2)->toDateTimeString())
        {
            $this->notification = __("mails.expire:$this->modelName:2days:notification", ['title' => $this->elipsis($this->model->title, 15)]);
            $this->days = '2days';
        }
        // expires tomorrow
        else if($event->model->expires_on >= Carbon::now()->toDateTimeString() && $event->model->expires_on <= Carbon::now()->addDays(1)->toDateTimeString())
        {
            $this->notification = __("mails.expire:$this->modelName:1day:notification", ['title' => $this->elipsis($this->model->title, 15)]);
            $this->days = '1day';
        }
        // expires in 7 days
        else if($event->model->expires_on >= Carbon::now()->addDays(7)->toDateTimeString() && $event->model->expires_on <= Carbon::now()->addDays(8)->toDateTimeString())
        {
            $this->notification = __("mails.expire:$this->modelName:7days:notification", ['title' => $this->elipsis($this->model->title, 15)]);
            $this->days = '7days';
        }
        // fallback (will not be used)
        else
        {
            $this->notification ="Your ".$this->modelName." ".$this->model->title." will expire soon.";
            $this->days = '1day';
        }

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

        $this->sub = $this->notification;

        $this->mailData = [
            'title' => __("mails.expire:$this->modelName:$this->days:title"),

            $this->modelName => [
                'id' => $this->model->id,
                'title' => $this->model->title,
                'owner_name' => $isCompany ? $name : $notifiable->name,
                'imageUrl' => $image,
            ],

            'master_btn_text' => 'VIEW ON TAGTASTE',
            'master_btn_url' => env('APP_URL').'/collaborations/'.$this->model->id.'/edit',

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
        $interested = $this->model->getApprovedAttribute();
        if($interested['count'] <=  0) {
            return;
        }
        $this->mailData['profiles_count'] = $interested['count']-3;
        $this->mailData['profiles'] =[];

        $count = 2;     // no. of interested profiles to send with email

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