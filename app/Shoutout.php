<?php

namespace App;

use App\Channel\Payload;
use App\Interfaces\CommentNotification;
use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shoutout extends Model implements Feedable
{
    use IdentifiesOwner, CachedPayload, SoftDeletes;
    
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
        $meta['likeCount'] = \Redis::hget("shoutout:" . $this->id . ":meta","like") ?: 0;

        $idLiked = $this->like()->select('profile_id')->take(3)->get();
        $meta['peopleLiked'] = \App\User::whereIn('id',$idLiked)->select('name')->get();

        $meta['commentCount'] = $this->comments()->count();

        $meta['shareCount']=\DB::table('shoutout_shares')->where('shoutout_id',$this->id)->whereNull('deleted_at')->count();
        $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);
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
        if($this->has_tags === 0){
            return $value;
        }
        
        $found = preg_match_all('/@\[([0-9]*):([0-9]*)\]/i',$value,$matches);
        if($found === false){
            return $value;
        }
        
        $profiles = Profile::getMultipleFromCache($matches[1]);
        
        if(!$profiles){
            return $value;
        }
        
        $value = [
            'text' => $value,
            'profiles' => $profiles
        ];
        
        return $value;
    }
}
