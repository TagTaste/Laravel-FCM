<?php

namespace App\Chat;

use App\Chat;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'chat_messages';
    
    protected $fillable = ['message', 'chat_id', 'profile_id', 'read_on'];
    
    public function chat()
    {
        return $this->belongsTo(Chat::class,'chat_id');
    }
    
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class,'profile_id');
    }
}
