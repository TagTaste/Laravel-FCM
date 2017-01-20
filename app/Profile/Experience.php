<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }
}
