<?php

namespace App\Events\Actions;

use App\Collaborate\Batches;
use App\Events\Action;
use App\Company;
use App\ModelSubscriber;
use App\Profile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RollbackTaster extends Action
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $model;
    public $who;
    public $action;
    public $content;
    public $image;
    public $actionModel;
    public $info;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model &$model, $who = null, $content = null, $image = null, $action = null, $company = null, $info = null)
    {
        parent::__construct($model, $who);
        $this->model = $model;
        if (isset($company)) {
            $this->who = ['id' => $company->id, 'name' => $company->name, 'imageUrl' => isset($company->logo) ? $company->logo : $company->image, 'type' => isset($company->logo)?'company':'user', 'tagline' => $company->tagline, 'verified' => $company->verified];
        }

        $this->action = $action === null ? strtolower(class_basename(static::class)) : $action;
        $this->image = $image;
        $this->content = $content;
        $this->actionModel = null;

        if (isset($info["is_survey"]))  
            $this->info = $info;
        elseif (!is_null($info))
            $this->info = \DB::table('collaborate_batches')->where('id', $info)->first();
    }
}
