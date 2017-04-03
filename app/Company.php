<?php

namespace App;

use App\Company\Address;
use App\Company\Advertisement;
use App\Company\Book;
use App\Company\Patent;
use App\Company\Portfolio;
use App\Company\Status;
use App\Company\Type;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Company extends Model
{
    protected $fillable = [
        'name',
        'about',
        'logo',
        'hero_image',
        'phone',
        'email',
        'registered_address',
        'established_on',
        'type',
        'employee_count',
        'client_count',
        'annual_revenue_start',
        'annual_revenue_end',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'instagram_url',
        'youtube_url',
        'pinterest_url',
        'google_plus_url',
        'tagline',
        'establishments',
        'cuisines',
        'websites',
        'milestones',
        'speciality'
    ];
    
    protected $visible = [
        'id',
        'name',
        'about',
        'logo',
        'hero_image',
        'phone',
        'email',
        'registered_address',
        'established_on',
        'type',
        'employee_count',
        'client_count',
        'annual_revenue_start',
        'annual_revenue_end',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'instagram_url',
        'youtube_url',
        'pinterest_url',
        'google_plus_url',
        'tagline',
        'establishments',
        'cuisines',
        'websites',
        'advertisements','addresses','type','status','awards','albums','patents','books','portfolio',
        'created_at',
        'milestones',
        'speciality'
    ];


    protected $with = ['advertisements','addresses','type','status','awards','albums','patents','books','portfolio'];


    protected $appends = ['statuses','companyTypes'];

    public static function boot()
    {
        parent::boot();

        static::created(function(Company $company){
            $album = Album::createDefault();

            $company->albums()->attach($album->id);
        });

    }

    public function setEstablishedOnAttribute($value)
    {
        $this->attributes['established_on'] = date("Y-m-d",strtotime($value));
    }

    public function albums()
    {
        return $this->belongsToMany('App\Album','company_albums','company_id','album_id');
    }

    public function awards()
    {
        return $this->belongsToMany('App\Company\Award','company_awards','company_id','award_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function status()
    {
        return $this->belongsTo('App\Company\Status','status_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Company\Type','type');
    }

    public function websites()
    {
        return $this->hasMany('App\Company\Website','company_id','id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }

    public function patents()
    {
        return $this->hasMany(Patent::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function getStatusesAttribute($value = null)
    {
        return Status::all()->pluck('name','id');
    }

    public function getCompanyTypesAttribute($value = null)
    {
        return Type::all()->pluck('name','id');
    }
    
    public function portfolio()
    {
        return $this->hasMany(\App\Company\Portfolio::class);
    }
    
    //there should be a better way to write the paths.
    public static function getLogoPath($profileId,$id, $filename = null)
    {
        $relativePath = "profile/{$profileId}/companies/{$id}/logos";
        Storage::makeDirectory($relativePath);
        if($filename === null){
            return $relativePath;
        }
        return storage_path("app/" . $relativePath . "/" . $filename);
        
    }
    
    //there should be a better way to write the paths.
    public static function getHeroImagePath($profileId, $id, $filename = null)
    {
        $relativePath = "profile/{$profileId}/companies/{$id}/hero_images";
        Storage::makeDirectory($relativePath);
        if($filename == null){
            return $relativePath;
        }
        return storage_path("app/" . $relativePath . "/" . $filename);
    }
    
    public function jobs()
    {
        return $this->hasMany(\App\Job::class);
    }
    
    public function applications()
    {
        return $this->jobs()->join('applications','jobs.id','=','applications.job_id')
            ->join('profiles','applications.profile_id','=','profiles.id')
            ->where('jobs.company_id',$this->id)->get();
    }
}
