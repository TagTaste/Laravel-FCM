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
            $cacheKeys = json_decode($this->payload);
            $objects = [];
            foreach($cacheKeys as $name => $key){
                $object = \Redis::get($key);
                if(!$object){
                    continue;
                }
                $objects[$name] = json_decode($object);
            }
            \Redis::publish($this->channel->name, json_encode($objects));
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
