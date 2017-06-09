<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollaborateShareLike extends Model
{
    //
    public $timestamps = false;

    protected $fillable = ['id','profile_id'];
}
