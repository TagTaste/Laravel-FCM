<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['job_id', 'profile_id'];
    protected $visible = ['created_at', 'profile', 'name'];
    protected $with = ['profile'];
    protected $appends = ['name'];
    public function job()
    {
        return $this->belongsTo(\App\Job::class);
    }
    
    public function profile()
    {
        return $this->belongsTo(\App\Application\Profile::class);
    }
    
    public function getNameAttribute()
    {
        return $this->profile->user->name;
    }
}
