<?php

namespace App\Channel;

use App\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payload extends Model
{
    use SoftDeletes;
    
    protected $table = 'channel_payloads';
    
    protected $fillable = ['channel_name', 'payload'];
    
    public static function boot()
    {
        parent::boot();
        
        self::created(function(Payload $payload){
            $payload->publish();
        });
    
    }
    
    private function publish()
    {
        \Redis::publish($this->channel->name, $this->payload);
    }
    
    public function channel()
    {
        return $this->belongsTo(Channel::class,'channel_name','name');
    }
    
    public function setPayloadAttribute($data)
    {
        $payload = is_object($data) ? [ strtolower(class_basename($data)) => $data] : $data;
        $this->attributes['payload'] = json_encode($payload);
    }
}
