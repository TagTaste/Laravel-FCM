<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use SoftDeletes;
    
    public static $expectsFiles = true;
    public static $fileInputs = ['image' => 'recipes/images'];
    protected $fillable = ['name','showcase','description','content', 'ingredients',
        'category', 'serving', 'calorie', 'time', 'image',
        'preparation_time','cooking_time','level','tags',
        'profile_id','privacy_id'];
    protected $dates = ['created_at','deleted_at'];
    protected $visible = ['name','description','ingredients','imageUrl','category','serving', 'calorie',
        'preparation_time','cooking_time','level','tags',
        'created_at',
        'time','pivot','profile','likeCount'];
    protected $with = ['profile'];
    protected $appends = ['imageUrl','likeCount'];

    public function profile() {
    	return $this->belongsTo(\App\Recipe\Profile::class);
    }

    //specific for API
    public function getImageUrlAttribute()
    {
        return "/profile/recipe/" . $this->id . '.jpg';

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
}
