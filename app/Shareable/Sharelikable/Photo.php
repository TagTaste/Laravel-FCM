<?php

namespace App\Shareable\Sharelikable;

use Illuminate\Database\Eloquent\Model;

class Photo extends Like
{
	protected $table = 'photo_share_likes';
    protected $fillable = ['id','profile_id'];
}
