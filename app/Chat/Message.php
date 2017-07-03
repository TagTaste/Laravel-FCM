<?php

namespace App\Chat;

use App\Chat;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'chat_messages';
    
    protected $fillable = ['message', 'chat_id', 'profile_id', 'read_on'];
    
    protected $visible = ['message','created_at','chat_id','profile','profile_id'];
    
    protected $with = ['profile'];
    
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
}
