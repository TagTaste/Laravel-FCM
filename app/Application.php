<?php

namespace App;

use App\Notifications\ShortlistApplication;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['job_id', 'profile_id','shortlisted'];
    protected $visible = ['created_at', 'profile','shortlisted'];
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
        $this->shortlisted = 1;
        $this->update();
        
        return $this->profile->user->notify(
            new ShortlistApplication($shortlister->user->email, $shortlister->name, $this->job));
    }
    
}
