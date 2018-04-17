<?php

namespace App\PublicView;

use App\Job\Type;
use App\Traits\IdentifiesOwner;
use App\Traits\JobInternshipDate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Job as BaseJob;

class Job extends BaseJob
{
    use SoftDeletes, IdentifiesOwner,JobInternshipDate;

    protected $visible = ['id','title', 'description','why_us', 'type', 'location','key_skills',
        'profile_id','salary_min','salary_max','experience_min','experience_max','joining',
        'company_id', 'type_id', 'profile_id','minimum_qualification',
        'applications','created_at', 'expires_on','job_id','privacy_id','resume_required',
        'start_month','start_year','end_month','end_year','deleted_at','updated_at','owner'
    ];

    protected $appends = ['owner'];

    public function getJobIdAttribute()
    {
        return $this->id;
    }

    public function company()
    {
        return $this->belongsTo(\App\PublicView\Company::class);
    }

    public function profile()
    {
        return $this->belongsTo(\App\PublicView\Profile::class);
    }

    public function getOwnerAttribute()
    {
        return $this->owner();
    }

    public function getTypeAttribute()
    {
        return $this->jobType ? $this->jobType->name : null;
    }

    public function jobType()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function getMetaForPublic()
    {
        $meta = [];

        return $meta;
    }

    public function privacy()
    {
        return $this->belongsTo(Privacy::class);
    }

}
