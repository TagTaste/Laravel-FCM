<?php

namespace App;

use App\Interfaces\Feedable;
use App\Traits\IdentifiesOwner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Shoutout extends Model implements Feedable
{
    use IdentifiesOwner;
    
    protected $fillable = ['content', 'profile_id', 'company_id', 'flag','privacy_id'];
    
    protected $visible = ['content','profile_id','company_id','owner','created_at','likeCount','privacy_id','privacy'];
    
    protected $appends = ['owner','likeCount'];
    
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
        return 0;
    }
    
    public function privacy()
    {
        return $this->belongsTo(Privacy::class);
    }
    
    public function getCreatedAtAttribute()
    {
        $createdAt =new Carbon($this->attributes['created_at']);
        return $createdAt->diffForHumans();
    }
}
