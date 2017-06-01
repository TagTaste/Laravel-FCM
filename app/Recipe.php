<?php

namespace App;

use App\Channel\Payload;
use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model implements Feedable
{
    use SoftDeletes, CachedPayload, IdentifiesOwner;
    
    public static $expectsFiles = true;
    public static $fileInputs = ['image' => 'images/r'];
    protected $fillable = ['name','showcase','description','content', 'ingredients',
        'category', 'serving', 'calorie', 'image',
        'preparation_time','cooking_time','level','tags',
        'profile_id','privacy_id','payload_id'];
    protected $dates = ['created_at','deleted_at'];
    protected $visible = ['id','name','description','content','ingredients','imageUrl','category','serving', 'calorie',
        'preparation_time','cooking_time','level','tags','likeCount',
        'created_at','pivot','profile'];
    protected $with = ['profile'];
    protected $appends = ['imageUrl','likeCount'];
    
    public static function boot()
    {
        self::created(function($recipe){
            //$recipe = \DB::table('recipes')->find($recipe->id);
            \Redis::set("recipe:" . $recipe->id,$recipe->makeHidden(['profile','likeCount'])->toJson());
        });
    }
    public function profile() {
    	return $this->belongsTo(\App\Recipe\Profile::class);
    }

    //specific for API
    public function getImageUrlAttribute()
    {
        return $this->image !== null ? "/images/r/" . $this->image : null;
    }
    
    public function comments()
    {
        return $this->belongsToMany('App\Comment','comments_recipes','recipe_id','comment_id');
    }
    
    public function like()
    {
        return $this->hasMany('App\RecipeLike', 'recipe_id');
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
    
    public function owner()
    {
        return $this->profile;
    }
    
    public function payload()
    {
        return $this->belongsTo(Payload::class);
    }
    
    public function getMetaFor($profileId)
    {
        $meta = [];
        $meta['hasLiked'] = $this->like()->where('profile_id',$profileId)->count() === 1;
        $meta['likeCount'] = $this->likeCount;
        $meta['commentCount'] = $this->comments()->count();
    
        return $meta;
    }
}
