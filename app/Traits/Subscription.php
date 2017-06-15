<?php


namespace app\Traits;

use App\Channel;
use App\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;
trait Subscription
{
    public function subscribe($channelName, &$owner)
    {
        return $this->getChannel($channelName,$owner,true)->subscribe($this->id);
    }
    
    private function getChannel(&$channelName, &$owner, $createIfNotExist = true)
    {
        $prefix = $owner instanceof Company ? "company." : null;
        $whereClause = $owner instanceof Company ? "company_id" : "profile_id";
        $channelName = $prefix . $channelName . "." . $owner->id;
        
        $channel = Channel::where($whereClause,$owner->id)->where('name','like',$channelName)->first();
        
        if($channel === null){
            if(!$createIfNotExist) {
                throw new ModelNotFoundException("Channel not found.");
            }
            
            $channel = Channel::create(['name'=>$channelName,$whereClause=>$owner->id]);
        }
        
        return $channel;
    }
}