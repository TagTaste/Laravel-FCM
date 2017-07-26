<?php

namespace App\Job;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'job_notifications';
    protected $fillable = ['job_id', 'profile_id','is_notify'];

}
