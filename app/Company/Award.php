<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    public function company()
    {
        return $this->belongsToMany('App\Company','company_awards','award_id','company_id');

    }
}
