<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecipeShareLike extends Model
{
     public $timestamps = false;

    protected $fillable = ['id','profile_id'];
}
