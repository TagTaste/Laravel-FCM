<?php

namespace App\Shareable\Sharelikable;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Like
{
	protected $table = 'recipe_share_likes';
     public $timestamps = false;

    protected $fillable = ['id','profile_id'];
}
