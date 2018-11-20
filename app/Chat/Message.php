<?php

namespace App\Chat;

use App\Chat;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'chat_messages';
    
    protected $fillable = ['message', 'chat_id', 'profile_id', 'read_on','file','preview','parent_message_id',
        'type','file_meta','signature'];
    
    protected $visible = ['id','message','profile_id','created_at','chat_id','profile','read_on',
        'file','preview','read','parentMessage','headerMessage','messageType', 'file_meta','signature','chatInfo','headerAction'];
    
    protected $with = ['profile'];
    
    protected $touches = ['chat'];

    protected $appends = ['read','parentMessage','headerMessage','messageType','chatInfo','headerAction'];

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
        return $this->belongsTo(\App\Chat\Profile::class,'profile_id','id');
    }

    public function getFileUrlAttribute()
    {
        return !is_null($this->file) ? \Storage::url($this->file) : null;
    }

    // public function getPreviewAttribute($value)
    // {
    //     try {
    //         $preview = json_decode($value,true);

    //         if(isset($preview['image']) && !is_null($preview['image']))
    //         {
    //             $preview['image'] = is_null($preview['image']) ? null : \Storage::url($preview['image']);
    //         }
    //         return $preview;

    //     } catch(\Exception $e){
    //         \Log::error("Could not load preview image");
    //         \Log::error($preview);
    //         \Log::error($e->getLine());
    //         \Log::error($e->getMessage());
    //     }
    //     return empty($preview) ? null : $preview;
    // }

    public function getReadAttribute()
    {
        if(!\DB::table('message_recepients')->where('message_id',$this->id)->where('recepient_id','!=',request()->user()->profile->id)->exists())
        {
            return false;
        }
        $meta = \DB::table('message_recepients')->where('message_id',$this->id)->where('recepient_id','!=',request()->user()->profile->id)
            ->whereNull('read_on')->exists();
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
            $receiverId = is_null($messageArray[2]) ? : $messageArray[2];
            $messageString = [];
            if($messageArray[0] == request()->user()->profile->id)
            {
                $messageString[0]="You";
            }
            if($messageArray[0] != request()->user()->profile->id)
            {
                $profile = \App\Recipe\Profile::where('id',$messageArray[0])->first();
                $messageString[0] = $profile["name"];
            }

            $messageString[1] = \DB::table('chat_message_type')->where('id',$this->type)->pluck('id')->first();

            if($messageArray[2] === null || ($messageArray[2] == $messageArray[0]))
            {
                $messageString[2] = null;
            }

            if($messageArray[2] == request()->user()->profile->id)
            {
                $messageString[2] = "you";
            }

            else
            {
                $profile = \App\Recipe\Profile::where('id',$receiverId)->first();
                $messageString[2] = $profile["name"];
            }

            switch ($messageString[1]) {
                case 1:
                    return $messageString[0]." created group ".$this->name;
                    break;
                
                case 2:
                    return $messageString[0]." added ".$messageString[2];
                    break;

                case 3:
                    return $messageString[0]." removed ".$messageString[2]." from the group";
                    break;

                case 4:
                    return $messageString[0]." left the group";
                    break;

                case 5:
                    return $messageString[0]." updated the group name";
                    break;

                case 6:
                    return $messageString[0]." updated the group icon";
                    break;

                case 7:
                    if ($messageString[0]=="You") {
                        return $messageString[0]." are now an admin";
                    }
                    else
                    {
                        return $messageString[0]." is now an admin";
                    }
                    break;

                case 8:
                    if ($messageString[0]=="You") {
                        return $messageString[0]." have been removed as an admin";
                    }
                    else
                    {
                        return $messageString[0]." has been removed as an admin";
                    }
                    break;
                default:
                    return "some action is taken in the group";
                    break;
            }

        }
        return;
    }

    public function getMessageTypeAttribute()
    {
        if($this->type == 0)
            return 0;
        else
            return 1;
    }

    public function getChatInfoAttribute()
    {
        $chat = \DB::table('chats')->where('id',$this->chat_id)->select('id','name','image','chat_type')->first();
        return $chat;
    }

    public function getHeaderActionAttribute()
    {
        if($this->type != 0)
        {
            $action = explode('.', $this->message);
            $data = [];
            if(isset($action[2]) && isset($action[0]) && !is_null($action[2]) && !is_null($action[0]))
            {
                $data = ['actionTakenBy'=> json_decode(\Redis::get("profile:small:".$action[0])),'actionedOn'=>json_decode(\Redis::get("profile:small:".$action[2]))] ;
            }
            else if(isset($action[0]) && !is_null($action[0]))
            {
                $data = ['actionTakenBy' => json_decode(\Redis::get("profile:small:".$action[0]))];
            }
            return ['actionAbleProfiles'=>$data,'action'=>$this->type];
        }
        return null;
    }

}
