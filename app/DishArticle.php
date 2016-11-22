<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DishArticle extends Model
{
    protected $fillable = ['showcase','hasRecipe','article_id','chef_id'];

    public function article() {
    	return $this->belongsTo('\App\Article','article_id');
    }

    public function chef() {
    	return $this->belongsTo('\App\Profile','chef_id');
    }
}
