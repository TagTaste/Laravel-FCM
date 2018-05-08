<?php

namespace App\Recipe;

use Storage;
use App\Company as BaseCompany;

class Company extends BaseCompany
{
    protected $fillable = [];

    protected $visible = ['id', 'name', 'about', 'logo', 'hero_image', 'tagline', 'created_at', 'speciality', 'profileId', 'handle', 'city',
        'isFollowing','style_logo', 'style_hero_image', 'company_id'];

    protected $appends = ['profileId','is_admin','isFollowing','company_id'];

    public function getCompanyIdAttribute()
    {
        return $this->id;
    }

    public function photos()
    {
        return $this->belongsToMany('App\Photo','company_photos','company_id','photo_id');
    }

    public function awards()
    {
        return $this->belongsToMany('App\Company\Award','company_awards','company_id','award_id');
    }

    //company creater user
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function websites()
    {
        return $this->hasMany('App\Company\Website','company_id','id');
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
        if(is_null($value))
        {
            $fileId = 14 - $this->id % 14;
            return "https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/app/bannerImage/".$fileId.".jpg";
        }
        else
        {
            return \Storage::url($value);
        }
    }

    public function getProfileIdAttribute()
    {
        return $this->user->profile->id;
    }

    public function getIsFollowingAttribute()
    {
        return $this->isFollowing(request()->user()->profile->id);
    }
    public function isFollowing($followerProfileId = null)
    {
        return \Redis::sIsMember("following:profile:" . $followerProfileId,"company." . $this->id) === 1;
    }

    public function getIsAdminAttribute()
    {
        $userId = request()->user()->id;
        return $this->users()->where('user_id','=',$userId)->exists();
    }

    //added by manda.
    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'content' => $this->name,
            'image' => $this->logo
        ];
    }

}
