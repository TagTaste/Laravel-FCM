<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class Photo extends Model
{
    protected $fillable = ['caption','file','album_id'];

    protected $visible = ['id','caption','file','created_at','album','comments','count','hasliked'];

    protected $with = ['album','comments','like'];

     protected $appends = ['count','hasliked'];


    public static function boot()
    {
        parent::boot();

        static::deleting(function($photo){
//            \DB::transaction(function() use ($photo){
                $photo->ideabooks()->detach();
//            });
        });
    }

    public function album()
    {
        return $this->belongsTo('App\Album');
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
    

    public static function getProfileImagePath($profileId,$albumId,$filename = null)
    {
        $relativePath = "profile/$profileId/albums/$albumId/photos";
        $status = Storage::makeDirectory($relativePath,0644,true);
        if($filename === null){
            return $relativePath;
        }
        return storage_path("app/".$relativePath) . "/" . $filename;
    }
    
    public static function getCompanyImagePath($profileId,$companyId, $albumId,$filename = null)
    {
        $relativePath = "profile/$profileId/companies/$companyId/albums/$albumId/photos";
        $status = Storage::makeDirectory($relativePath,0644,true);
        if($filename === null){
            return $relativePath;
        }
        return storage_path("app/".$relativePath) . "/" . $filename;
    }

    public function getCountAttribute()
    {
        return $this->like->count();
    }
    public function getHaslikedAttribute($profileId)
    {
        
        if($this->like->where('profile_id',$profileId)->first() != null)
            {
                return true;
            }
            else
            {
                return false;
            }
           
    }
   
}
