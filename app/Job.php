<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = ['title', 'description', 'type', 'location', 'annual_salary', 'functional_area', 'key_skills', 'xpected_role', 'experience_required', 'company_id'];
    
    protected $with = ['company'];
    
    public function company()
    {
        return $this->belongsTo(\App\Company::class);
    }
    
    public function applications()
    {
        return $this->hasMany(\App\Application::class);
    }
    
    public function apply($profileId)
    {
        return \DB::table('applications')->create(['job_id' => $this->id, 'profile_id' => $profileId]);
    }
    
    public function unapply($profileId)
    {
        return \DB::table('applications')->where(['job_id'=>$this->id,'profile_id'=>$profileId])->delete();
    
    }
}
