<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatLimit extends Model
{
    protected $fillable = ['profile_id', 'remaining', 'max'];
}
