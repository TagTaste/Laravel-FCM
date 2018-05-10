<?php

namespace App\PublicView;

use App\Company\Address;
use App\Company\Status;
use App\Company\Type;
use App\CompanyRating;
use Storage;
use App\Company as BaseCompany;

class Company extends BaseCompany
{
    protected $visible = [
        'id', 'name', 'about', 'logo', 'hero_image', 'phone', 'registered_address', 'established_on', 'type', 'tagline', 'gallery',
        'type', 'status', 'avg_rating', 'review_count', 'rating_count','followersCount','speciality'];

    protected $with = ['gallery','status','type'];

    protected $appends = ['statuses','companyTypes','avg_rating','review_count','rating_count','followersCount'];

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

    public function status()
    {
        return $this->belongsTo('App\Company\Status','status_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Company\Type','type');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
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

    public function getMetaForPublic()
    {
        $meta = [];

        return $meta;
    }

    public function getFollowersCountAttribute()
    {
        return \Redis::SCARD("followers:company:".$this->id);
    }

}
