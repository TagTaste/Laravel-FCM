<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $fillable = ['name','description','date','profile_id'];
}
