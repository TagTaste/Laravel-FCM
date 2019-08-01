<?php

namespace App\PublicView;

use App\Channel\Payload;
use App\Interfaces\Feedable;
use App\Scopes\Company as ScopeCompany;
use App\Scopes\Profile as ScopeProfile;
use App\Traits\CachedPayload;
use App\Traits\GetTags;
use App\Traits\HasPreviewContent;
use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use App\Photo as BasePhoto;
use Illuminate\Support\Facades\Redis;

class Photo extends BasePhoto
{
    use ScopeProfile, ScopeCompany, GetTags, HasPreviewContent;

    use IdentifiesOwner;

    protected $visible = ['id','caption','photoUrl','likeCount',
        'created_at', 'profile_id','company_id','privacy_id','updated_at','deleted_at',
        'owner'];

    protected $appends = ['photoUrl','profile_id','company_id','owner'];


    public static function getProfileImagePath($profileId,$filename = null)
    {
        $relativePath = "images/ph/$profileId/p";
        $status = Storage::makeDirectory($relativePath,0644,true);
        return $filename === null ? $relativePath : $relativePath . "/" . $filename;
    }

    public static function getCompanyImagePath($profileId,$companyId, $filename = null)
    {
        $relativePath = "images/ph/$companyId/c";
        $status = Storage::makeDirectory($relativePath,0644,true);
        return $filename === null ? $relativePath : $relativePath . "/" . $filename;
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

    public function getPhotoUrlAttribute()
    {
        if($this->profile_id) {
            return !is_null($this->file) ? $this->file : null;
        }

        return !is_null($this->file) ? $this->file : null;
    }

    public function profile()
    {
        return $this->belongsToMany('App\Recipe\Profile','profile_photos','photo_id','profile_id');
    }

    public function getProfile(){
        return $this->profile->first();
    }

    public function company()
    {
        return $this->belongsToMany('App\PublicView\Company','company_photos','photo_id','company_id');
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

    public function getMetaForPublic()
    {
        $meta = [];
        $key = "meta:photo:likes:" . $this->id;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        return $meta;
    }

    public function getCaptionAttribute($value)
    {
        $profiles = $this->getTaggedProfiles($value);

        if($profiles){
            $value = ['text'=>$value,'profiles'=>$profiles];
        }
        return $value;
    }

}
