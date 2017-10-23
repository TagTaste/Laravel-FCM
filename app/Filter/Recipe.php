<?php

namespace App\Filter;

use App\Filter;

class Recipe extends Filter {
    
    protected $table = "recipe_filters";
    
    protected $csv = ['tags'];
    
    protected $strings = ['serving'];
    
    protected $models = ['cuisine.name'];
    
    public static $cacheKey = "recipe:";
    
    public static $relatedColumn = 'recipe_id';

}