<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \App\Scopes\Profile as ScopeProfile;
use \App\Scopes\Company as ScopeCompany;
class Photo extends Model
{
    use ScopeProfile, ScopeCompany, SoftDeletes;
    
    protected $fillable = ['caption','file'];

    protected $visible = ['id','caption','photoUrl','created_at','comments','likeCount','hasLiked'];

    protected $with = ['like'];

    protected $appends = ['likeCount','hasLiked','photoUrl'];
    
    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($photo){
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
        return "/profiles/photos/" . $this->id . ".jpg";
    }
    
    public function profile()
    {
        return $this->belongsToMany('App\Profile','profile_photos','photo_id','profile_id');
    }
    
    public function getProfile(){
        return $this->profile()->first();
    }
    
    public function company()
    {
        return $this->belongsToMany('App\Company','company_photos','photo_id','company_id');
    }
   
}
