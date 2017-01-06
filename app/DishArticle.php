<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DishArticle extends Model
{
    protected $fillable = ['showcase','description','hasRecipe','article_id', 'ingredients', 'category', 'serving', 'calorie', 'time', 'image'];

    public static $expectsFiles = true;

    public static $fileInputs = ['image' => 'dishes/images'];

    public function article() {
    	return $this->belongsTo('\App\Article','article_id');
    }

    public function chef() {
    	return $this->belongsTo('\App\Profile','chef_id');
    }

    public function recipe() {
    	return $this->hasMany('\App\RecipeArticle','dish_id');
    }

    public static function getAsArray($userId,$profileTypeId) {
        return static::whereHas('article',function($query) use ($userId,$profileTypeId) {
            $query->where('user_id',$userId)->where('profile_type_id',$profileTypeId);
        })->get()->pluck('article.title','id');
    }
}
