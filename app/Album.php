<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = ['name','description','profile_id'];

    public function photos()
    {
        return $this->hasMany('App\Photo');
    }
}
