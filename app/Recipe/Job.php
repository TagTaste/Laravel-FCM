<?php

namespace App\Recipe;

use Storage;
use App\Job as BaseJob;

class Job extends BaseJob
{
    protected $fillable = [];

    protected $visible = ['title', 'description','why_us', 'type', 'location','key_skills',
        'profile_id','salary_min','salary_max','experience_min','experience_max','joining',
        'company_id', 'type_id', 'company', 'profile','minimum_qualification',
        'applications','created_at', 'expires_on','privacy_id','resume_required',
        'start_month','start_year','end_month','end_year','deleted_at','updated_at'];

    protected $with = ['profile','company'];

    protected $appends = ['type','job_id'];

    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }

    public function company()
    {
        return $this->belongsTo(\App\Recipe\Company::class);
    }

    public function getTypeAttribute()
    {
        return $this->jobType ? $this->jobType->name : null;
    }

    public function jobType()
    {
        return $this->belongsTo(BaseJob\Type::class, 'type_id');
    }

    public function getJobIdAttribute()
    {
        return $this->id;
    }

}
