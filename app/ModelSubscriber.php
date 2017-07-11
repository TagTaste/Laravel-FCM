<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelSubscriber extends Model
{
    protected $fillable = ['model', 'model_id', 'profile_id', 'muted_on'];
}
