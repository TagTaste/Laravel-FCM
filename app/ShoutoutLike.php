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
        //cannot use 'touches' property since a like is never updated.
        //it is either created or destroyed.
        
        //event is fired on 'deleted' and not on 'deleting' because
        //Shoutout checks whether this like exists or not.
        //on "deleting", Shoutout::hasLiked() would return true, but on 'deleted' event, the model
        //has been deleted. So Shoutout::hasLiked() won't return true.
            self::deleted(function($like){
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
