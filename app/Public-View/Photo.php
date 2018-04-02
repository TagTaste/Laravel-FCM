<?php

namespace App;

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

class Photo extends Model
{
    use ScopeProfile, ScopeCompany, GetTags, HasPreviewContent;

    use IdentifiesOwner;

    protected $visible = ['id','caption','photoUrl','likeCount',
        'created_at', 'profile_id','company_id','privacy_id','updated_at','deleted_at',
        'owner'];

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
            return !is_null($this->file) ? \Storage::url($this->file) : null;
        }

        return !is_null($this->file) ? \Storage::url($this->file) : null;
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

    public function getMetaFor($profileId)
    {
        $meta = [];
        $key = "meta:photo:likes:" . $this->id;
        $meta['hasLiked'] = \Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = \Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'photo' ,request()->user()->profile->id);
        $meta['shareCount']=\DB::table('photo_shares')->where('photo_id',$this->id)->whereNull('deleted_at')->count();
        $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);
        $meta['tagged']=\DB::table('ideabook_photos')->where('photo_id',$this->id)->exists();
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id',$this->company_id)->where('user_id',request()->user()->id)->exists() : false ;

        return $meta;
    }

    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'content' => $this->caption,
            'image' => $this->photoUrl
        ];
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
