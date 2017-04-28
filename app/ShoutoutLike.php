<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoutoutLike extends Model
{
    protected $fillable = ['profile_id', 'shoutout_id'];
}
