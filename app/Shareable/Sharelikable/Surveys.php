<?php

namespace App\Shareable\Sharelikable;

use Illuminate\Database\Eloquent\Model;

class Surveys extends Like
{
    protected $table = 'surveys_share_likes';
    protected $fillable = ['surveys_share_id','profile_id'];
}
