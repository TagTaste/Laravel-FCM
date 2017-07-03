<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMember extends Model
{
    use SoftDeletes;
    protected $fillable = ['chat_id', 'profile_id'];
}
