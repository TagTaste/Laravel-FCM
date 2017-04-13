<?php

namespace App\Ideabook;

use \App\Recipe as BaseRecipe;

class Recipe extends BaseRecipe
{
    protected $visible = ['id','name','description','imageUrl','pivot','profile_id'];
    
    protected $fillable = ['note'];
    
}
