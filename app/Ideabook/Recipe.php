<?php

namespace App\Ideabook;

use \App\Recipe as BaseRecipe;

class Recipe extends BaseRecipe
{
    protected $visible = ['name','description','imageUrl'];
    
    protected $fillable = ['note'];
    
}
