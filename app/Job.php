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
    
    protected $fillable = ['title', 'description','why_us','location','key_skills',
        'profile_id','salary_min','salary_max','experience_min','experience_max','joining',
        'company_id', 'type_id','privacy_id','resume_required','minimum_qualification'
    ];
    protected $visible = ['title', 'description','why_us', 'type', 'location','key_skills',
        'profile_id','salary_min','salary_max','experience_min','experience_max','joining',
        'company_id', 'type_id', 'company', 'profile', 'profile_id','minimum_qualification',
        'applications','created_at', 'expires_on','job_id','privacy_id'
    ];
    
    protected $with = ['company','profile', 'applications'];
    
    protected $appends = ['type','job_id'];
    
    
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
        return $this->jobType ? $this->jobType->name : null;
    }
    
    public function jobType()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
    
    
//    public function getProfileIdAttribute()
//    {
//        return $this->company->user->profile->id;
//    }
    
    public function apply($profileId,$resume = null,$message = null)
    {
        return \DB::table('applications')->insert(['job_id' => $this->id, 'profile_id' => $profileId,
            'created_at' => Carbon::now()->toDateTimeString(),'resume'=>$resume,'shortlisted'=>0,'message'=>$message]);
    }
    
    public function unapply($profileId)
    {
        return \DB::table('applications')->where(['job_id'=>$this->id,'profile_id'=>$profileId])->delete();
    }
    
    public function getMetaFor($profileId)
    {
        $meta = [];
        $meta['hasApplied'] = $this->applications()->where('profile_id',$profileId)->first() !== null;
        $meta['shareCount']=\DB::table('job_shares')->where('job_id',$this->id)->whereNull('deleted_at')->count();
        $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);
    
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
    
    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'content' => $this->title,
            'image' => null
        ];
    }
    
}
