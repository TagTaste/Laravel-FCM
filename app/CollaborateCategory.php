<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CollaborateCategory extends Model
{
    protected $table = 'collaborate_categories';

    protected $fillable = ['name', 'description'];

    protected $visible = ['id', 'name','description'];

}
