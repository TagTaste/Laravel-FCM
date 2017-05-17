<?php
namespace App\Traits;


use App\Company;

trait PushesToChannel
{
    public function pushToChannel($channelName,&$model)
    {
        $channelName = $this->getChannelName($channelName);
        
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
        
       
        $payload = $model->getPayload();
        $payload = $channel->addPayload(get_class($model),$model->id,$payload);
        //update model id
        $model->payload_id = $payload->id;
        $model->save();
        return $payload;
        
    }
    
    private function getChannelName($name)
    {
        $prefix = $this instanceof Company ? "company." : null;
        return $prefix . $name . "." . $this->id;
    }
    
    public function pushToMyFeed(&$data)
    {
        //push to my feed
        $this->pushToChannel("feed",$data);
    }
    
    public function pushToNetwork(&$data)
    {
        return $this->pushToChannel("network",$data);
    }
    
    public function pushToPublic(&$data)
    {
        return $this->pushToChannel("public",$data);
    }
    
}