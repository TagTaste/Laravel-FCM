<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PollingOption extends Model
{
    protected $fillable = ['text','poll_id','count'];

    protected $visible = ['id','text','poll_id','count'];
}
