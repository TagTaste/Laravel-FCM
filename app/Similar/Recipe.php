<?php namespace App\Similar;

use App\Recipe as BaseRecipe;

class Recipe extends BaseRecipe
{
    protected $visible = ['id','name','imageUrl'];
    
    public function similar()
    {
        return self::take(4)->get();
    }
}