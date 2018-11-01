<?php

namespace App\Chat;

use App\Chat;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'chat_messages';
    
    protected $fillable = ['message', 'chat_id', 'profile_id', 'read_on','file','preview','parent_message_id','type'];
    
    protected $visible = ['id','message','profile_id','created_at','chat_id','profile','read_on','fileUrl','preview',
        'read','parentMessage','headerMessage','messageType'];
    
    protected $with = ['profile'];
    
    protected $touches = ['chat'];

    protected $appends = ['fileUrl','read','parentMessage','headerMessage','messageType'];

    //type only in group
    // 1 - when owner create a group
    // 2 - when admin added someone
    // 3 when admin remove someone
    // 4 when someone is left
    // 5 group name change
    // 6 iamge change
    // 7 make admin
    // 8 remove admin


    public static function boot()
    {
        self::created(function(Model $message){

            //is there a better way?
            $message->load('profile');
            \Redis::publish("chat." . $message->chat_id,$message->toJson());
        });
    }
    
    public function chat()
    {
        return $this->belongsTo(Chat::class,'chat_id');
    }
    
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class,'profile_id','id');
    }

    public function getFileUrlAttribute()
    {
        return !is_null($this->file) ? \Storage::url($this->file) : null;
    }

    public function getPreviewAttribute($value)
    {
        try {
            $preview = json_decode($value,true);

            if(isset($preview['image']) && !is_null($preview['image']))
            {
                $preview['image'] = is_null($preview['image']) ? null : \Storage::url($preview['image']);
            }
            return $preview;

        } catch(\Exception $e){
            \Log::error("Could not load preview image");
            \Log::error($preview);
            \Log::error($e->getLine());
            \Log::error($e->getMessage());
        }
        return empty($preview) ? null : $preview;
    }

    public function getReadAttribute()
    {
        $meta = \DB::table('message_recepients')->where('message_id',$this->id)->where('recepient_id','!=',request()->user()->profile->id)->whereNull('read_on')->exists();
        return !$meta;
    }

    public function getParentMessageAttribute()
    {
        if($this->parent_message_id)
        {
            return Message::where('id',$this->parent_message_id)->first();
        }
    }


    public function getHeaderMessageAttribute()
    {   
        if($this->type != 0 && isset($this->message))
        {
            $messageArray = explode('.', $this->message);
            $receiverId = $messageArray[2];
            $messageString = [];
            if($messageArray[0] == request()->user()->profile->id)
            {
                $messageString[0]="You ";
            }
            if($messageArray[0] != request()->user()->profile->id)
            {
                $profile = \App\Recipe\Profile::where('id',$messageArray[0])->first();
                $messageString[0] = $profile["name"];
            }

            $messageString[1] = \DB::table('chat_message_type')->where('id',$this->type)->pluck('text')->first();

            if($messageArray[2] === null || ($messageArray[2] == $messageArray[0]))
            {
                $messageString[2] = null;
            }

            if($messageArray[2] ==request()->user()->profile->id)
            {
                $messageString[2] = " you";
            }

            else
            {
                $profile = \App\Recipe\Profile::where('id',$receiverId)->first();
                $messageString[2] = $profile["name"];
            }
            return $messageString[0].$messageString[1].$messageString[2];

        }
    }

    public function getMessageTypeAttribute()
    {
        if($this->type == 0)
            return 0;
        else
            return 1;
    }

}
