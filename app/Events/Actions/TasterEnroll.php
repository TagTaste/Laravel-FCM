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

class TasterEnroll extends Action
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $model;
    public $who;
    public $action;
    public $content;
    public $image;
    public $actionModel;
    public $batchInfo;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model &$model, $who = null, $content = null, $image = null, $action = null)
    {
        parent::__construct($model, $who = null);
        $this->model = $model;        
        $this->action = $action === null ? strtolower(class_basename(static::class)) : $action;
        $this->image = $image;
        $this->content = $content;
        
    }
}
