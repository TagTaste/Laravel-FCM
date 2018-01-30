<?php

namespace App\Events;

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

class Action
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $model;
    public $who;
    public $action;
    public $content;
    public $image;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model &$model, Profile $who = null, $content = null, $image = null, $action = null, $company = null)
    {
        $this->model = $model;
        $this->who = isset($company) ? ['id'=>$who->id, 'name'=>$who->name, 'imageUrl'=>$who->logo,'type'=>'company', 'tagline'=>$who->tagline] : isset($who) ? ['id'=>$who->id, 'name'=>$who->name, 'imageUrl'=>$who->imageUrl,'type'=>'profile', 'tagline'=>$who->tagline] : null;
        $this->action = $action === null ? strtolower(class_basename(static::class)) : $action;
        $this->image = $image;
        $this->content = $content;
    }

    public function getModelName(){
        return strtolower(class_basename($this->model));
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
