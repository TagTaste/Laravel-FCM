<?php

namespace App\Events;

use App\Events\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ExpireQuiz extends Action
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model &$model, $who = null, $content = null, $image = null, $action = null, $company = null)
    {
        parent::__construct($model,$who);
        $this->model = $model;
        if(isset($company))
        {
            $this->who = ['id'=>$company->id, 'name'=>$company->name, 'imageUrl'=>$company->logo,'type'=>'company', 'tagline'=>$company->tagline, 'verified'=>$company->verified];
        }
        
        $this->action = $action === null ? strtolower(class_basename(static::class)) : $action;
        $this->image = $image;
        $this->content = $content;
        $this->actionModel = null;
    }
}
