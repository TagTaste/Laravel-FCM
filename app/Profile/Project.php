<?php

namespace App\Profile;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }
}
