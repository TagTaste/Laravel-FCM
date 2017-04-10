<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dish extends Model
{
    use SoftDeletes;

    protected $fillable = ['showcase','description','hasRecipe', 'ingredients', 'category', 'serving', 'calorie', 'time', 'image'];

    protected $dates = ['deleted_at'];

    protected $visible = ['description','ingredients','imageUrl','category','serving', 'calorie', 'time', 'hasRecipe'];

    public static $expectsFiles = true;

    public static $fileInputs = ['image' => 'dishes/images'];

    protected $appends = ['imageUrl'];

    public static function boot()
    {
        self::deleting(function($dish){
            if($dish->recipe){
                $dish->recipe->delete();
            }
        });
    }

    public function profile() {
    	return $this->belongsTo('\App\Profile','chef_id');
    }

    public function recipe() {
    	return $this->hasMany(\App\Recipe::class,'dish_id');
    }

    public static function getAsArray($userId,$profileTypeId) {
        return static::whereHas('article',function($query) use ($userId,$profileTypeId) {
            $query->where('user_id',$userId)->where('profile_type_id',$profileTypeId);
        })->get()->pluck('article.title','id');
    }

    //specific for API
    public function getImageUrlAttribute()
    {
        return "/profile/dish/" . $this->id . '.jpg';

    }
}
