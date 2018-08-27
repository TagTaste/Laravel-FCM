<?php

namespace App\Collaborate;

use Illuminate\Database\Eloquent\Model;


class Job extends Model {

    protected $table = 'collaborate_profiles_job';

    protected $fillable = ['collaborate_id','job_id'];

    protected $visible = ['collaborate_id','job_id'];

}
