<?php

namespace App\Job;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'job_types';
    protected $fillable = ['name', 'description'];
}
