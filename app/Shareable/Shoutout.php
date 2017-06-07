<?php

namespace App\Shareable;

use App\Shareable\Share;

class Shoutout extends Share
{
    protected $fillable = ['profile_id','shoutout_id','payload_id'];
    protected $visible = ['id','profile_id'];
}
