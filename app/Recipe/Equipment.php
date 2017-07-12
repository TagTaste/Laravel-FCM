<?php

namespace App\Recipe;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table = "recipe_equipments";

    protected $fillable = ['name','recipe_id'];

    protected $visible = ['id','name'];


}
