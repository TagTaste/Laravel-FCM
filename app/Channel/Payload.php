<?php

namespace App\Channel;

use App\Channel;
use Illuminate\Database\Eloquent\Model;

class Payload extends Model
{
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
        \Redis::publish($this->channel, $this->payload);
    }
    
    public function channel()
    {
        return $this->belongsTo(Channel::class,'channel_name','name');
    }
}
