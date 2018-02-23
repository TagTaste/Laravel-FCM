<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Onboarding extends Model
{
    //
    protected $table = 'onboarding';
    protected $fillable = ['key','value'];
    protected $visible = ['key','value'];
}
