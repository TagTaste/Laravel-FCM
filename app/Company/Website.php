<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $table = 'company_websites';

    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}
