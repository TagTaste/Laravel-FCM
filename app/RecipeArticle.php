<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RecipeArticle extends Model
{
    use SoftDeletes;

    protected $fillable = ['dish_id','step','difficulty_level','content','template_id','parent_id'];

    protected $dates = ['deleted_at'];

    public static function boot() {

    }

    public function dish() {
    	return $this->belongsTo('\App\DishArticle','dish_id');
    }

    public function template(){
    	return $this->belongsTo('\App\Template','template_id');
    }

    public function parent() {
    	return $this->belongsTo('\App\RecipeArticle','parent_id');
    }
}
