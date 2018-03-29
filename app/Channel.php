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
        $subscriber = Subscriber::where('channel_name','like',$this->name)->where('profile_id',$subscriberProfileId)->first();
        if(!$subscriber){
            return false;
        }
        
        return $subscriber->delete();
    }
    
    public function payload()
    {
        return $this->hasMany(Payload::class,'channel_name','name');
    }
    
    public function addPayload($modelName, $modelId, &$data)
    {
        return $this->payload()->create(['payload'=>$data,'model'=>$modelName,'model_id'=>$modelId]);
    }
    
    public static function names($id)
    {
        //it's ok to have public channel here.
        //because, in socket.js, it would be
        //connected to a namespaced socket.io :D
        //G Maane Genius.
        $subscribedChannels = Subscriber::select('channel_name')->where('profile_id',$id)->get();
        $channels = $subscribedChannels->pluck('channel_name')->toArray();
        return $channels;
    }
}
