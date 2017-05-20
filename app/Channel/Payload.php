<?php

namespace App\Channel;

use App\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payload extends Model
{
    use SoftDeletes;
    
    protected $table = 'channel_payloads';
    
    protected $fillable = ['channel_name', 'payload','model','model_id'];
    
    public static function boot()
    {
        parent::boot();
        
        self::created(function(Payload $payload){
            $payload->publish();
        });
        
    }
    
    private function publish()
    {
        try {
            //payload has all the required keys, stored as json.
                $cacheKeys = json_decode($this->payload,true);
            
            //get all cached objects at once
                $objects = \Redis::mget(array_values($cacheKeys));
            
            //Making a string instead of using objects/arrays (not using json_encode/decode).
            //Why decode an object just to prefix a key to it, and then re-encode it?
    
            //build the json string.
                $i = 0;
                $jsonPayload = "{";
                $count = count($cacheKeys);
                foreach($cacheKeys as $name => $key){
                    $jsonPayload .= "\"{$name}\":"  . $objects[$i];
                    if($i<$count-1){
                        $jsonPayload .= ",";
                    }
                    $i++;
                }
                $jsonPayload .= "}";
            
            //publish
            \Redis::publish($this->channel->name, $jsonPayload);
            //\Redis::sAdd($this->channel->name,json_encode($object));
    
        } catch (\Exception $e){
            \Log::warning("Could not publish.");
            \Log::info($e->getMessage());
            \Log::info($e->getFile() . " " . $e->getLine() . " " . $e->getCode());
        }
    }
    
    public function channel()
    {
        return $this->belongsTo(Channel::class,'channel_name','name');
    }
    
    public function setPayloadAttribute($data)
    {
        $this->attributes['payload'] = json_encode($data);
    }
}
