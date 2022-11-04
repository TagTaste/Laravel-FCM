<?php

namespace App\Shareable\Sharelikable;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Like
{
    protected $table = 'quiz_share_likes';
    protected $fillable = ['quiz_share_id','profile_id'];
}
