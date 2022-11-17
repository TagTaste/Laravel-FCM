<?php

namespace App\Events\Actions;

use App\Events\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class QuizAnswered extends Action
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $model;
    public $who;
    public $action;
    public $content;
    public $image;
    public $actionModel;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model &$model, $who = null, $image = null, $action = null, $company = null)
    {        
        parent::__construct($model);
        $this->model = $model;
        $this->who = $who;
        if(isset($company))
        {
            $this->who = ['id'=>$company->id, 'name'=>$company->name, 'imageUrl'=>$company->logo,'type'=>'company', 'tagline'=>$company->tagline, 'verified'=>$company->verified];
        }
        
        $this->action = $action === null ? strtolower(class_basename(static::class)) : $action;
        $this->image = $image;
        $this->actionModel = null;
    }

}
