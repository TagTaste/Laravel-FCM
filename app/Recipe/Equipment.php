<?php

namespace App\Recipe;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;

    protected $table = "recipe_equipments";

    protected $fillable = ['name','recipe_id'];

    protected $visible = ['id','name'];


}
