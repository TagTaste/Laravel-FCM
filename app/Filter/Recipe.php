<?php

namespace App\Filter;

use App\Filter;

class Recipe extends Filter {
    
    protected $table = "recipe_filters";
    
    protected $csv = ['tags'];
    
    protected $strings = ['level','type','Food Type'=>'veg'];
    
    protected $models = ['cuisine.name'];
    
    public static $cacheKey = "recipe:";
    
    public static $relatedColumn = 'recipe_id';
    
    public function getlevelattribute(&$model)
    {
        return \App\Recipe::$level[$model->level] ?? null;
    }
    
    public function gettypeattribute(&$model)
    {
        return \App\Recipe::$type[$model->type] ?? null;
    }
    
    public function getvegattribute(&$model)
    {
        return \App\Recipe::$veg[$model->is_vegetarian] ?? null;
    }

}