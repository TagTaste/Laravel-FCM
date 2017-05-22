<?php

namespace App;

use App\Channel\Payload;
use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;

class Shoutout extends Model implements Feedable
{
    use IdentifiesOwner, CachedPayload;
    
    protected $fillable = ['content', 'profile_id', 'company_id', 'flag','privacy_id','payload_id'];
    
    protected $visible = ['id','content','profile_id','company_id','owner',
        'created_at','privacy_id','privacy'
    ];
    
    protected $appends = ['owner','likeCount'];
    
    protected $with = ['privacy'];
    
    public static function boot()
    {
        self::created(function($shoutout){
            \Redis::set("shoutout:" . $shoutout->id,$shoutout->makeHidden(['privacy','owner'])->toJson());
        });
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
        $meta['hasLiked'] = $this->like()->where('profile_id',$profileId)->first() !== null;
        $meta['likeCount'] = \Redis::hget("shoutout:" . $this->id . ":meta","like");
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
        
        if(!\Redis::exists($key)){
            \Redis::set($key, $owner->toJson());
        }
        
        return [$prefix => $key];
    
        
    
    }
}
