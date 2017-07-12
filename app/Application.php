<?php

namespace App;

use App\Notifications\ShortlistApplication;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['job_id', 'profile_id', 'shortlisted', 'resume'];
    protected $visible = ['created_at', 'profile', 'shortlisted', 'resumeUrl'];
    protected $with = ['profile'];
    protected $appends = ['resumeUrl'];
    
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
        if ($this->shortlisted === 1) {
            return false;
        }
        
        $this->shortlisted = 1;
        $this->update();
        
        $this->profile->user->notify(new ShortlistApplication($shortlister->user->email, $shortlister->name, $this->job));
        
        return true;
    }
    
    public function getResumeUrlAttribute()
    {
        return $this->resume !== null ? "/profile/" . $this->profile_id . "/job/" . $this->job_id . "/resume/" . $this->resume : null;
    }
    
}
