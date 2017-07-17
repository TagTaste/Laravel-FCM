<?php

namespace App\Filter;

use App\Recipe as BaseRecipe;

class Recipe extends BaseRecipe {
    protected $with = [];
    protected $visible = ['cuisine_id','level','type'];
    protected $appends = [];
}