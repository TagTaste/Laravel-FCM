<?php

namespace App;

use App\Events\UpdateFeedable;
use Illuminate\Database\Eloquent\Model;

class ShoutoutLike extends Model
{
    protected $fillable = ['profile_id', 'shoutout_id'];
    
    public static function boot()
    {
        parent::boot();
        
        //Update the payload when a like is added or removed.
            self::created(function($like){
                event(new UpdateFeedable($like->shoutout));
            });
            
        //UpdateFeedable is called and not DeleteFeedable
        //since a like is child of the feed payload, and not the payload itself.
        
            self::deleting(function($like){
                event(new UpdateFeedable($like->shoutout));
            });
    }
    
    public function shoutout()
    {
        return $this->belongsTo( Shoutout::class);
    }
    
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
