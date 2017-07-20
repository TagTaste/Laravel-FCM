<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeRating extends Model
{
    public $timestamps = false;

    protected $fillable = ['recipe_id','profile_id','rating'];

    protected $visible = ['rating','profile_id'];

}
