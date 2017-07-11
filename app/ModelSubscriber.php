<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelSubscriber extends Model
{
    use SoftDeletes;
    protected $fillable = ['model', 'model_id', 'profile_id', 'muted_on'];
}
