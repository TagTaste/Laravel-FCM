<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollingLike extends Model
{
    protected $table = 'polling_likes';

    protected $fillable = ['poll_id','profile_id'];

    public function polling()
    {
        return $this->belongsToMany('App\Polling','poll_id');
    }
}
