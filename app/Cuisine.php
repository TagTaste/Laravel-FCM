<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuisine extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    protected $visible = ['id', 'name','key','value'];
}
