<?php


namespace App\Notifications\Actions;

use App\Notifications\Action;
use App\FCMPush;

class BeginTasting extends Action
{
    public $view;
    public $sub;
    public $notification ;

    public function __construct($event)
    {
        parent::__construct($event);
        $this->view = 'emails.begintasting';

        $this->sub = $this->data->who['name'] ." has assigned a new sample for you to taste";
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

}