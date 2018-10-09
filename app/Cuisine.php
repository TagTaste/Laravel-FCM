<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuisine extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','country','is_active'];

    protected $visible = ['id', 'name','country','is_active'];
}
