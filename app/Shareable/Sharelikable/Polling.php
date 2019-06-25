<?php

namespace App\Shareable\Sharelikable;

use Illuminate\Database\Eloquent\Model;

class Polling extends Like
{
    protected $table = 'polling_share_likes';
    protected $fillable = ['poll_share_id','profile_id'];
}
