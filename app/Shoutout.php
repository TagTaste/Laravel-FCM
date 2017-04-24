<?php

namespace App;

use App\Interfaces\Feedable;
use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;

class Shoutout extends Model implements Feedable
{
    use IdentifiesOwner;
    
    protected $fillable = ['content', 'profile_id', 'company_id', 'flag'];
    
    protected $visible = ['content','profile_id','company_id','owner','created_at','likeCount'];
    
    protected $appends = ['owner','likeCount'];
    
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
}
