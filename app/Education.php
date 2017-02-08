<?php

namespace App;

use App\Traits\StartEndDate;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use StartEndDate;

    public function profile()
    {
        return $this->belongsTo('App\Profile');
    }
}
