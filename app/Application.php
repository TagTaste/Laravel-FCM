<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['job_id', 'profile_id'];
    
    public function jobs()
    {
        return $this->belongsToMany(\App\Job::class);
    }
    
    public function profiles()
    {
        return $this->belongsToMany(\App\Profile::class);
    }
}
