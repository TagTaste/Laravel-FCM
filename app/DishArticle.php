<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DishArticle extends Model
{
    protected $fillable = ['showcase','description','hasRecipe','article_id','chef_id', 'ingredients', 'category', 'serving', 'calorie', 'time', 'image'];

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


    public static function getAsArray($chefId) {
        return static::with('article')->where('chef_id','=',$chefId)->get()->pluck('article.title','id');
    }
}
