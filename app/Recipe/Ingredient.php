<?php

namespace App\Recipe;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{

    protected $table = "recipe_ingredients";

    protected $fillable = ['description','recipe_id'];

    protected $visible = ['id','description'];

}
