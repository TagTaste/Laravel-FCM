<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileType extends Model
{
    protected $fillable = ['type','enabled','default'];

    protected $casts = ['enabled' => 'boolean', 'default' => 'boolean'];
}
