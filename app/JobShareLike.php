<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobShareLike extends Model
{
     public $timestamps = false;

    protected $fillable = ['id','profile_id'];
}
