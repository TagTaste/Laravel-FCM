<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Model
{
    use SoftDeletes;
    protected $fillable = ['channel_name', 'profile_id','timestamp'];
    
    public function profile()
    {
        return $this->belongsTo('profile_id');
    }
    
    public function channel()
    {
        return $this->belongsTo(Channel::class,'channel_name','name');
    }
}
