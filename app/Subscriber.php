<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = ['channel_name', 'profile_id','timestamp'];
    
    public function profile()
    {
        return $this->belongsTo('profile_id');
    }
}
