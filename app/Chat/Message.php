<?php

namespace App\Chat;

use App\Chat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Message extends Model
{
    protected $table = 'chat_messages';
    
    protected $fillable = ['message', 'chat_id', 'profile_id', 'read_on','file','preview'];
    
    protected $visible = ['id','message','created_at','chat_id','profile','read_on','fileUrl','preview'];
    
    protected $with = ['profile'];
    
    protected $touches = ['chat'];

    protected $appends = ['fileUrl'];


    public static function boot()
    {   
        self::created(function(Model $message){

            $members = \App\Chat\Member::withTrashed()->where('chat_id',$message->chat_id)->whereNull('exited_on')->pluck('profile_id');
            \App\Chat\Member::where('chat_id',$message->chat_id)->onlyTrashed()->update(['deleted_at'=>null]);
            $recepient = [];
            $time = $message->created_at;
            foreach ($members as $profileId) {
                if($profileId == request()->user()->profile->id)
                {
                    $recepient[] = ['message_id'=>$message->id, 'recepient_id'=>$profileId, 'chat_id'=>$message->chat_id, 'sent_on'=>$time, 'read_on' => $time];
                }
                else
                {
                    if($message->type != 0)
                    {
                        $recepient[] = ['message_id'=>$message->id, 'recepient_id'=>$profileId, 'chat_id'=>$message->chat_id, 'sent_on'=>$time, 'read_on' => $time];
                    }
                    else
                    {
                        $recepient[] = ['message_id'=>$message->id, 'recepient_id'=>$profileId, 'chat_id'=>$message->chat_id, 'sent_on'=>$time, 'read_on' => null];
                    }
                }
            }
            \DB::table('message_recepients')->insert($recepient);

            if($message->type == 0)
            {   
                $message->load('profile');
                Redis::publish("chat." . $message->chat_id,$message->toJson());
            }
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
        return $this->file;
    }

    public function getPreviewAttribute($value)
    {
        try {
            $preview = json_decode($value,true);

            if(isset($preview['image']) && !is_null($preview['image']))
            {
                $preview['image'] = is_null($preview['image']) ? null : \Storage::url($preview['image']);
            }
            return is_array($preview) ? (string)json_encode($preview,true) : $preview;

        } catch(\Exception $e){
            \Log::error("Could not load preview image");
            \Log::error($preview);
            \Log::error($e->getLine());
            \Log::error($e->getMessage());
        }
        return empty($preview) ? null : is_array($preview) ? (string)json_encode($preview,true) : $preview;
    }
    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
