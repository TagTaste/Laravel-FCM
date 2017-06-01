<?php

namespace App;

use App\Channel\Payload;
use App\Interfaces\Feedable;
use App\Job\Type;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model implements Feedable
{
    use SoftDeletes, IdentifiesOwner, CachedPayload;
    
    protected $fillable = ['title', 'description', 'type', 'location',
        'annual_salary', 'functional_area', 'key_skills', 'expected_role',
        'experience_required','profile_id',
        'company_id', 'type_id','privacy_id'

    ];
    protected $visible = ['title', 'description', 'type', 'location',
        'annual_salary', 'functional_area', 'key_skills', 'expected_role',
        'experience_required',
        'company_id', 'type_id', 'company', 'profile_id',
        'applications','created_at', 'expires_on','job_id','privacy_id'
    ];
    
    public static function boot()
    {
        self::created(function($model){
    
            \App\Documents\Job::create($model);
    
            \Redis::set("job:" . $model->id,$model->makeHidden(['privacy','owner','company','applications'])->toJson());
        });
    
        self::updated(function($model){
            \Redis::set("job:" . $model->id,$model->makeHidden(['privacy','owner','company','applications'])->toJson());
        });
    }
    
    protected $with = ['company', 'applications'];
    
    protected $appends = ['type','job_id'];
    
    public function getJobIdAttribute()
    {
        return $this->id;
    }
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
    
    
//    public function getProfileIdAttribute()
//    {
//        return $this->company->user->profile->id;
//    }
    
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
    
    public function shortlisted()
    {
        return $this->applications()->where('shortlisted',1)->get();
    }
    
    public function privacy()
    {
        return $this->belongsTo(Privacy::class);
    }
    
    public function payload()
    {
        return $this->belongsTo(Payload::class);
    }
    
}
