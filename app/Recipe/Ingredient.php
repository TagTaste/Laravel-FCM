<?php

namespace App\Recipe;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use SoftDeletes;

    protected $table = "recipe_ingredients";

    protected $fillable = ['name','recipe_id'];

    protected $visible = ['id','name','key','value'];

}
