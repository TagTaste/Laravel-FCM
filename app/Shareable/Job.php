<?php

namespace App\Shareable;

use App\Shareable\Share;

class Job extends Share
{
    protected $fillable = ['profile_id','job_id','payload_id'];
    protected $visible = ['id','profile_id','created_at'];
}
