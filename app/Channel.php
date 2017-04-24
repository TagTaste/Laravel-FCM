<?php

namespace App;

use App\Channel\Payload;
use Carbon\Carbon;
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
        return $this->hasMany(Subscriber::class,'channel_name','name');
    }
    
    public function subscribe($subscriberProfileId)
    {
        //todo: notify the channel owner after new subscription
        $subscriber = Subscriber::where('channel_name',$this->name)->where('profile_id',$subscriberProfileId)->first();
        if($subscriber){
            throw new \Exception("You are already following this profile.");
        }
       
        $subscriber = Subscriber::create([
            'channel_name'=>$this->name,
            'profile_id'=>$subscriberProfileId,
            'timestamp'=>Carbon::now()->toDateTimeString()]);
        
        return $subscriber;
    }
    
    public function unsubscribe($subscriberProfileId)
    {
        $subscriber = Subscriber::where('channel_name',$this->name)->where('profile_id',$subscriberProfileId)->first();
        
        if(!$subscriber){
            throw new \Exception("You are not following this profile.");
        }
        
        return $subscriber->delete();
    }
    
    public function payload()
    {
        return $this->hasMany(Payload::class,'channel_name','name');
    }
    
    public function addPayload(&$data)
    {
        $json = is_object($data) ? [ strtolower(class_basename($data)) => $data] : $data;
        return $this->payload()->create(['payload'=>json_encode($json)]);
    }
    
    public static function names($id)
    {
        $names = ['feed','network','public'];
        foreach($names as &$name){
            $name = $name . "." . $id;
        }
        return $names;
    }
}
