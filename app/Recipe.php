<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['name','showcase','description','content', 'ingredients',
        'category', 'serving', 'calorie', 'time', 'image',
        'preparation_time','cooking_time','level','tags',
        'profile_id'];


    protected $dates = ['created_at','deleted_at'];

    protected $visible = ['name','description','ingredients','imageUrl','category','serving', 'calorie',
        'preparation_time','cooking_time','level','tags',
        'created_at',
        'time','pivot','profile'];
    
    protected $with = ['profile'];

    public static $expectsFiles = true;

    public static $fileInputs = ['image' => 'recipes/images'];

    protected $appends = ['imageUrl'];

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
}
