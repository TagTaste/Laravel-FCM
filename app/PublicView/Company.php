<?php

namespace App\PublicView;

use App\Company\Address;
use App\Company\Advertisement;
use App\Company\Affiliation;
use App\Company\Book;
use App\Company\Coreteam;
use App\Company\Patent;
use App\Company\Status;
use App\Company\Type;
use App\Traits\PushesToChannel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;
use App\Company as BaseCompany;

class Company extends BaseCompany
{
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
        'tagline'
    ];

    private $empValue = ['1','2 - 10','11 - 50','51 - 200','201 - 500','501 - 1000','1001 - 5000','5001 - 10,000','10,000+'];

    public function getEmployeeCountArrayAttribute()
    {
        return $this->empValue;
    }

    public function getEmployeeCountValueAttribute()
    {
        return isset($this->empValue[$this->employee_count]) ? $this->empValue[$this->employee_count] : null;
    }

    public function photos()
    {
        return $this->belongsToMany('App\PublicView\Photos','company_photos','company_id','photo_id');
    }

    public function awards()
    {
        return $this->belongsToMany('App\Company\Award','company_awards','company_id','award_id');
    }

    public function coreteam()
    {
        return $this->hasMany(Coreteam::class);
    }

    public function affiliation()
    {
        return $this->hasMany(Affiliation::class);
    }

    //company creater user
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function users()
    {
        return $this->belongsToMany('App\User','company_users','company_id','user_id');
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

    public function rating()
    {
        return $this->hasMany(CompanyRating::class,'company_id');
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
        $relativePath = "images/c/{$id}/l";

        Storage::makeDirectory($relativePath);
        if($filename === null){
            return $relativePath;
        }
        return storage_path("app/" . $relativePath . "/" . $filename);

    }

    //there should be a better way to write the paths.
    public static function getHeroImagePath($profileId, $id, $filename = null)
    {
        $relativePath = "images/c/{$id}/hi";
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

    public function collaborate()
    {
        return $this->hasMany(\App\Collaborate::class);
    }

    public function shoutouts()
    {
        return $this->hasMany(Shoutout::class);
    }

    /**
     * Get Company User Profiles
     *
     * @return \App\CompanyUser[]|null
     */
    public function getUsers()
    {
        return \App\CompanyUser::with('profile')->where("company_id",$this->id)->get();
    }


    /**
     * Add User to Company
     *
     * @param $userId
     * @return bool|void
     */
    /**
     * Remove user from company;
     *
     * @param $userId
     * @return bool
     */

    public function getLogoAttribute($value)
    {
        try{
            return !is_null($value) ? \Storage::url($value) : null;
        } catch (\Exception $e){
            \Log::warning("Couldn't get logo for company" . $this->id);
            \Log::warning($e->getMessage());
        }
    }

    public function getHeroImageAttribute($value)
    {
        try {
            return !is_null($value) ? \Storage::url($value) : null;

        } catch (\Exception $e){
            \Log::warning("Couldn't get hero image for company" . $this->id);
            \Log::warning($e->getMessage());
        }

    }

    public function getAvgRatingAttribute()
    {
        return $this->rating()->avg('rating');
    }

    public function getReviewCountAttribute()
    {
        return $this->rating()->whereNotNull('title')->count();
    }

    public function getRatingCountAttribute()
    {
        return $this->rating()->count();
    }

    public function gallery()
    {
        return $this->hasMany(\App\Company\Gallery::class);
    }

    public function getPreviewContent()
    {
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_profile/'.$this->id;
        $data['owner'] = $this->id;
        $data['title'] = 'Check out '.$this->name.' on TagTaste';
        $data['description'] = substr($this->about,0,155);
        $data['ogTitle'] = 'Check out '.$this->name.' on TagTaste';
        $data['ogDescription'] = substr($this->about,0,155);
        $data['ogImage'] = $this->logo;
        $data['cardType'] = 'summary_large_image';
        $data['ogUrl'] = env('APP_URL').'/companies/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/companies/'.$this->id;
        if(empty($this->logo)) {
            $data['cardType'] = 'summary';
        }

        return $data;

    }

}
