<?php

namespace App\Filter;

use App\Recipe as BaseRecipe;

class Recipe extends BaseRecipe {
    protected $with = [];
    protected $visible = ['level','type','key','value'];
    protected $appends = [];

}