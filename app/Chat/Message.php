<?php

namespace App\Chat;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'chat_messages';
    protected $fillable = ['message', 'chat_id', 'profile_id', 'read_on'];
}
