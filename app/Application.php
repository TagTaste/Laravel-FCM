<?php

namespace App;

use App\Notifications\ShortlistApplication;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;
    protected $fillable = ['job_id', 'profile_id', 'shortlisted', 'resume', 'message'];
    protected $visible = ['created_at', 'profile', 'shortlisted', 'resumeUrl', 'message'];
    protected $with = ['profile'];
    protected $appends = ['resumeUrl'];
    
    public static $tags = ['Action Pending','Shortlisted','Saved','Reject'];
    
    public function job()
    {
        return $this->belongsTo(\App\Job::class);
    }
    
    public function profile()
    {
        return $this->belongsTo(\App\Application\Profile::class);
    }
    
    public function shortlist(Profile $shortlister, $tag)
    {
        if(!isset(self::$tags[$tag]) || $this->shortlisted == $tag){
            return false;
        }
        
        $this->shortlisted = $tag;
        $this->update();
        
        if(self::$tags[$tag] === 'Shortlisted'){
            $this->profile->user->notify(new ShortlistApplication($shortlister->user->email, $shortlister->name, $this->job));
        }
        
        return true;
    }
    
    public function getResumeUrlAttribute()
    {
        return !is_null($this->resume) ? \Storage::url($this->resume) : null;
    }
    
    public static function getCounts($jobId)
    {
        $count = [];
        $counts = \DB::table("applications")->where('job_id',$jobId)->whereNull('deleted_at')
            ->select("shortlisted")->selectRaw('count(*) as count')->groupBy('shortlisted')->get();
        if($counts){
            $counts = $counts->keyBy('shortlisted');
            foreach(Application::$tags as $index => $tag){
                $count[$index] = ['tag'=> [ 'index'=>$index,'name'=>$tag],'count'=>0];
                if($counts->get($index)){
                    $count[$index]['count'] = $counts->get($index)->count;
                }
            }
        }
        return $count;
    }
    
}
