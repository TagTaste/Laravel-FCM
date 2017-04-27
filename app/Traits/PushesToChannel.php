<?php
namespace App\Traits;


trait PushesToChannel
{
    public function pushToChannel($channelName,&$model)
    {
        if(!method_exists($this,'channels')){
            
            \Log::info("Cannot push to " . $channelName);
            \Log::warning("Channel relationship not defined on " . class_basename($this));
            return;
        }
        
        $channel = $this->channels()->where('name',$channelName)->first();
        
        if(!$channel){
            //since a user can post even if he has no network (i.e. no followers)
            //throwing an exception here might cause some problem.
            //Throw an error if you feel like. Make sure it doesn't break anything.
            \Log::warning("Channel " . $channelName . " does not exist.");
            return false;
        }
        
        $payload = $channel->addPayload($model);
        //update model id
        $model->payload_id = $payload->id;
        $model->save();
        //
        return $payload;
        
    }
    
    public function pushToMyFeed(&$data)
    {
        //push to my feed
        $this->pushToChannel("feed." . $this->id,$data);
    }
    
    public function pushToNetwork(&$data)
    {
        return $this->pushToChannel("network." . $this->id,$data);
    }
    
    public function pushToPublic(&$data)
    {
        return $this->pushToChannel("public." . $this->id,$data);
    }
    
    
}