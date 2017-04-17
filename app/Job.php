<?php

namespace App;

use App\Job\Type;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = ['title', 'description', 'type', 'location', 'annual_salary', 'functional_area', 'key_skills', 'expected_role', 'experience_required', 'company_id'];
    
    protected $with = ['company'];
    
    public function company()
    {
        return $this->belongsTo(\App\Company::class);
    }
    
    public function applications()
    {
        return $this->hasMany(\App\Application::class);
    }
    
    public function getTypeAttribute()
    {
        return $this->type->name;
    }
    
    public function type()
    {
        return $this->hasOne(Type::class);
    }
    
    public function apply($profileId)
    {
        return \DB::table('applications')->insert(['job_id' => $this->id, 'profile_id' => $profileId]);
    }
    
    public function unapply($profileId)
    {
        return \DB::table('applications')->where(['job_id'=>$this->id,'profile_id'=>$profileId])->delete();
        
    }
}
