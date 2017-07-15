<?php

namespace App\Shareable\Sharelikable;

use Illuminate\Database\Eloquent\Model;

class Collaborate extends Like
{
    protected $table = 'collaborate_share_likes';
    protected $fillable = ['id','profile_id'];
}
