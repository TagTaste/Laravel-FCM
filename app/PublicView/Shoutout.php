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

class Shoutout extends Model
{
    use IdentifiesOwner, GetTags, HasPreviewContent;

    protected $visible = ['id','content','profile_id','company_id','owner','has_tags',
        'created_at','privacy_id','privacy','image','preview','updated_at'
    ];
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
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
        $count = \Redis::sCard("meta:shoutout:likes:" . $this->id);

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

    public function like()
    {
        return $this->hasMany(ShoutoutLike::class,'shoutout_id');
    }

    public function getMetaFor($profileId)
    {
        $meta = [];

        return $meta;
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
            \Log::error($preview);
            \Log::error($e->getLine());
            \Log::error($e->getMessage());
        }
        return empty($preview) ? null : $preview;
    }

    public function getPreviewContent()
    {
        $profile = isset($this->company_id) ? Company::getFromCache($this->company_id) : Profile::getFromCache($this->profile_id);
        $profile = json_decode($profile);
        $content = $this->getContent($this->content);
        $data = [];
        $data['modelId'] = $this->id;
        $data['deeplinkCanonicalId'] = 'share_feed/'.$this->id;
        $data['owner'] = $profile->id;
        $data['title'] = $profile->name.' has posted on TagTaste';
        $data['description'] = substr($content,0,155);
        $data['ogTitle'] = $profile->name. ' has posted on TagTaste';
        $data['ogDescription'] = substr($content,0,155);
        $data['ogImage'] = 'https://s3.ap-south-1.amazonaws.com/static3.tagtaste.com/images/share/share-shoutout-small.png';
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
}
