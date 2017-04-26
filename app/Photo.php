<?php

namespace App;

use App\Channel\Payload;
use App\Interfaces\Feedable;
use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Scopes\Profile as ScopeProfile;
use \App\Scopes\Company as ScopeCompany;

class Photo extends Model implements Feedable
{
    use ScopeProfile, ScopeCompany, SoftDeletes;
    
    use IdentifiesOwner;
    
    protected $fillable = ['caption','file','privacy_id','payload_id'];

    protected $visible = ['id','caption','photoUrl',
        'created_at','comments','likeCount','hasLiked',
        'profile_id','company_id','privacy_id',
        'owner'];

    protected $with = ['like'];

    protected $appends = ['likeCount','hasLiked','photoUrl','profile_id','company_id','owner'];
    
    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        self::deleting(function($photo){
//            \DB::transaction(function() use ($photo){
                $photo->ideabooks()->detach();
//            });
        });
    }


    public function ideabooks()
    {
        return $this->belongsToMany('\App\Ideabook','ideabook_photos','photo_id','ideabook_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return date("d-m-Y",strtotime($value));
    }

    public function comments()
    {
        return $this->belongsToMany('App\Comment','comments_photos','photo_id','comment_id');
    }

    public function like()
    {
        return $this->hasMany('App\PhotoLike','photo_id');
    }
    
    public static function getProfileImagePath($profileId,$filename = null)
    {
        $relativePath = "profile/$profileId/photos";
        $status = Storage::makeDirectory($relativePath,0644,true);
        if($filename === null){
            return $relativePath;
        }
        return storage_path("app/".$relativePath) . "/" . $filename;
    }
    
    public static function getCompanyImagePath($profileId,$companyId, $filename = null)
    {
        $relativePath = "profile/$profileId/companies/$companyId/photos";
        $status = Storage::makeDirectory($relativePath,0644,true);
        if($filename === null){
            return $relativePath;
        }
        return storage_path("app/".$relativePath) . "/" . $filename;
    }

    public function getLikeCountAttribute()
    {
        $count = $this->like->count();
        
        if($count >1000000)
        {
            $count = round($count/1000000, 1);
            $count = $count."M";

        }
        elseif ($count>1000) {
            $count = round($count/1000, 1);
            $count = $count."K";
        }
        return $count;
    }
    
    public function getHasLikedAttribute()
    {
       return $this->like->count() === 1;
    }
    
    public function getPhotoUrlAttribute()
    {
        $profileId = $this->getProfile()->id;
        return "/profiles/" . $profileId . "/photos/" . $this->id . ".jpg";
    }
    
    public function profile()
    {
        return $this->belongsToMany('App\Profile','profile_photos','photo_id','profile_id');
    }
    
    public function getProfile(){
        return $this->profile->first();
    }
    
    public function company()
    {
        return $this->belongsToMany('App\Company','company_photos','photo_id','company_id');
    }
    
    public function getCompany()
    {
        return $this->company->first();
    }
    
    public function getProfileIdAttribute()
    {
        $profile = $this->getProfile();
        
        return $profile !== null ? $profile->id : null;
    }
    
    public function getCompanyIdAttribute()
    {
        $company = $this->getCompany();
        
        return $company !== null ? $company->id : null;
    }
    
    public function owner()
    {
        $profile = $this->getProfile();
        \Log::info($profile);
        if($profile){
            return $profile;
        }
        
        return $this->getCompany();
    }
    
    public function getOwnerAttribute()
    {
        return $this->owner();
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
