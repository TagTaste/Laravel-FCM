<?php

namespace App\V2;

use App\Channel\Payload;
use App\Interfaces\Feedable;
use App\Filter\People;
use App\PeopleLike;
use App\Privacy;
use App\Scopes\Company as ScopeCompany;
use App\Scopes\Profile as ScopeProfile;
use App\Traits\CachedPayload;
use App\Traits\GetTags;
use App\Traits\HasPreviewContent;
use App\Traits\IdentifiesOwner;
use App\Traits\IdentifiesContentIsReported;
use App\Traits\HashtagFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;

class Photo extends Model implements Feedable
{
    use ScopeProfile, ScopeCompany, SoftDeletes, GetTags, HasPreviewContent, IdentifiesContentIsReported, HashtagFactory;

    use IdentifiesOwner, CachedPayload;

    protected $fillable = ['caption','privacy_id','payload_id','images','image_meta'];

    // old
    // protected $visible = ['id','caption','likeCount',
    //     'created_at','comments',
    //     'profile_id','company_id','privacy_id','updated_at','deleted_at',
    //     'owner','nextPhotoId','previousPhotoId','images','image_meta'];

    protected $visible = ['id','caption','created_at','profile_id','company_id','privacy_id','updated_at','deleted_at','images','image_meta', 'owner'];

    protected $casts = [
        'privacy_id' => 'integer',
        'profile_id' => 'integer',
        'company_id' => 'integer',
        'has_tags' => 'integer'
    ];

    protected $with = ['like'];

    // old
    // protected $appends = ['profile_id','company_id','owner','likeCount','nextPhotoId','previousPhotoId'];
    protected $appends = ['profile_id','company_id', 'owner'];

    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        self::deleting(function($photo){
//            \DB::transaction(function() use ($photo){
//                $photo->ideabooks()->detach();
            $photo->deleteExistingHashtag('App\V2\Photo',$photo->id);
            $photo->profile()->detach();
            $photo->company()->detach();

//            });
        });

        //do not fire self::created methods here.
        //manage this in the controller.
        //self::created doesn't fire after the relationship of profile/company has been established.
        //so it can't be pushed to the feed since there won't be any "owner".

