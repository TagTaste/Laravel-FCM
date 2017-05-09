<?php

namespace App;

use App\Channel\Payload;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = ['name', 'profile_id','company_id'];
    
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
        $subscriber = Subscriber::withTrashed()->where('channel_name',$this->name)->where('profile_id',$subscriberProfileId)->first();
        if($subscriber){
            if($subscriber->trashed()){
                return $subscriber->restore();
            }
            return false;
        }
       
        $subscriber = Subscriber::create([
            'channel_name'=>$this->name,
            'profile_id'=>$subscriberProfileId,
            'timestamp'=>Carbon::now()->toDateTimeString()]);
        
        return $subscriber;
    }
    
    public function unsubscribe($subscriberProfileId)
    {
        \Log::info("unsubscribing");
        \Log::info($subscriberProfileId);
        \Log::info("from");
        \Log::info($this->name);
        $subscriber = Subscriber::where('channel_name','like',$this->name)->where('profile_id',$subscriberProfileId)->first();
        \Log::info($subscriber);
        if(!$subscriber){
            return false;
        }
        
        return $subscriber->delete();
    }
    
    public function payload()
    {
        return $this->hasMany(Payload::class,'channel_name','name');
    }
    
    public function addPayload(&$data)
    {
        return $this->payload()->create(['payload'=>$data,'model'=>get_class($data),'model_id'=>$data->id]);
    }
    
    public static function names($id)
    {
        //it's ok to have public channel here.
        //because, in socket.js, it would be
        //connected to a namespaced socket.io :D
        //G Maane Genious.
        $subscribedChannels = Subscriber::select('channel_name')->where('profile_id',$id)->get();
        $channels = $subscribedChannels->pluck('channel_name')->toArray();
        \Log::info($channels);
        return $channels;
    }
}
