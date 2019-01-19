<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = ['name','is_active'];

    protected $visible = ['id','name','is_active'];


}
