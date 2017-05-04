<?php

namespace App;

use App\Job\Type;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use SoftDeletes;
    protected $fillable = ['title', 'description', 'type', 'location',
        'annual_salary', 'functional_area', 'key_skills', 'expected_role',
        'experience_required',
        'company_id', 'type_id'

    ];
    protected $visible = ['id','title', 'description', 'type', 'location',
        'annual_salary', 'functional_area', 'key_skills', 'expected_role',
        'experience_required',
        'company_id', 'type_id', 'company', 'profile_id',
        'applications','created_at', 'expires_on'
    ];
    
    protected $with = ['company', 'applications'];
    
    protected $appends = ['type', 'profile_id'];
    
    public function hasApplied($profileId)
    {
        return $this->applications()->where('profile_id', $profileId)->count() == 1;
    }
    
    public function applications()
    {
        return $this->hasMany(\App\Application::class);
    }
    
    public function company()
    {
        return $this->belongsTo(\App\Company::class);
    }
    
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
    
    public function getTypeAttribute()
    {
        return $this->jobType->name;
    }
    
    public function jobType()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
    
    
    public function getProfileIdAttribute()
    {
        return $this->company->user->profile->id;
    }
    
    public function apply($profileId)
    {
        return \DB::table('applications')->insert(['job_id' => $this->id, 'profile_id' => $profileId, 'created_at' => Carbon::now()->toDateTimeString()]);
    }
    
    public function unapply($profileId)
    {
        return \DB::table('applications')->where(['job_id'=>$this->id,'profile_id'=>$profileId])->delete();
    }
    
    public function getMetaFor($profileId)
    {
        $meta = [];
        $meta['hasApplied'] = $this->applications()->where('profile_id',$profileId)->first() !== null;
        return $meta;
    }
    
}