        self::created(function($photo){
            $matches = $photo->hasHashtags($photo);
            if(count($matches)) {
                $photo->createHashtag($matches,'App\V2\Photo',$photo->id);
            }
            //Redis::set("photo:" . $photo->id,$photo->makeHidden(['profile_id','company_id','owner','likeCount'])->toJson());
        });

//        self::created(function($photo){
//            $photo->addToCache();
//        });
//
       self::updated(function($photo){
        $matches = $photo->hasHashtags($photo);
        $photo->deleteExistingHashtag('App\V2\Photo',$photo->id);
        if(count($matches)) {
            $photo->createHashtag($matches,'App\V2\Photo',$photo->id);
        }
       });
    }

    public function addToCache()
    {
        $data = ['id'=>$this->id,'caption'=>$this->caption,'photoUrl'=>$this->photoUrl,'created_at'=>$this->created_at->toDateTimeString(),
            'updated_at'=>$this->updated_at->toDateTimeString(),'image_meta'=>$this->image_meta];
        Redis::set("photo:".$this->id,json_encode($data));
    }

    public function deleteFromCache()
    {
        Redis::del("photo:".$this->id);
    }

    public function ideabooks()
    {
        return $this->belongsToMany('\App\Ideabook','ideabook_photos','photo_id','ideabook_id');
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
        return $this->file;
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
        return $this->belongsToMany('App\Recipe\Company','company_photos','photo_id','company_id');
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

    public function getOwnerAttributeV2()
    {
        $data = array();
        $owner = $this->owner();
        if (!is_null($this->company_id)) {
            $data = [
                'id' => $owner->id,
                'profile_id' => $owner->profileId,
                'name' => $owner->name,
                'logo_meta' => $owner->logo_meta,
                'verified' => $owner->verified
            ];
            return $data;
        } else {
            if (!is_null($this->profile_id)) {
                $keyRequired = [
                    'id',
                    'user_id',
                    'name',
                    'designation',
                    'handle',
                    'tagline',
                    'image_meta',
                    'isFollowing',
                    'verified',
                    'is_tasting_expert'
                ];
                $data = array_intersect_key(
                    $owner->toArray(), 
                    array_flip($keyRequired)
                );
                foreach ($data as $key => $value) {
                    if (in_array($key, ["verified", "is_tasting_expert"])) {
                        continue;
                    }
                    if (is_null($value) || $value == '')
                        unset($data[$key]);
                }
                return $data;
            }
        }
        return $data;
    }

    public function privacy()
    {
        return $this->belongsTo(Privacy::class);
    }

    public function payload()
    {
        return $this->belongsTo(Payload::class,'payload_id','id');
    }

    public function isPhotoReported()
    {
        return $this->isReported(request()->user()->profile->id, "photo", (string)$this->id);
    }

    public function getMetaFor($profileId)
    {
        $meta = [];
        $key = "meta:photo:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'photo' ,request()->user()->profile->id);
        $meta['shareCount']=\DB::table('photo_shares')->where('photo_id',$this->id)->whereNull('deleted_at')->count();
        $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);
        $meta['tagged']=\DB::table('ideabook_photos')->where('photo_id',$this->id)->exists();
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id',$this->company_id)->where('user_id',request()->user()->id)->exists() : false ;
        $meta['isReported'] =  $this->isPhotoReported(); 
        return $meta;
    }

    public function getMetaForV2($profileId)
    {
        $meta = [];
        $key = "meta:photo:likes:" . $this->id;
        $meta['hasLiked'] = Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['shareCount']=\DB::table('photo_shares')->where('photo_id',$this->id)->whereNull('deleted_at')->count();
        $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);
        $meta['tagged']=\DB::table('ideabook_photos')->where('photo_id',$this->id)->exists();
        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id',$this->company_id)->where('user_id',request()->user()->id)->exists() : false ;
        $meta['isReported'] =  $this->isPhotoReported(); 
        return $meta;
    }

    public function getNotificationContent()
    {
        $image = null;
        if (isset($this->images)) {
            $image_data = $this->images;
            if (isset($image_data[0]) && isset($image_data[0]->original_photo)) {
                $image = $image_data[0]->original_photo;
            }
        }
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'content' => $this->caption,
            'image' => $image
        ];
    }

    public function getRelatedKey() : array
    {
        if(empty($this->relatedKey) && $this->profile_id !== null){
            return ['profile'=>'profile:small:' . $this->profile_id];
        }
        return ['company'=>'company:small:' . $this->company_id];
    }

    public function getCaptionAttribute($value)
    {
        $profiles = $this->getTaggedProfilesV2($value);

        if($profiles){
            $value = ['text'=>$value,'profiles'=>$profiles];
        }
        return $value;
    }

    public function getPreviewContent()
    {
        $profile = isset($this->company_id) ? Company::getFromCache($this->company_id) : Profile::getFromCache($this->profile_id);
        $profile = json_decode($profile);
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_feed/'.$this->id;
        $data['owner'] = $profile->id;
        $content = $this->getContent($this->caption);
        $data['title'] = $profile->name. ' has posted on TagTaste';
        $data['description'] = substr($content,0,150);
        $data['ogTitle'] = $profile->name. ' has posted on TagTaste';
        $data['ogDescription'] = substr($content,0,150);
        $data['ogImage'] = $this->photoUrl;
        $data['cardType'] = 'summary_large_image';
        $data['ogUrl'] = env('APP_URL').'/preview/photo/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/feed/view/photo/'.$this->id;

        return $data;

    }

    public function getNextPhotoIdAttribute()
    {
        if(isset($this->company_id) && !is_null($this->company_id))
        {
            $photoId = \DB::table('company_photos')->select('photo_id')->where('photo_id','<', $this->id)
                ->where('company_id',$this->company_id)->orderBy('photo_id','DESC')->first();
            return !is_null($photoId) ? $photoId->photo_id : null;
        }
        else if(isset($this->profile_id) && !is_null($this->profile_id))
        {
            $photoId = \DB::table('profile_photos')->select('photo_id')->where('photo_id','<', $this->id)
                ->where('profile_id',$this->profile_id)->orderBy('photo_id','DESC')->first();
            return !is_null($photoId) ? $photoId->photo_id : null;
        }
        else
        {
            return null;
        }

    }

    public function getPreviousPhotoIdAttribute()
    {
        if(isset($this->company_id) && !is_null($this->company_id))
        {
            $photoId = \DB::table('company_photos')->select('photo_id')->where('photo_id','>', $this->id)
                ->where('company_id',$this->company_id)->first();
            return !is_null($photoId) ? $photoId->photo_id : null;
        }
        else if(isset($this->profile_id) && !is_null($this->profile_id))
        {
            $photoId = \DB::table('profile_photos')->select('photo_id')->where('photo_id','>', $this->id)
                ->where('profile_id',$this->profile_id)->first();
            return !is_null($photoId) ? $photoId->photo_id : null;
        }
        else
        {
            return null;
        }
    }

    public function getImagesAttribute($value)
    {
        if($value === null){
            if($this->image_meta == null){
                return null;
            }
            else{
                return "[".$this->image_meta."]";
            }
        }
        else{
            return $value;
        }
    }

    public function getImageMetaAttribute($value)
    {
        if ($value!=null) {
            return $value;
        }
        else{
            if ($this->images != null && count($this->images)>0) {
                return json_encode($this->images[0]);
            } else {
                return null;
            }
        }
    }

    /**
     * @param int $profileId
     * @return array
     */
    public function getSeoTags() : array
    {
        $title = "TagTaste | Photo";
        $description = "";
        if (!is_null($this->caption)) {
            $description = substr(htmlspecialchars_decode($this->getContent($this->caption)),0,160)."...";
        } else {
            $description = "World's first online community for food professionals to discover, network and collaborate with each other.";
        }

        $seo_tags = [
            "title" => $title,
            "meta" => array(
                array(
                    "name" => "description",
                    "content" => $description,
                ),
                array(
                    "name" => "keywords",
                    "content" => "post, feed, user feed, tagtaste post, photo",
                )
            ),
            "og" => array(
                array(
                    "property" => "og:title",
                    "content" => $title,
                ),
                array(
                    "property" => "og:description",
                    "content" => $description,
                ),
                array(
                    "property" => "og:image",
                    // "content" => $this->photoUrl,
                    "content" => json_decode($this->image_meta)->original_photo
                )
            ),
        ];
        return $seo_tags;
    }

    public function hasHashtags($data) 
    {

        $totalMatches = [];
        if(gettype($data->caption) == 'array') {
            $content = $data->caption['text'];
        } else {
            $content = $data->caption;
        }
        if(preg_match_all('/\s#[A-Za-z0-9_]{1,50}/i',' '.$data->content,$matches)) {
            $totalMatches = array_merge($totalMatches,$matches[0]);
        }
        return $totalMatches;
    }
}
