<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = ['name', 'profile_id'];
    
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
    
    public function subscribers()
    {
        return Subscriber::where('channel_name','like',$this->name)->get();
    }
    
    public function subscribe($profileId)
    {
        //todo: notify the channel owner after new subscription
        return Subscriber::create(['channel_name'=>$this->name,'profile_id'=>$profileId]);
    }
}
