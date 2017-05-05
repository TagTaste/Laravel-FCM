<?php

namespace App;

use App\Notifications\ShortlistApplication;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['job_id', 'profile_id'];
    protected $visible = ['created_at', 'profile'];
    protected $with = ['profile'];
    
    public function job()
    {
        return $this->belongsTo(\App\Job::class);
    }
    
    public function profile()
    {
        return $this->belongsTo(\App\Application\Profile::class);
    }
    
    public function shortlist(Profile $shortlister)
    {
        return $this->profile->user->notify(
            new ShortlistApplication($shortlister->user->email, $shortlister->name, $this->job));
    }
    
}
