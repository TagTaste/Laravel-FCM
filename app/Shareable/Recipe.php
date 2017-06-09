<?php

namespace App\Shareable;

use App\Shareable\Share;

class Recipe extends Share
{
    protected $fillable = ['profile_id','photo_id','payload_id'];
    protected $visible = ['id','profile_id','created_at'];
}
