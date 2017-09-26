<?php

namespace App;

use App\Channel\Payload;
use App\Interfaces\CommentNotification;
use App\Interfaces\Feedable;
use App\Traits\CachedPayload;
use App\Traits\IdentifiesOwner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model implements Feedable, CommentNotification
{
    use SoftDeletes, CachedPayload, IdentifiesOwner;
    
    public static $expectsFiles = true;
    protected $fillable = ['name','description', 'serving',
        'preparation_time','cooking_time','level','tags','cuisine_id','type',
        'profile_id','privacy_id','payload_id','directions','tutorial_link','is_vegetarian'];
    protected $dates = ['created_at','deleted_at'];
    

    protected $visible = ['id','name','description','serving',
        'preparation_time','cooking_time','level','tags','likeCount','type',
        'created_at','pivot','profile','ingredients','equipments','images','directions','rating','cuisine_id',
        'tutorial_link','cuisine','is_vegetarian'];
    
    protected $with = ['profile','ingredients','equipments','images','cuisine'];

    protected $appends = ['likeCount','rating'];

    public static $level = ['Easy','Medium','Hard'];
    public static $type = ['Vegan','Vegetarian','Non-Vegetarian'];
    public static $veg = ['Vegetarian','Non-Vegetarian'];

    public static function boot()
    {
        self::created(function($recipe){
            //\Redis::set("recipe:" . $recipe->id,$recipe->makeHidden(['profile','likeCount'])->toJson());
    
            //create the document for searching
            \App\Documents\Recipe::create($recipe);
        });
        
        self::updated(function($recipe){
            
            //update the document
            \App\Documents\Recipe::create($recipe);
        });
    }
    
    public function addToCache()
    {
        \Redis::set("recipe:" . $this->id,$this->makeHidden(['profile','likeCount'])->toJson());
    }
    public function profile() {
    	return $this->belongsTo(\App\Recipe\Profile::class);
    }
    
    public function getNotificationContent()
    {
        $showcaseImage = null;
        foreach($this->images as $image)
        {
            if($image->show_case)
            {
                $showcaseImage = $image->imageUrl;
                break;
            }
        }
    
        return [
            'name' => strtolower(class_basename(self::class)),
            'id' => $this->id,
            'content' => $this->name,
            'image' => $showcaseImage
        ];
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
        $count = \Redis::sCard("meta:recipe:likes:" . $this->id);
        if(is_null($count)) return 0;
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
        $key = "meta:recipe:likes:" . $this->id;
        $meta['hasLiked'] = \Redis::sIsMember($key,$profileId) === 1;
        $meta['likeCount'] = \Redis::sCard($key);
        $meta['commentCount'] = $this->comments()->count();
        $meta['sharedAt']= \App\Shareable\Share::getSharedAt($this);
        return $meta;
    }
    
    public function getCommentNotificationMessage() : string
    {
        return "New comment on " . $this->name . "recipe.";
    }

    public function ingredients()
    {
        return $this->hasMany('App\Recipe\Ingredient');
    }

    public function equipments()
    {
        return $this->hasMany('App\Recipe\Equipment');
    }

    public function images()
    {
        return $this->hasMany('App\Recipe\Image');
    }

    public function rating()
    {
        return $this->hasMany(RecipeRating::class,'recipe_id');
    }

    public function getRatingAttribute()
    {
        return $this->rating()->avg('rating');
    }
    
    /**
     * @return array
     */
    public function getFillable(): array
    {
        return $this->fillable;
    }
    
    public function cuisine()
    {
        return $this->belongsTo(Cuisine::class);
    }
}
