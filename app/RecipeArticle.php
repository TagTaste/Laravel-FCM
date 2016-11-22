<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeArticle extends Model
{
    $fillable = ['dish_id','step','content','template_id','parent_id'];

    public function dish() {
    	return $this->belongsTo('\App\DishRecipe','dish_id');
    }

    public function template(){
    	return $this->belongsTo('\App\Template','template_id');
    }

    public function parent() {
    	return $this->belongsTo('\App\RecipeArticle','parent_id');
    }
}
