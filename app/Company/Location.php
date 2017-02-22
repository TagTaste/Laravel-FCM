<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = ['company_locations'];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}
