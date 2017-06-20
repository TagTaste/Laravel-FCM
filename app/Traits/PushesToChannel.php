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
            //since a user can post even if he has no networzk (i.e. no followers)
            //throwing an exception here might cause some problem.
            //Throw an error if you feel like. Make sure it doesn't break anything.
            \Log::warning("Channel " . $channelName . " does not exist.");
            return false;
        }
        
        $payload = $model->getPayload();
        $payload = $channel->addPayload(get_class($model),$model->id,$payload);
        //update model id
        
        //this simple thing will not fire any events of the model.
        //who knew.
        $table = $model->getTable();
        $id = $model->id;
        \DB::table($table)->where('id',$id)->update(['payload_id'=>$payload->id]);

        return $payload;
        
    }
    
    private function getChannelName($name)
    {
        $prefix = $this instanceof Company ? "company." : null;
        return $prefix . $name . "." . $this->id;
    }
    
    public function pushToMyFeed(&$data, $payloadable)
    {
        //push to my feed
        $this->pushToChannel("feed",$data,$payloadable);
    }
    
    public function pushToNetwork(&$data, $payloadable)
    {
        return $this->pushToChannel("network",$data,$payloadable);
    }
    
    public function pushToPublic(&$data,$payloadable)
    {
        return $this->pushToChannel("public",$data,$payloadable);
    }
    
}