<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $visible = ['name'];

    protected $fillable = ['name'];

    public $timestamps = false;
}
