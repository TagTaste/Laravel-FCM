<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'company_addresses';

    protected $fillable = ['address','country','phone','company_id'];

    protected $visible = ['id','address','country','phone'];
}
