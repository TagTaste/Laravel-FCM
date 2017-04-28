<?php

namespace App;

use App\Channel\Payload;
use App\Interfaces\Feedable;
use App\Traits\IdentifiesOwner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Shoutout extends Model implements Feedable
{
    use IdentifiesOwner;
    
    protected $fillable = ['content', 'profile_id', 'company_id', 'flag','privacy_id','payload_id'];
    
    protected $visible = ['id','content','profile_id','company_id','owner',
        'created_at','likeCount','privacy_id','privacy',
        'hasLiked'
    
    ];
    
    protected $appends = ['owner','likeCount','hasLiked'];
    
    protected $with = ['privacy'];
    
    public function profile()
    {
        return $this->belongsTo(\App\Recipe\Profile::class);
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class);
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
    
    public function getHasLikedAttribute()
    {
        return $this->like->count() === 1;
    }
}
