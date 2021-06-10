<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userActivityTracking extends Model
{
    protected $table = 'user_activity_tracking';

    protected $fillable = ['profile_id', 'method', 'url'];

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';
}
