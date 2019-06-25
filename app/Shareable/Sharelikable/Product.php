<?php

namespace App\Shareable\Sharelikable;

use Illuminate\Database\Eloquent\Model;

class Product extends Like
{
    protected $table = 'public_review_share_likes';
    protected $fillable = ['public_review_share_id','profile_id'];
}
