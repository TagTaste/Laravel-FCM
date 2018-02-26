<?php


namespace App\Notifications\Actions;

use App\Notifications\Action;

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

}