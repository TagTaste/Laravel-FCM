<?php

namespace App\Shareable\Sharelikable;

use Illuminate\Database\Eloquent\Model;

class Shoutout extends Like
{
	protected $table = 'shoutout_share_likes';
    protected $fillable = ['shoutout_share_id','profile_id'];
}
