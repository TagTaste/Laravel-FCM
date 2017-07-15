<?php

namespace App\Shareable\Sharelikable;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Like
{
	protected $table = 'recipe_share_likes';
    protected $fillable = ['recipe_share_id','profile_id'];
}
