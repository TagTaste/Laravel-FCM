<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = ['company_addresses'];

    protected $fillable = ['address','country','phone','company_id'];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}
