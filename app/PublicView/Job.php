<?php

namespace App;

use App\Channel\Payload;
use App\Interfaces\Feedable;
use App\Job\Type;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use App\Traits\JobInternshipDate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use SoftDeletes, IdentifiesOwner,JobInternshipDate;

    protected $visible = ['title', 'description','why_us', 'type', 'location','key_skills',
        'profile_id','salary_min','salary_max','experience_min','experience_max','joining',
        'company_id', 'type_id', 'company', 'profile', 'profile_id','minimum_qualification',
        'applications','created_at', 'expires_on','job_id','privacy_id','resume_required',
        'start_month','start_year','end_month','end_year','deleted_at','updated_at'
    ];


    public function getJobIdAttribute()
    {
        return $this->id;
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

    public function getMetaFor($profileId)
    {
        $meta = [];
        $meta['hasApplied'] = $this->applications()->where('profile_id',$profileId)->first() !== null;
        $meta['shareCount']=\DB::table('job_shares')->where('job_id',$this->id)->whereNull('deleted_at')->count();
        $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id',$this->company_id)->where('user_id',request()->user()->id)->exists() : false ;

        return $meta;
    }

    public function privacy()
    {
        return $this->belongsTo(Privacy::class);
    }

    public function payload()
    {
        return $this->belongsTo(Payload::class);
    }

    public function getPreviewContent()
    {
        $profile = isset($this->company_id) ? Company::getFromCache($this->company_id) : Profile::getFromCache($this->profile_id);
        $profile = json_decode($profile);
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_feed/'.$this->id;
        $data['owner'] = $profile->id;
        $data['title'] = $profile->name. ' has opened a job opportunity for '.substr($this->title,0,65);
        $data['description'] = substr($this->description,0,155);
        $data['ogTitle'] = $profile->name. ' has opened a job opportunity for '.substr($this->title,0,65);
        $data['ogDescription'] = substr($this->description,0,155);
        $data['ogImage'] = 'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-job-big.png';
        $data['cardType'] = 'summary';
        $data['ogUrl'] = env('APP_URL').'/preview/jobs/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/jobs/'.$this->id;

        return $data;

    }

}
