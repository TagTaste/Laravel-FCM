<?php

namespace App;

use App\Channel\Payload;
use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Traits\GetTags;
use App\Traits\HasPreviewContent;
use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redis;

class Shoutout extends Model implements Feedable
{
    use IdentifiesOwner, CachedPayload, SoftDeletes, GetTags, HasPreviewContent;

    protected $fillable = ['content', 'profile_id', 'company_id', 'flag','privacy_id','payload_id','has_tags','preview',
        'media_url','cloudfront_media_url','media_json'];

    protected $visible = ['id','content','profile_id','company_id','owner','has_tags',
        'created_at','privacy_id','privacy','image','preview','updated_at','media_url','cloudfront_media_url','media_json','mediaJson',
        'payload_id'
    ];

    protected $casts = [
        'privacy_id' => 'integer',
        'profile_id' => 'integer',
        'company_id' => 'integer',
        'has_tags' => 'integer'
    ];

    protected $appends = ['owner','likeCount','mediaJson'];

    protected $with = ['privacy'];

    public static function boot()
    {
        self::created(function($shoutout){
            $shoutout->addToCache();
        });

        self::updated(function($shoutout){
            $shoutout->addToCache();
        });    }

    public function addToCache(){
        Redis::set("shoutout:" . $this->id,$this->makeHidden(['privacy','owner'])->toJson());
    }

    public function removeFromCache()
    {
        Redis::del("shoutout:" . $this->id);
    }
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }

    public function comments()
    {
        return $this->belongsToMany('App\Comment','comments_shoutouts','shoutout_id','comment_id');
    }

    public function company()
    {
        return $this->belongsTo(\App\Shoutout\Company::class);
    }

    public function getOwnerAttribute()
    {
        return $this->owner();
    }

    public function getLikeCountAttribute()
    {
        $count = Redis::sCard("meta:shoutout:likes:" . $this->id);

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

    public function privacy()
    {
        return $this->belongsTo(Privacy::class);
    }

    public function payload()
    {
        return $this->belongsTo(Payload::class);
    }

    public function like()
    {
        return $this->hasMany(ShoutoutLike::class,'shoutout_id');
    }

    public function getMetaFor($profileId)
    {
        $meta = [];
        $meta['hasLiked'] = Redis::sIsMember("meta:shoutout:likes:" . $this->id,$profileId) === 1;
        $meta['likeCount'] = Redis::sCard("meta:shoutout:likes:" . $this->id);

        $meta['commentCount'] = $this->comments()->count();
        $peopleLike = new PeopleLike();
        $meta['peopleLiked'] = $peopleLike->peopleLike($this->id, 'shoutout' ,request()->user()->profile->id);

        $meta['shareCount']=\DB::table('shoutout_shares')->where('shoutout_id',$this->id)->whereNull('deleted_at')->count();
        $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);

        $meta['isAdmin'] = $this->company_id ? \DB::table('company_users')
            ->where('company_id',$this->company_id)->where('user_id',request()->user()->id)->exists() : false ;



        return $meta;
    }

    public function getRelatedKey() : array
    {

        $owner = $this->owner();
        $prefix = "profile";
        if($owner instanceof \App\Recipe\Profile){
            $prefix = "profile";
        } elseif ($owner instanceof \App\Shoutout\Company){
            $prefix = "company";
        }
        $key = $prefix . ":small:" . $owner->id;

        if(!Redis::exists($key)){
            Redis::set($key, $owner->toJson());
        }

        return [$prefix => $key];
    }

    public function getNotificationContent()
    {
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'content' => $this->content,
            'image' => null
        ];
    }

    public function getContentAttribute($value)
    {
        $profiles = $this->getTaggedProfiles($value);

        if($profiles){
            $value = ['text'=>$value,'profiles'=>$profiles];
        }
        return $value;
    }

    public function getImageAttribute($value)
    {
        return is_null($value) ? null : \Storage::url($value);
    }

   public function getPreviewAttribute($value)
    {

        try {
            $preview = json_decode($value,true);

            if(isset($preview['image']) && !is_null($preview['image']))
            {
                
                $preview['image'] = is_null($preview['image']) ? null : \Storage::url($preview['image']);
            }
        
            return $preview;

        } catch(\Exception $e){
            \Log::error("Could not load preview image");
//            \Log::error($preview);
//            \Log::error($e->getLine());
//            \Log::error($e->getMessage());
        }
        return empty($preview) ? null : $preview;
    }

    public function getPreviewContent()
    {
        $profile = isset($this->company_id) ? Company::getFromCache($this->company_id) : Profile::getFromCache($this->profile_id);
        $profile = json_decode($profile);
        $content = $this->getContent($this->content);
        $preview = $this->getPreviewAttribute($this->preview);
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_feed/'.$this->id;
        $data['owner'] = $profile->id;
        $data['title'] = $profile->name.' has posted on TagTaste';
        $data['description'] = substr($content,0,155);
        $data['ogTitle'] = $this->getOgTitle();
        $data['ogDescription'] = $this->getOgDescription();
        $data['ogImage'] = $this->getOgImage();
        $data['cardType'] = 'summary';
        $data['ogUrl'] = env('APP_URL').'/preview/shoutout/'.$this->id;
        $data['redirectUrl'] = env('APP_URL').'/feed/view/shoutout/'.$this->id;

        // Fetching ogImage from redis for URLs in Shoutout
        $urlRegex = '/(https?:\/\/[^\s]+)/';
        preg_match($urlRegex,$content,$matches);
        if(isset($matches[0])) {
            $preview = \App\Preview::getCached($matches[0]);
            if(!is_null($preview) && !empty($preview->image)) {
                $data['ogImage'] = $preview->image;
            }
        }

        return $data;

    }

    public static function getProfileMediaPath($profileId, $filename = null)
    {
        $relativePath = "shoutout/media/$profileId/p";
        $status = Storage::makeDirectory($relativePath,0644,true);
        return $filename === null ? $relativePath : $relativePath . "/" . $filename;
    }

    public function getmediaUrlAttribute($value)
    {
        return !is_null($value) ? \Storage::url($value) : null;
    }

    public function getmediaJsonAttribute($value)
    {
        return json_decode($value);
    }

    public function getOgTitle()
    {
        if($this->preview != null) {
            return  $this->preview["title"];
        }

        return $this->getContent($this->content)!=null ? substr($this->getContent($this->content),0,155) : "Checkout this post by ".$this->owner->name;
    }

    public function getOgDescription()
    {
        if($this->preview != null) {
            return  $this->preview["url"];
        }
        return null;
    }

    public function getOgImage()
    {
        if($this->preview != null) {
            return  isset($this->preview["image"])?$this->preview["image"]:null;
        }
        if($this->media_url != null) {
            $thumbnail = isset($this->media_json->thumbnail) ? $this->media_json->thumbnail : null;
            return $thumbnail;
        }
        return 'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-shoutout-small.png';
    }
}
