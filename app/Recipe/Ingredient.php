<?php

namespace App\Recipe;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{

    protected $table = "recipe_ingredients";

    protected $fillable = ['name','recipe_id','key','value'];

    protected $visible = ['id','name'];

}
