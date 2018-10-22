<?php

namespace App\Chat;

use App\Chat;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'chat_messages';
    
    protected $fillable = ['message', 'chat_id', 'profile_id', 'read_on','file','preview','parent_message_id','type'];
    
    protected $visible = ['id','message','profile_id','created_at','chat_id','profile','read_on','fileUrl','preview','read','parentMessage','messageType'];
    
    protected $with = ['profile'];
    
    protected $touches = ['chat'];

    protected $appends = ['fileUrl','read','parentMessage','messageType'];


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

    public function getMessageTypeAttribute()
    {
        if($this->type != 0)
        {
            return \DB::table('chat_message_type')->where('id',$this->type)->first();
        }
    }


}
