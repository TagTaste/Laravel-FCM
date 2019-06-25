<?php

namespace App\PublicView;

use App\Privacy;
use App\Traits\GetTags;
use App\Traits\HasPreviewContent;
use App\Traits\IdentifiesOwner;
use App\Shoutout as BaseShoutout;

class Shoutout extends BaseShoutout
{
    use IdentifiesOwner, GetTags, HasPreviewContent;

    protected $visible = ['id','content','profile_id','company_id','owner','has_tags',
        'created_at','privacy_id','privacy','image','preview','updated_at','thumbnail'
    ];

    protected $appends = ['owner','thumbnail'];

    public function profile()
    {
        return $this->belongsTo(\App\PublicView\Profile::class);
    }

    public function company()
    {
        return $this->belongsTo(\App\PublicView\Company::class);
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

    public function getMetaForPublic()
    {
        $meta = [];
        $meta['likeCount'] = \Redis::sCard("meta:shoutout:likes:" . $this->id);
        $meta['commentCount'] = $this->comments()->count();
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
//            \Log::error($preview);
            \Log::error($e->getLine());
            \Log::error($e->getMessage());
        }
        return empty($preview) ? null : $preview;
    }

    public function getthumbnailAttribute()
    {
        return isset($this->media_json->thumbnail) ? $this->media_json->thumbnail :null;

    }

}
