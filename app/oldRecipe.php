<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class oldRecipe extends Model
{
    use SoftDeletes;

    protected $fillable = ['dish_id','step','difficulty_level','content','template_id','parent_id'];

    protected $dates = ['deleted_at'];

    public static function boot() {

    }

    public function dish() {
    	return $this->belongsTo(\App\Recipe::class,'dish_id');
    }

    public function template(){
    	return $this->belongsTo('\App\Template','template_id');
    }

    public function parent() {
    	return $this->belongsTo(self::class,'parent_id');
    }
}
